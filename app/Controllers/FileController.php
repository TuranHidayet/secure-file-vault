<?php
class FileController
{
    private FileModel $fileModel;
    private array $uploadConfig;

    public function __construct()
    {
        $this->fileModel = new FileModel();
        $this->uploadConfig = require __DIR__ . '/../../config/upload.php';
    }

    public function index(): void
    {
        $files = $this->fileModel->getAll();
        require __DIR__ . '/../Views/files/index.php';
    }

    public function upload(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /');
            exit;
        }

        if (empty($_FILES['file']['name'])) {
            $_SESSION['error'] = 'Fayl seçilməyib.';
            header('Location: /');
            exit;
        }

        $file     = $_FILES['file'];
        $original = $file['name'];                             
        $tmpPath  = $file['tmp_name'];                         
        $size     = $file['size'];                              
        $ext      = strtolower(pathinfo($original, PATHINFO_EXTENSION)); 
        $mime     = mime_content_type($tmpPath);                    

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Yükləmə zamanı xəta baş verdi.';
            header('Location: /');
            exit;
        }

        if ($size > $this->uploadConfig['max_size']) {
            $_SESSION['error'] = 'Fayl 5MB-dan böyükdür.';
            header('Location: /');
            exit;
        }

        if (!in_array($ext, $this->uploadConfig['allowed_types'])) {
            $_SESSION['error'] = 'Bu fayl tipi icazəli deyil.';
            header('Location: /');
            exit;
        }

        $allowedMimes = [
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'pdf'  => 'application/pdf',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'zip'  => 'application/zip',
        ];
        if ($allowedMimes[$ext] !== $mime) {
            $_SESSION['error'] = 'Faylın içi adı ilə uyğun deyil.';
            header('Location: /');
            exit;
        }

        $storageName = time() . '_' . substr(uniqid(), -8) . '.' . $ext;

        $destination = $this->uploadConfig['upload_dir'] . $storageName;
        if (!move_uploaded_file($tmpPath, $destination)) {
            $_SESSION['error'] = 'Fayl saxlanıla bilmədi.';
            header('Location: /');
            exit;
        }

        $this->fileModel->create([
            'original_name' => $original,
            'storage_name'  => $storageName,
            'file_type'     => $ext,
            'file_size'     => $size,
        ]);

        $_SESSION['success'] = 'Fayl uğurla yükləndi.';
        header('Location: /');
        exit;
    }

    public function download(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id === 0) {
            header('Location: /');
            exit;
        }

        $file = $this->fileModel->findById($id);
        if (!$file) {
            $_SESSION['error'] = 'Fayl tapılmadı.';
            header('Location: /');
            exit;
        }

        $path = $this->uploadConfig['upload_dir'] . $file['storage_name'];
        if (!file_exists($path)) {
            $_SESSION['error'] = 'Fayl serverdə tapılmadı.';
            header('Location: /');
            exit;
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file['original_name'] . '"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }

    public function delete(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id === 0) {
            header('Location: /');
            exit;
        }

        $file = $this->fileModel->findById($id);
        if (!$file) {
            $_SESSION['error'] = 'Fayl tapılmadı.';
            header('Location: /');
            exit;
        }

        $path = $this->uploadConfig['upload_dir'] . $file['storage_name'];
        if (file_exists($path)) {
            unlink($path);
        }

        $this->fileModel->delete($id);

        $_SESSION['success'] = 'Fayl silindi.';
        header('Location: /');
        exit;
    }
}