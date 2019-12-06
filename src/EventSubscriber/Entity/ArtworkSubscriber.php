<?php

namespace App\EventSubscriber\Entity;

use App\Entity\Artwork;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class ArtworkSubscriber extends AbstractSubscriber
{

    public function prePersist(LifecycleEventArgs $args): void
    {
        if (!$this->isArtworkEntity($args)) {
            return;
        }
        $entity = $this->isArtworkEntity($args);
        $name = $entity->getName();
        $slug = $this->stringService->getSlug($name);
        $entity->setSlug($slug);
        //transfert de l'image
        $this->fileTransfer($entity,'img/artwork');
    }

    public function isArtworkEntity(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Artwork) {
            return;
        } else {
            return $entity;
        }
    }

    public function postLoad(LifecycleEventArgs $args): void
    {
        if (!$this->isArtworkEntity($args)) {
            return;
        }
        $entity = $this->isArtworkEntity($args);
        $entity->prevImage = $entity->getImage();
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        if (!$this->isArtworkEntity($args)) {
            return;
        }
        $entity = $this->isArtworkEntity($args);
        $this->preUpdate = true;
        $this->fileTransfer($entity, 'img/artwork');
        $this->preUpdate = false;

    }

}