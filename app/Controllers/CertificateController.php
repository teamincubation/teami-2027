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
                $pdo = \App\Models\BaseModel::getConnection();

                $searchKey = preg_replace('/[^A-Za-z0-9]/', '', $code);

                $stmt = $pdo->prepare("
                    SELECT c.*, ct.name as type_name 
                    FROM certificates c 
                    JOIN certificate_types ct ON c.type_id = ct.id 
                    WHERE c.search_key = ? AND c.status = 'Active' 
                    LIMIT 1
                ");
                $stmt->execute([$searchKey]);
                $cert = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($cert) {
                    $meta = !empty($cert['metadata']) ? json_decode($cert['metadata'], true) : [];
                    $result = [
                        'name' => $cert['holder_name'],
                        'type' => $cert['type_name'],
                        'duration' => $cert['associated_programme'] ?? 'Ongoing',
                        'status' => 'Completed & Verified',
                        'issued_at' => $cert['issued_date'],
                        'grade' => $meta['grade'] ?? 'Outstanding',
                        'description' => $meta['remarks'] ?? 'Verified credential registry.'
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
                    $error = 'Could not query database registry, and code is not in mock registry. (Error: ' . $e->getMessage() . ')';
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
