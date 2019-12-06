<?php

namespace App\EventSubscriber\Entity;

use App\Service\FileService;
use App\Service\StringService;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class AbstractSubscriber implements EventSubscriber
{
    protected $stringService;
    protected $fileService;
    protected $preUpdate = false;

    public function __construct(StringService $stringService, FileService $fileService)
    {
        $this->stringService = $stringService;
        $this->fileService = $fileService;
    }

    function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::postLoad,
            Events::preUpdate,
        ];
    }

    /**
     * @param $entity
     */
    protected function fileTransfer($entity,$dir): void
    {
        if ($entity->getImage() instanceof UploadedFile) {
            $this->fileService->upload($entity->getImage(), $dir);
            $entity->setImage($this->fileService->getFileName());

            if ($this->preUpdate) {
                //Supprimer l'ancienne image
                if (file_exists("$dir{$entity->prevImage}")) {
                    $this->fileService->remove($dir, $entity->prevImage);
                }
            }

        } else {
            if ($this->preUpdate) {
                $entity->setImage($entity->prevImage);
            }
        }
    }

}