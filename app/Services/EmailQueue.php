<?php

namespace App\Services;

use App\Models\BaseModel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailerException;

class EmailQueue extends BaseModel {

    /**
     * Enqueue a new email.
     */
    public function enqueue(string $recipientEmail, ?string $recipientName, string $subject, string $bodyHtml, ?string $bodyText = null): bool {
        try {
            $this->insert('email_queue', [
                'recipient_email' => $recipientEmail,
                'recipient_name' => $recipientName,
                'subject' => $subject,
                'body_html' => $bodyHtml,
                'body_text' => $bodyText,
                'status' => 'pending'
            ]);
            return true;
        } catch (\Exception $e) {
            logMessage('ERROR', "Failed to enqueue email to {$recipientEmail}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Process pending emails in the queue.
     * Called by the cron scheduler script.
     */
    public function process(int $limit = 20): array {
        $emails = $this->select(
            "SELECT * FROM email_queue 
             WHERE status = 'pending' 
                OR (status = 'failed' AND retry_count < max_retries) 
             ORDER BY created_at ASC 
             LIMIT ?", 
            [$limit]
        );

        if (empty($emails)) {
            return ['processed' => 0, 'sent' => 0, 'failed' => 0];
        }

        $sentCount = 0;
        $failedCount = 0;
        
        $mailConfig = require dirname(dirname(__DIR__)) . '/config/mail.php';

        foreach ($emails as $email) {
            // Mark as processing
            $this->update('email_queue', ['status' => 'processing'], 'id = ?', [$email['id']]);

            $mail = new PHPMailer(true);

            try {
                $useSmtp = !(empty($mailConfig['password']) || $mailConfig['password'] === 'smtp_password_here');
                
                if ($useSmtp) {
                    // SMTP Server Settings
                    $mail->isSMTP();
                    $mail->Host       = $mailConfig['host'];
                    $mail->SMTPAuth   = $mailConfig['settings']['smtp_auth'];
                    $mail->Username   = $mailConfig['username'];
                    $mail->Password   = $mailConfig['password'];
                    $mail->SMTPSecure = $mailConfig['encryption'] === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = $mailConfig['port'];
                } else {
                    // Fallback to PHP native mail()
                    $mail->isMail();
                }
                $mail->CharSet    = 'UTF-8';

                // Recipients
                $mail->setFrom($mailConfig['from']['address'], $mailConfig['from']['name']);
                $mail->addAddress($email['recipient_email'], $email['recipient_name'] ?? '');

                // Content
                $mail->isHTML($mailConfig['settings']['html']);
                $mail->Subject = $email['subject'];
                $mail->Body    = $email['body_html'];
                
                if ($email['body_text']) {
                    $mail->AltBody = $email['body_text'];
                } else {
                    $mail->AltBody = strip_tags($email['body_html']);
                }

                // Send email with automatic SMTP -> mail() fallback
                try {
                    $mail->send();
                } catch (\Exception $e) {
                    if ($useSmtp) {
                        logMessage('WARNING', "SMTP failed, trying native PHP mail() fallback: " . $e->getMessage());
                        $mail->isMail();
                        $mail->send();
                    } else {
                        throw $e;
                    }
                }

                // Update Status to Sent
                $this->update(
                    'email_queue', 
                    [
                        'status' => 'sent', 
                        'sent_at' => date('Y-m-d H:i:s'), 
                        'error_message' => null
                    ], 
                    'id = ?', 
                    [$email['id']]
                );
                
                $sentCount++;
            } catch (MailerException $e) {
                // Update Status to Failed
                $retries = $email['retry_count'] + 1;
                $newStatus = ($retries >= $email['max_retries']) ? 'failed' : 'pending'; // Let it fall back to pending to process next run
                
                $this->update(
                    'email_queue',
                    [
                        'status' => $newStatus,
                        'retry_count' => $retries,
                        'error_message' => substr($e->getMessage(), 0, 500)
                    ],
                    'id = ?',
                    [$email['id']]
                );
                
                logMessage('ERROR', "Email delivery failed for ID {$email['id']} to {$email['recipient_email']}: " . $e->getMessage());
                $failedCount++;
            }
        }

        return [
            'processed' => count($emails),
            'sent' => $sentCount,
            'failed' => $failedCount
        ];
    }
}
