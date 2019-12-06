<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Form\Model\ContactModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ContactController extends AbstractController
{

    /**
     * @Route(
     *     "/contact/",
     *     name="contact.form",
     * )
     */
    public function form(Request $request, \Swift_Mailer $mailer, Environment $twig): Response
    {
        $type = ContactType::class;
        $model = new ContactModel();

        $form = $this->createForm($type, $model);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = new \Swift_Message();
            $message
                ->setFrom('dad-dad173@inbox.mailtrap.io')
                ->setSubject('contact')
                ->setContentType('text/html')
                ->setBody(
                    $twig->render('emailing/contact.html.twig', [
                        'data'=> $form-> getData()
                    ])
                )
                ->addPart(
                    $twig->render('emailing/contact.text.twig', [
                        'data'=> $form-> getData()
                    ]), 'text/plain'
                )
            ;

            $mailer->send($message);

            $this->addFlash('notice', 'un email vous à été envoyé');

            return $this->redirectToRoute('contact.form');
        }

        return $this->render("contact/form.html.twig", [
            'form' => $form->createView()
        ]);
    }
}