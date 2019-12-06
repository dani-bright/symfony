<?php

namespace App\Controller;

use App\Repository\ArtworkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArtworkController extends AbstractController
{
    /**
     * @param ArtworkRepository $artworkRepository
     * @return Response
     * @Route(
     *     "/artwork/{slug}",
     *     name="artwork.details",
     * )
     */
    public function details(ArtworkRepository $artworkRepository, string $slug): Response
    {
        $result = $artworkRepository->findOneBy([
            'slug' => $slug
        ]);

        return $this->render("artwork/details.html.twig", [
            "artwork" => $result,
        ]);
    }

    /**
     * @Route(
     *     "/artworks/{page}",
     *     name="artwork.index",
     * )
     */
    public function index(ArtworkRepository $artworkRepository, int $page = 1): Response
    {

        $artworkToDisplay = $this->getParameter('artworkToDisplay');
        $artwork = $artworkRepository->findAll();
        $nbPage = 0;
        for ($i = 0; $i < count($artwork); $i++) {
            $nbPage += 1;
        }
        $offset = ($page * 5) - 5;
        $result = $artworkRepository->findBy([], array('name' => 'ASC'), $artworkToDisplay, $offset);


        return $this->render("artwork/index.html.twig", [
            "artworks" => $result,
            "nbPages" => $nbPage / $artworkToDisplay
        ]);
    }

    /**
     * @Route(
     *     "/artworksbycategory/{category}",
     *     name="artwork.category",
     * )
     */
    public function category(ArtworkRepository $artworkRepository, int $category = 1): Response
    {

        $result = $artworkRepository->findBy(array('category' => $category));

        return $this->render("artwork/category.html.twig", [
            "artworks" => $result,
        ]);
    }


}