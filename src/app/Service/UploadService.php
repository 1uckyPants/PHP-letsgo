<?php

declare(strict_types=1);

namespace App\Service;

class UploadService
{

    public ?string $tmpName = null;
    public ?string $name = null;

    public function upload(): string
    {
        foreach ($_FILES as $field => $file) {
            $this->tmpName = $file['tmp_name'];
        }

        foreach ($_FILES as $field => $file) {
            $this->name = $file['name'];
        }

        $filePath = STORAGE_PATH . '/' . $this->name;

        move_uploaded_file($this->tmpName, $filePath);

        return $this->name;
    }

    public function filePath()
    {
        return STORAGE_PATH . '/' . $this->name;
    }
}