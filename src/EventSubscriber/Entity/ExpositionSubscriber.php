<?php

namespace App\EventSubscriber\Entity;

use App\Entity\Exposition;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class ExpositionSubscriber extends AbstractSubscriber
{

    public function prePersist(LifecycleEventArgs $args): void
    {
        if (!$this->isExpoEntity($args)) {
            return;
        }
        $entity = $this->isExpoEntity($args);
        $name = $entity->getTitle();
        $slug = $this->stringService->getSlug($name);
        $entity->setSlug($slug);
        //transfert de l'image
        $this->fileTransfer($entity, 'img/expo');
    }

    public function isExpoEntity(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof Exposition) {
            return;
        } else {
            return $entity;
        }
    }

    public function postLoad(LifecycleEventArgs $args): void
    {
        if (!$this->isExpoEntity($args)) {
            return;
        }

        $entity = $this->isExpoEntity($args);
        $entity->prevImage = $entity->getImage();
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        if (!$this->isExpoEntity($args)) {
            return;
        }
        $entity = $this->isExpoEntity($args);
        $this->preUpdate = true;
        $this->fileTransfer($entity, 'img/expo');
        $this->preUpdate = false;

    }


}