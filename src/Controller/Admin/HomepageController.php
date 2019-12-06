<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//préfix de toute les routes de la classes
/**
 * @Route("/admin")
 */
class HomepageController extends AbstractController
{
    /**
     * @Route(
     *     "/",
     *     name="admin.index",
     * )
     */
    public function index(): Response
    {
        return $this->render("admin/homepage/index.html.twig");
    }
}