<div class="container-fluid" style="padding: 2rem;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: var(--text-main); margin: 0;">Gallery Photos</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.25rem;">Manage the images and captions displayed on the public gallery page.</p>
        </div>
        <a href="/admin/gallery/create" class="btn-cta" style="box-shadow: none;">
            <i class="fa-solid fa-plus"></i> Add Photo
        </a>
    </div>

    <!-- Alert Box Handling -->
    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="alert-box alert-success" style="margin-bottom: 1.5rem;">
            <i class="fa-solid fa-circle-check"></i>
            <div><?= htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?></div>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="alert-box alert-error" style="margin-bottom: 1.5rem;">
            <i class="fa-solid fa-circle-exclamation"></i>
            <div><?= htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?></div>
        </div>
    <?php endif; ?>

    <!-- Photos Grid List -->
    <?php if (empty($photos)): ?>
        <div style="background: #ffffff; border: 1px solid var(--border-glow); border-radius: 16px; padding: 4rem 2rem; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.01);">
            <i class="fa-solid fa-images" style="font-size: 3.5rem; color: var(--text-muted); margin-bottom: 1rem; opacity: 0.5;"></i>
            <h4 style="color: var(--text-main); margin-bottom: 0.5rem;">No Photos Found</h4>
            <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1.5rem;">Get started by uploading your first photo to the public gallery.</p>
            <a href="/admin/gallery/create" class="btn-cta" style="box-shadow: none; display: inline-block;">Upload Photo</a>
        </div>
    <?php else: ?>
        <div style="background: #ffffff; border: 1px solid var(--border-glow); border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.01);">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
                    <thead>
                        <tr style="border-bottom: 2px solid #f1f5f9; color: var(--text-muted); font-weight: 600;">
                            <th style="padding: 1rem;">Preview</th>
                            <th style="padding: 1rem;">Details</th>
                            <th style="padding: 1rem; text-align: center;">Display Order</th>
                            <th style="padding: 1rem; text-align: center;">Status</th>
                            <th style="padding: 1rem; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($photos as $photo): ?>
                            <tr style="border-bottom: 1px solid #f1f5f9; vertical-align: middle; transition: background 0.2s;">
                                <td style="padding: 1rem;">
                                    <div style="width: 80px; height: 60px; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-glow); background: #f8fafc;">
                                        <img src="<?= media_url($photo['image_path']) ?>" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                </td>
                                <td style="padding: 1rem;">
                                    <div style="font-weight: 600; color: var(--text-main); font-size: 0.95rem;">
                                        <?= !empty($photo['title']) ? htmlspecialchars($photo['title']) : '<em style="color:var(--text-muted);font-weight:normal;">Untitled</em>' ?>
                                    </div>
                                    <div style="color: var(--text-muted); font-size: 0.825rem; margin-top: 0.25rem; max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <?= !empty($photo['caption']) ? htmlspecialchars($photo['caption']) : 'No caption' ?>
                                    </div>
                                </td>
                                <td style="padding: 1rem; text-align: center; font-weight: 500; color: var(--text-main);">
                                    <?= intval($photo['display_order']) ?>
                                </td>
                                <td style="padding: 1rem; text-align: center;">
                                    <?php if ($photo['active'] == 1): ?>
                                        <span style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.75rem; font-weight: 700; background: #e6fcf5; color: #0ca678; padding: 0.25rem 0.6rem; border-radius: 12px;">
                                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #0ca678;"></span> Active
                                        </span>
                                    <?php else: ?>
                                        <span style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.75rem; font-weight: 700; background: #f1f5f9; color: #64748b; padding: 0.25rem 0.6rem; border-radius: 12px;">
                                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #64748b;"></span> Draft
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 1rem; text-align: right;">
                                    <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                                        <a href="/admin/gallery/edit/<?= $photo['id'] ?>" style="color: var(--primary); text-decoration: none; font-size: 1.1rem; padding: 0.25rem;" title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="/admin/gallery/delete/<?= $photo['id'] ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this photo from the gallery?');" style="display: inline;">
                                            <button type="submit" style="background: none; border: none; color: #e03131; font-size: 1.1rem; cursor: pointer; padding: 0.25rem;" title="Delete">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>
