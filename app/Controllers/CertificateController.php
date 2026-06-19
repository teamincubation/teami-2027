<?php

namespace App\Controllers;

use PDO;
use Exception;

class CertificateController extends BaseController {

    private array $mockRegistry = [
        'TI-2026-INT-101' => [
            'name' => 'Adnan Vellicheri',
            'type' => 'Software Engineering Internship',
            'duration' => '3 Months (Jan 2026 - Mar 2026)',
            'status' => 'Completed & Verified',
            'issued_at' => '2026-04-01',
            'grade' => 'Outstanding (A+)',
            'description' => 'Developed custom MVC routing engines and database migration systems.'
        ],
        'TI-2026-VOL-202' => [
            'name' => 'Sara Nisham',
            'type' => 'Youth Volunteering Campaign',
            'duration' => '6 Weeks (Feb 2026 - Mar 2026)',
            'status' => 'Completed & Verified',
            'issued_at' => '2026-03-25',
            'grade' => 'Excellent (A)',
            'description' => 'Successfully coordinated regional career conclaves and campus outreach initiatives.'
        ]
    ];

    public function index(): void {
        $this->render('verify', [
            'title' => 'Certificate Verification System | Team Incubation',
            'active' => 'verify',
            'searched' => false,
            'result' => null,
            'error' => null,
            'is_mocked' => false
        ]);
    }

    public function verify(): void {
        $code = trim($_POST['certificate_code'] ?? '');
        $result = null;
        $error = null;
        $isMocked = false;

        if (empty($code)) {
            $error = 'Please enter a valid certificate code.';
        } else {
            try {
                // Attempt to connect to local database
                $dbConfig = require dirname(dirname(__DIR__)) . '/config/database.php';
                $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";
                $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
                    PDO::ATTR_TIMEOUT => 2, // Low timeout to fallback fast if offline
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);

                // Query database (assuming certificates table exist)
                $stmt = $pdo->prepare("SELECT * FROM certificates WHERE code = ? LIMIT 1");
                $stmt->execute([$code]);
                $cert = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($cert) {
                    $result = [
                        'name' => $cert['recipient_name'],
                        'type' => $cert['program_type'],
                        'duration' => $cert['duration_string'],
                        'status' => 'Completed & Verified',
                        'issued_at' => $cert['issued_date'],
                        'grade' => $cert['evaluation_grade'],
                        'description' => $cert['remarks'] ?? ''
                    ];
                } else {
                    // Not found in database, check mock registry
                    if (isset($this->mockRegistry[$code])) {
                        $result = $this->mockRegistry[$code];
                        $isMocked = true;
                    } else {
                        $error = 'Certificate code not found in our registry.';
                    }
                }

            } catch (Exception $e) {
                // Database is offline/failed. Fallback to mock data for demonstration
                if (isset($this->mockRegistry[$code])) {
                    $result = $this->mockRegistry[$code];
                    $isMocked = true;
                } else {
                    $error = 'Could not establish database connection, and code is not in mock registry. (Error: ' . $e->getMessage() . ')';
                }
            }
        }

        $this->render('verify', [
            'title' => 'Certificate Verification System | Team Incubation',
            'active' => 'verify',
            'searched' => true,
            'code' => $code,
            'result' => $result,
            'error' => $error,
            'is_mocked' => $isMocked
        ]);
    }
}
