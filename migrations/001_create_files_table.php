<?php 
class CreateFilesTable {
    public function up(PDO $pdo) {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS files (
                id            INT          AUTO_INCREMENT PRIMARY KEY,
                original_name VARCHAR(255) NOT NULL,
                storage_name  VARCHAR(255) NOT NULL,
                file_type     VARCHAR(50)  NOT NULL,
                file_size     INT          NOT NULL,
                uploaded_at   DATETIME     DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function down(PDO $pdo) {
        $pdo->exec("DROP TABLE IF EXISTS files");
    }
}