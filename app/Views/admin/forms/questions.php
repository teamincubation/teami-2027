<div class="actions-header" style="margin-bottom: 2rem;">
    <a href="/admin/forms" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Form Builder</a>
</div>

<div class="form-card">
    <h2 style="font-family:'Outfit', sans-serif; font-weight: 800; font-size: 1.5rem; margin-bottom: 0.5rem; color: var(--text-main);"><?= htmlspecialchars($title) ?></h2>
    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 2rem;">Add, arrange, or remove dynamic fields for this form. Clear the question text to discard an entry.</p>

    <form action="/admin/forms/<?= $type ?>/<?= $type === 'event' ? $event['id'] : $internship['id'] ?>" method="POST" autocomplete="off">
        <?= csrf_field() ?>

        <div class="table-container">
            <table class="admin-table" id="questionsTable">
                <thead>
                    <tr>
                        <th>Question Label <span class="required">*</span></th>
                        <th style="width: 200px;">Input Type</th>
                        <th style="width: 250px;">Options (for Select / Checkbox)</th>
                        <th style="width: 100px; text-align: center;">Required</th>
                        <th style="width: 80px; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($questions)): ?>
                        <!-- Render a default row if empty -->
                        <tr class="question-row">
                            <td>
                                <input type="text" name="question_text[]" required placeholder="e.g. Do you have any previous volunteering experience?" class="form-control">
                            </td>
                            <td>
                                <select name="question_type[]" class="form-control select-type">
                                    <option value="text">Short Answer (Text)</option>
                                    <option value="textarea">Paragraph (Textarea)</option>
                                    <option value="select">Dropdown (Select)</option>
                                    <option value="checkbox">Multiple Choice (Checkbox)</option>
                                    <option value="file">File Upload (PDF/Image)</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="options[]" placeholder="e.g. Yes, No (comma-separated)" class="form-control options-input" disabled>
                            </td>
                            <td style="text-align: center;">
                                <label class="checkbox-container">
                                    <input type="checkbox" name="is_required[0]" checked value="1">
                                    <span class="checkmark"></span>
                                </label>
                            </td>
                            <td style="text-align: right;">
                                <button type="button" class="action-btn-icon delete-btn" onclick="removeRow(this)"><i class="fa-regular fa-trash-can"></i></button>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($questions as $index => $q): ?>
                            <tr class="question-row">
                                <td>
                                    <input type="text" name="question_text[]" required value="<?= htmlspecialchars($q['question_text']) ?>" class="form-control">
                                </td>
                                <td>
                                    <select name="question_type[]" class="form-control select-type">
                                        <option value="text" <?= $q['question_type'] === 'text' ? 'selected' : '' ?>>Short Answer (Text)</option>
                                        <option value="textarea" <?= $q['question_type'] === 'textarea' ? 'selected' : '' ?>>Paragraph (Textarea)</option>
                                        <option value="select" <?= $q['question_type'] === 'select' ? 'selected' : '' ?>>Dropdown (Select)</option>
                                        <option value="checkbox" <?= $q['question_type'] === 'checkbox' ? 'selected' : '' ?>>Multiple Choice (Checkbox)</option>
                                        <option value="file" <?= $q['question_type'] === 'file' ? 'selected' : '' ?>>File Upload (PDF/Image)</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="options[]" value="<?= htmlspecialchars($q['options'] ?? '') ?>" placeholder="e.g. Yes, No (comma-separated)" class="form-control options-input" <?= in_array($q['question_type'], ['select', 'checkbox']) ? '' : 'disabled' ?>>
                                </td>
                                <td style="text-align: center;">
                                    <label class="checkbox-container">
                                        <input type="checkbox" name="is_required[<?= $index ?>]" <?= $q['is_required'] ? 'checked' : '' ?> value="1">
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                                <td style="text-align: right;">
                                    <button type="button" class="action-btn-icon delete-btn" onclick="removeRow(this)"><i class="fa-regular fa-trash-can"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            <button type="button" class="btn-builder" onclick="addRow()"><i class="fa-solid fa-plus" style="margin-right: 0.4rem;"></i> Add Question Field</button>
        </div>

        <div class="form-actions-bar">
            <a href="/admin/forms" class="btn-cancel">Cancel</a>
            <button type="submit" class="btn-submit">Save Form Questions <i class="fa-regular fa-floppy-disk" style="margin-left: 0.5rem;"></i></button>
        </div>
    </form>
</div>

<script>
    let rowIndex = <?= max(1, count($questions ?? [])) ?>;

    // Handle select changes to enable/disable options input
    document.addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('select-type')) {
            const row = e.target.closest('tr');
            const optionsInput = row.querySelector('.options-input');
            const type = e.target.value;
            
            if (type === 'select' || type === 'checkbox') {
                optionsInput.removeAttribute('disabled');
                optionsInput.focus();
            } else {
                optionsInput.setAttribute('disabled', 'disabled');
                optionsInput.value = '';
            }
        }
    });

    function addRow() {
        const tableBody = document.querySelector('#questionsTable tbody');
        const newRow = document.createElement('tr');
        newRow.className = 'question-row';
        
        newRow.innerHTML = `
            <td>
                <input type="text" name="question_text[]" required placeholder="Enter question label..." class="form-control">
            </td>
            <td>
                <select name="question_type[]" class="form-control select-type">
                    <option value="text">Short Answer (Text)</option>
                    <option value="textarea">Paragraph (Textarea)</option>
                    <option value="select">Dropdown (Select)</option>
                    <option value="checkbox">Multiple Choice (Checkbox)</option>
                    <option value="file">File Upload (PDF/Image)</option>
                </select>
            </td>
            <td>
                <input type="text" name="options[]" placeholder="e.g. Option1, Option2 (comma-separated)" class="form-control options-input" disabled>
            </td>
            <td style="text-align: center;">
                <label class="checkbox-container">
                    <input type="checkbox" name="is_required[${rowIndex}]" checked value="1">
                    <span class="checkmark"></span>
                </label>
            </td>
            <td style="text-align: right;">
                <button type="button" class="action-btn-icon delete-btn" onclick="removeRow(this)"><i class="fa-regular fa-trash-can"></i></button>
            </td>
        `;
        
        tableBody.appendChild(newRow);
        rowIndex++;
    }

    function removeRow(button) {
        const rows = document.querySelectorAll('.question-row');
        if (rows.length <= 1) {
            alert('At least one form field is required.');
            return;
        }
        
        const row = button.closest('tr');
        row.remove();
    }
</script>

<style>
    .back-link {
        color: var(--text-muted);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .back-link:hover { color: var(--primary); }
    .form-card {
        background: #ffffff;
        border: 1px solid var(--border-glow);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.01);
        padding: 2.5rem;
    }
    .table-container { overflow-x: auto; margin-bottom: 1.5rem; }
    .admin-table { width: 100%; border-collapse: collapse; text-align: left; }
    .admin-table th { background: #fafbfc; padding: 0.85rem 1rem; font-weight: 600; color: var(--text-muted); border-bottom: 1px solid var(--border-glow); }
    .admin-table td { padding: 0.75rem 1rem; border-bottom: 1px solid var(--border-glow); vertical-align: middle; }
    .form-control {
        width: 100%;
        padding: 0.6rem 0.85rem;
        border: 1px solid var(--border-glow);
        border-radius: 8px;
        font-family: 'Inter', sans-serif;
        font-size: 0.9rem;
        color: var(--text-main);
        outline: none;
        background-color: #fafbfc;
        transition: var(--transition);
    }
    .form-control:focus { border-color: var(--primary); background-color: #ffffff; box-shadow: 0 0 0 3px rgba(38, 181, 209, 0.12); }
    .required { color: #ef4444; }
    .btn-builder {
        background-color: #f1f5f9;
        color: var(--text-main);
        font-weight: 600;
        padding: 0.55rem 1.1rem;
        border-radius: 8px;
        font-size: 0.9rem;
        border: 1px solid var(--border-glow);
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
    }
    .btn-builder:hover { background-color: var(--pastel-blue); color: #0369a1; border-color: var(--border-active); }
    .action-btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        justify-content: center;
        align-items: center;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-size: 0.95rem;
        transition: var(--transition);
        background: #f1f5f9;
        color: var(--text-muted);
    }
    .delete-btn:hover { background-color: var(--pastel-pink); color: #ef4444; }
    .form-actions-bar { margin-top: 2.5rem; border-top: 1px solid var(--border-glow); padding-top: 1.5rem; display: flex; justify-content: flex-end; gap: 1rem; }
    .btn-cancel {
        background: #f1f5f9;
        color: var(--text-muted);
        text-decoration: none;
        padding: 0.7rem 1.5rem;
        border-radius: 8px;
        font-size: 0.95rem;
        font-weight: 600;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        border: 1px solid transparent;
    }
    .btn-cancel:hover { background: #e2e8f0; color: var(--text-main); }
    .btn-submit {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: #ffffff;
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        padding: 0.7rem 1.75rem;
        border-radius: 8px;
        font-size: 0.95rem;
        box-shadow: 0 4px 15px rgba(38, 181, 209, 0.25);
        transition: var(--transition);
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
    }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(38, 181, 209, 0.4); filter: brightness(1.05); }

    /* Custom Checkbox Design */
    .checkbox-container {
        display: inline-block;
        position: relative;
        padding-left: 25px;
        cursor: pointer;
        font-size: 14px;
        user-select: none;
    }
    .checkbox-container input { position: absolute; opacity: 0; cursor: pointer; height: 0; width: 0; }
    .checkmark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        height: 20px;
        width: 20px;
        background-color: #f1f5f9;
        border: 1px solid var(--border-glow);
        border-radius: 4px;
        transition: var(--transition);
    }
    .checkbox-container input:checked ~ .checkmark { background-color: var(--primary); border-color: var(--primary); }
    .checkmark:after { content: ""; position: absolute; display: none; }
    .checkbox-container input:checked ~ .checkmark:after { display: block; }
    .checkbox-container .checkmark:after {
        left: 7px;
        top: 3px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }
</style>
