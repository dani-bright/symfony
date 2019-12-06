<?php

namespace App\Controller\Admin;

use App\Entity\Exposition;
use App\Form\ExpositionType;
use App\Repository\ExpositionRepository;
use App\Service\FileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/expo")
 */
class ExpositionController extends AbstractController
{
    /**
     * @Route(
     *     "/",
     *     name="admin.expo.index",
     * )
     */
    public function index(ExpositionRepository $expositionRepository): Response
    {
        $results = $expositionRepository->findAll();
        return $this->render("admin/exposition/index.html.twig", [
            'expos' => $results
        ]);
    }

    /**
     * @Route(
     *     "/form",
     *     name="admin.expo.form",
     * )
     * @Route(
     *     "/form/update/{id}",
     *     name="admin.expo.form.update",
     * )
     */

    public function form(Request $request, EntityManagerInterface $entityManager, int $id = null, ExpositionRepository $expositionRepository): Response
    {
        $model = $id ? $expositionRepository->find($id) : new Exposition();
        $type = ExpositionType::class;
        $form = $this->createForm($type, $model);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $model->getId() ? $this->addFlash('notice', "l'oeuvre a bien été modifié")
                : $this->addFlash('notice', "l'oeuvre a bien été ajouté à la base de données");

            //inserer en base
            $model->getId() ? null : $entityManager->persist($form->getData());
            $entityManager->flush();

            return $this->redirectToRoute('admin.expo.index');
        }
        return $this->render("admin/exposition/form.html.twig", [
            'form' => $form->createView(),
            'id' => $id
        ]);
    }

    /**
     *     * @Route(
     *     "/remove/{id}",
     *     name="admin.expo.remove",
     * )
     */
    public function remove(ExpositionRepository $expositionRepository, EntityManagerInterface $entityManager, string $id, FileService $fileService)
    {
        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            $this->addFlash('notice_error', "vous n'avez pas les droit pour supprimer un produit");
            return $this->redirectToRoute('admin.expo.index');
        }
        $expo = $expositionRepository->find($id);
        $entityManager->remove($expo);
        $entityManager->flush();

        $this->addFlash('notice', 'le produit a bien été supprimé');
        if (file_exists("img/expo/{$expo->getImage()}")) {
            $fileService->remove('img/expo', $expo->getImage());
        }

        return $this->redirectToRoute('admin.expo.index');
    }
}