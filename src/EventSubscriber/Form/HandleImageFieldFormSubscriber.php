<?php

namespace App\EventSubscriber\Form;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class HandleImageFieldFormSubscriber implements EventSubscriberInterface
{
    public function postSetData(FormEvent $event)
    {
        $model = $event->getData();
        $form = $event->getForm();
        $entity = $form->getData();

        //modifier les contraintes
        $constraints = $entity->getId() ? [] : [
            new NotBlank([
                'message' => 'ajouter une image est fortement conseillé'
            ]),
            new Image([
                'mimeTypesMessage' => "Vous devez sélectionner une image",
                'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp']
            ])
        ];
        $form
            ->add('image', FileType::class, [
                'data_class' => null, //eviter erreur lors de la modif d'une entité
                'constraints' => $constraints
            ]);
        //dd($entity);
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::POST_SET_DATA => 'postSetData',
        ];
    }
}
