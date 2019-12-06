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

class ExpositionController extends AbstractController
{

    /**
     * @Route(
     *     "/expositions",
     *     name="expo.index",
     * )
     */
    public function index(ExpositionRepository $expositionRepository): Response
    {

        $expos = $expositionRepository->findAll();


        return $this->render("expositions/index.html.twig", [
            "expos" => $expos,
        ]);
    }

    /**
     * @param ArtworkRepository $expoRepository
     * @return Response
     * @Route(
     *     "/expo/{slug}",
     *     name="expo.details",
     * )
     */
    public function details(ExpositionRepository $expoRepository, string $slug): Response
    {
        $result = $expoRepository->findOneBy([
            'slug' => $slug
        ]);

        return $this->render("expositions/details.html.twig", [
            "expo" => $result,
        ]);
    }

}