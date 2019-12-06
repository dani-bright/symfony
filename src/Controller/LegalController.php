<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Form\Model\ContactModel;
use App\Repository\ArtworkRepository;
use App\Repository\ExpositionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class LegalController extends AbstractController
{

    /**
     * @Route(
     *     "/mentions-legal",
     *     name="legal.index",
     * )
     */
    public function index(): Response
    {
        return $this->render("legal/index.html.twig");
    }


}