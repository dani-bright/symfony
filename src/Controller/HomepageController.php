<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Form\Model\ContactModel;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomepageController extends AbstractController
{

    /**
     * @Route(
     *     "/",
     *     name="homepage.index",
     * )
     */
    public function index(): Response
    {
        return $this->render("homepage/index.html.twig");
    }
}