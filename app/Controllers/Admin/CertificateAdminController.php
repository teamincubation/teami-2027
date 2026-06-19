<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BaseModel;
use App\Services\FileStorage;
use Exception;
use PDO;

class CertificateAdminController extends BaseController {

    private FileStorage $storage;

    public function __construct() {
        $this->storage = new FileStorage();
    }

    public function index(): void {
        $db = BaseModel::getConnection();
        $stmt = $db->query("
            SELECT c.*, ct.name as type_name 
            FROM certificates c 
            JOIN certificate_types ct ON c.type_id = ct.id 
            ORDER BY c.issued_date DESC, c.created_at DESC
        ");
        $certificates = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->render('admin/certificates/index', [
            'title' => 'Certificate Registry | Admin',
            'certificates' => $certificates,
            'active' => 'certificates'
        ], 'admin');
    }

    public function create(): void {
        $db = BaseModel::getConnection();
        $stmt = $db->query("SELECT id, name FROM certificate_types ORDER BY id ASC");
        $types = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->render('admin/certificates/create', [
            'title' => 'Issue Certificate | Admin',
            'types' => $types,
            'active' => 'certificates'
        ], 'admin');
    }

    public function store(): void {
        $certNo = trim($_POST['certificate_number'] ?? '');
        $holderName = trim($_POST['holder_name'] ?? '');
        $typeId = intval($_POST['type_id'] ?? 1);
        $issuedDate = trim($_POST['issued_date'] ?? '');
        $status = trim($_POST['status'] ?? 'Active');
        $associatedProgramme = trim($_POST['associated_programme'] ?? '');
        $grade = trim($_POST['grade'] ?? 'Outstanding');
        $remarks = trim($_POST['remarks'] ?? '');

        $errors = [];
        if (empty($certNo)) {
            $errors[] = "Certificate Number is required.";
        }
        if (empty($holderName)) {
            $errors[] = "Holder Name is required.";
        }
        if (empty($issuedDate)) {
            $errors[] = "Issued Date is required.";
        }
        if (empty($_FILES['file']['name'])) {
            $errors[] = "Certificate PDF File is required.";
        }

        if (!empty($errors)) {
            $_SESSION['flash_errors'] = $errors;
            $this->redirect('/admin/certificates/create');
        }

        $searchKey = preg_replace('/[^A-Za-z0-9]/', '', $certNo);
        $filenameKey = preg_replace('/[^A-Za-z0-9-]/', '-', $certNo);

        try {
            $filePath = $this->storage->store($_FILES['file'], 'certificates', ['application/pdf']);

            $metadata = json_encode([
                'grade' => $grade,
                'remarks' => $remarks
            ]);

            $db = BaseModel::getConnection();
            $stmt = $db->prepare("
                INSERT INTO certificates (
                    certificate_number, search_key, filename_key, holder_name, type_id, 
                    issued_date, status, file_path, associated_programme, metadata
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $certNo, $searchKey, $filenameKey, $holderName, $typeId,
                $issuedDate, $status, $filePath, empty($associatedProgramme) ? null : $associatedProgramme, $metadata
            ]);

            $_SESSION['flash_success'] = "Certificate issued successfully.";
            $this->redirect('/admin/certificates');
        } catch (Exception $e) {
            $_SESSION['flash_errors'] = ["Error issuing certificate: " . $e->getMessage()];
            $this->redirect('/admin/certificates/create');
        }
    }

    public function edit(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT * FROM certificates WHERE id = ?");
        $stmt->execute([intval($id)]);
        $cert = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cert) {
            $_SESSION['flash_error'] = "Certificate not found.";
            $this->redirect('/admin/certificates');
        }

        $stmtTypes = $db->query("SELECT id, name FROM certificate_types ORDER BY id ASC");
        $types = $stmtTypes->fetchAll(PDO::FETCH_ASSOC);

        $meta = !empty($cert['metadata']) ? json_decode($cert['metadata'], true) : [];

        $this->render('admin/certificates/edit', [
            'title' => 'Edit Certificate | Admin',
            'cert' => $cert,
            'types' => $types,
            'meta' => $meta,
            'active' => 'certificates'
        ], 'admin');
    }

    public function update(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT * FROM certificates WHERE id = ?");
        $stmt->execute([intval($id)]);
        $cert = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cert) {
            $_SESSION['flash_error'] = "Certificate not found.";
            $this->redirect('/admin/certificates');
        }

        $certNo = trim($_POST['certificate_number'] ?? '');
        $holderName = trim($_POST['holder_name'] ?? '');
        $typeId = intval($_POST['type_id'] ?? 1);
        $issuedDate = trim($_POST['issued_date'] ?? '');
        $status = trim($_POST['status'] ?? 'Active');
        $associatedProgramme = trim($_POST['associated_programme'] ?? '');
        $grade = trim($_POST['grade'] ?? 'Outstanding');
        $remarks = trim($_POST['remarks'] ?? '');

        $errors = [];
        if (empty($certNo)) {
            $errors[] = "Certificate Number is required.";
        }
        if (empty($holderName)) {
            $errors[] = "Holder Name is required.";
        }
        if (empty($issuedDate)) {
            $errors[] = "Issued Date is required.";
        }

        if (!empty($errors)) {
            $_SESSION['flash_errors'] = $errors;
            $this->redirect('/admin/certificates/edit/' . $id);
        }

        $searchKey = preg_replace('/[^A-Za-z0-9]/', '', $certNo);
        $filenameKey = preg_replace('/[^A-Za-z0-9-]/', '-', $certNo);

        try {
            $filePath = $cert['file_path'];
            if (!empty($_FILES['file']['name'])) {
                $filePath = $this->storage->store($_FILES['file'], 'certificates', ['application/pdf']);
                if (!empty($cert['file_path'])) {
                    $this->storage->delete($cert['file_path']);
                }
            }

            $metadata = json_encode([
                'grade' => $grade,
                'remarks' => $remarks
            ]);

            $stmtUpdate = $db->prepare("
                UPDATE certificates 
                SET certificate_number = ?, search_key = ?, filename_key = ?, holder_name = ?, 
                    type_id = ?, issued_date = ?, status = ?, file_path = ?, 
                    associated_programme = ?, metadata = ?
                WHERE id = ?
            ");

            $stmtUpdate->execute([
                $certNo, $searchKey, $filenameKey, $holderName, $typeId,
                $issuedDate, $status, $filePath, empty($associatedProgramme) ? null : $associatedProgramme, 
                $metadata, intval($id)
            ]);

            $_SESSION['flash_success'] = "Certificate updated successfully.";
            $this->redirect('/admin/certificates');
        } catch (Exception $e) {
            $_SESSION['flash_errors'] = ["Error updating certificate: " . $e->getMessage()];
            $this->redirect('/admin/certificates/edit/' . $id);
        }
    }

    public function delete(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT * FROM certificates WHERE id = ?");
        $stmt->execute([intval($id)]);
        $cert = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cert) {
            if (!empty($cert['file_path'])) {
                $this->storage->delete($cert['file_path']);
            }
            $stmtDel = $db->prepare("DELETE FROM certificates WHERE id = ?");
            $stmtDel->execute([intval($id)]);
            $_SESSION['flash_success'] = "Certificate deleted successfully.";
        } else {
            $_SESSION['flash_error'] = "Certificate not found.";
        }

        $this->redirect('/admin/certificates');
    }

    public function importForm(): void {
        $this->render('admin/certificates/import', [
            'title' => 'Bulk Certificate Importer | Admin',
            'active' => 'cert_import'
        ], 'admin');
    }

    public function import(): void {
        if (empty($_FILES['csv_file']['name'])) {
            $_SESSION['flash_error'] = "Please upload a CSV file.";
            $this->redirect('/admin/certificates/import');
        }

        $fileTmp = $_FILES['csv_file']['tmp_name'];
        
        try {
            $db = BaseModel::getConnection();
            
            // Map of type_code -> id
            $stmtTypes = $db->query("SELECT id, code FROM certificate_types");
            $typesMap = [];
            while ($row = $stmtTypes->fetch(PDO::FETCH_ASSOC)) {
                $typesMap[strtoupper($row['code'])] = intval($row['id']);
            }

            $imported = 0;
            $errors = [];
            $batchId = 'CSV-IMPORT-' . time();

            if (($handle = fopen($fileTmp, "r")) !== FALSE) {
                // Read and check header
                $header = fgetcsv($handle, 1000, ",");
                
                $db->beginTransaction();
                
                $rowNum = 1;
                while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $rowNum++;
                    // Expected columns: certificate_number, holder_name, type_code, issued_date, associated_programme, file_path, grade, remarks
                    $certNo = trim($row[0] ?? '');
                    $holderName = trim($row[1] ?? '');
                    $typeCode = strtoupper(trim($row[2] ?? ''));
                    $issuedDate = trim($row[3] ?? '');
                    $associatedProg = trim($row[4] ?? '');
                    $filePath = trim($row[5] ?? '');
                    $grade = trim($row[6] ?? 'Outstanding');
                    $remarks = trim($row[7] ?? '');

                    if (empty($certNo) || empty($holderName) || empty($issuedDate)) {
                        $errors[] = "Row {$rowNum}: Skipping row due to missing required fields (No, Name, or Date).";
                        continue;
                    }

                    $typeId = $typesMap[$typeCode] ?? 1; // Default to Internship (id 1)
                    $searchKey = preg_replace('/[^A-Za-z0-9]/', '', $certNo);
                    $filenameKey = preg_replace('/[^A-Za-z0-9-]/', '-', $certNo);

                    if (empty($filePath)) {
                        $filePath = 'certificates/imported/' . $filenameKey . '.pdf';
                    }

                    $metadata = json_encode([
                        'grade' => $grade,
                        'remarks' => $remarks
                    ]);

                    $stmtIns = $db->prepare("
                        INSERT INTO certificates (
                            certificate_number, search_key, filename_key, holder_name, type_id, 
                            issued_date, status, file_path, associated_programme, metadata, import_batch_id
                        ) VALUES (?, ?, ?, ?, ?, ?, 'Active', ?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE 
                            holder_name = VALUES(holder_name), 
                            type_id = VALUES(type_id),
                            issued_date = VALUES(issued_date),
                            file_path = VALUES(file_path),
                            associated_programme = VALUES(associated_programme),
                            metadata = VALUES(metadata)
                    ");

                    $stmtIns->execute([
                        $certNo, $searchKey, $filenameKey, $holderName, $typeId,
                        $issuedDate, $filePath, empty($associatedProg) ? null : $associatedProg, 
                        $metadata, $batchId
                    ]);

                    $imported++;
                }

                $db->commit();
                fclose($handle);
            }

            $successMsg = "Successfully processed CSV. {$imported} certificates imported/updated.";
            if (!empty($errors)) {
                $_SESSION['flash_errors'] = array_merge([$successMsg], $errors);
            } else {
                $_SESSION['flash_success'] = $successMsg;
            }

            $this->redirect('/admin/certificates');
        } catch (Exception $e) {
            $_SESSION['flash_error'] = "Bulk import failed: " . $e->getMessage();
            $this->redirect('/admin/certificates/import');
        }
    }
}
