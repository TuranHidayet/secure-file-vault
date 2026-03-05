<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <title>File Vault</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 40px auto; padding: 0 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #f5f5f5; }
        .success { background: #d4edda; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .error   { background: #f8d7da; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .btn { padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 13px; }
        .btn-download { background: #007bff; color: white; }
        .btn-delete   { background: #dc3545; color: white; }
    </style>
</head>
<body>

<h1>📁 File Vault</h1>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="success"><?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<h2>Fayl Yüklə</h2>
<form action="/upload" method="POST" enctype="multipart/form-data">
    <input type="file" name="file" required>
    <button type="submit">Yüklə</button>
</form>

<h2>Yüklənmiş Fayllar</h2>

<?php if (empty($files)): ?>
    <p>Hələ heç bir fayl yüklənməyib.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>№</th>
                <th>Fayl Adı</th>
                <th>Tip</th>
                <th>Ölçü</th>
                <th>Tarix</th>
                <th>Əməliyyatlar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($files as $index => $file): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($file['original_name']) ?></td>
                <td><?= htmlspecialchars($file['file_type']) ?></td>
                <td><?= formatSize($file['file_size']) ?></td>
                <td><?= $file['uploaded_at'] ?></td>
                <td>
                    <a href="/download?id=<?= $file['id'] ?>" class="btn btn-download">Endir</a>
                    <a href="/delete?id=<?= $file['id'] ?>"
                       class="btn btn-delete"
                       onclick="return confirm('Silmək istədiyinizə əminsiniz?')">Sil</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

</body>
</html>