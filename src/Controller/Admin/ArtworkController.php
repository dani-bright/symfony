<?php

namespace App\Controller\Admin;

use App\Entity\Artwork;
use App\Form\ArtworkType;
use App\Repository\ArtworkRepository;
use App\Service\FileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/artworks")
 */
class ArtworkController extends AbstractController
{
    /**
     * @Route(
     *     "/",
     *     name="admin.artwork.index",
     * )
     */
    public function index(ArtworkRepository $artworkRepository): Response
    {
        $results = $artworkRepository->findAll();
        return $this->render("admin/artwork/index.html.twig", [
            'artworks' => $results
        ]);
    }

    /**
     * @Route(
     *     "/form",
     *     name="admin.artwork.form",
     * )
     * @Route(
     *     "/form/update/{id}",
     *     name="admin.artwork.form.update",
     * )
     */

    public function form(Request $request, EntityManagerInterface $entityManager, int $id = null, ArtworkRepository $artworkRepository): Response
    {
        $model = $id ? $artworkRepository->find($id) : new Artwork();
        $type = ArtworkType::class;
        $form = $this->createForm($type, $model);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $model->getId() ? $this->addFlash('notice', "l'oeuvre a bien été modifié")
                : $this->addFlash('notice', "l'oeuvre a bien été ajouté à la base de données");

            //inserer en base
            $model->getId() ? null : $entityManager->persist($form->getData());
            $entityManager->flush();

            return $this->redirectToRoute('admin.artwork.index');
        }
        return $this->render("admin/artwork/form.html.twig", [
            'form' => $form->createView(),
            'id' => $id
        ]);
    }

    /**
     *     * @Route(
     *     "/remove/{id}",
     *     name="admin.artwork.remove",
     * )
     */
    public function remove(ArtworkRepository $artworkRepository, EntityManagerInterface $entityManager, string $id, FileService $fileService)
    {
        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            $this->addFlash('notice_error', "vous n'avez pas les droit pour supprimer un produit");
            return $this->redirectToRoute('admin.artwork.index');
        }
        $artwork = $artworkRepository->find($id);
        $entityManager->remove($artwork);
        $entityManager->flush();

        $this->addFlash('notice', 'le produit a bien été supprimé');
        if (file_exists("img/artwork/{$artwork->getImage()}")) {
            $fileService->remove('img/artwork', $artwork->getImage());
        }

        return $this->redirectToRoute('admin.artwork.index');
    }
}