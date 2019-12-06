<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\File\UploadedFile;


class FileService
{
    private $stringService;
    private $fileName;

    public function __construct(StringService $stringService)
    {
        $this->stringService = $stringService;
    }

    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    public function upload(UploadedFile $file, string $dir): void
    {
        $this->fileName = "{$this->stringService->getToken()}.{$file->guessClientExtension()}";
        $file->move($dir, $this->fileName);
    }

    public function remove(string $dir, string $fileName): void
    {
        $destination = "$dir/$fileName";
        unlink($destination);
    }
}