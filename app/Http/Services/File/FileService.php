<?php

namespace App\Http\Services\File;

class FileService extends FileToolsService
{

    public function moveToPublic($file)
    {
        //set File
        $this->setFile($file);
        //execute provider
        $this->provider();
        //save File
        $result = $file->move(public_path($this->getFinalFileDirectory()), $this->getFinalFileName());
        return $result ? $this->getFileAddress() : false;
    }

    public function moveToStorage($file)
    {
        //set File
        $this->setFile($file);
        //execute provider
        $this->provider();
        //save File
        $result = $file->move(storage_path($this->getFinalFileDirectory()), $this->getFinalFileName());
        return $result ? $this->getFileAddress() : false;
    }

    public function deleteFile($filePath, $storage = false): bool
    {
        if ($storage === true) unlink(storage_path($filePath));

        if (file_exists($filePath)) {
            unlink($filePath);
            return true;
        }
        return false;
    }
}
