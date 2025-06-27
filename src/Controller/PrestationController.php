<?php

namespace App\Controller;

use App\Entity\Prestation;
use App\Form\PrestationForm;
use App\Service\FileUploader;
use App\Repository\PrestationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/prestation')]
final class PrestationController extends AbstractController
{
    // Affiche la liste de toutes les prestations
    #[Route(name: 'app_prestation_index', methods: ['GET'])]
    public function index(PrestationRepository $prestationRepository): Response
    {
        return $this->render('prestation/index.html.twig', [
            'prestations' => $prestationRepository->findAll(),
        ]);
    }

    // Crée une nouvelle prestation (formulaire + upload image)
    #[Route('/new', name: 'app_prestation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $prestation = new Prestation();
        $form = $this->createForm(PrestationForm::class, $prestation);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupère l'image uploadée
            $picture = $form->get('image')->getData();
            
            if ($picture) {
                // Upload l'image et enregistre le nom du fichier
                $filename = $fileUploader->upload($picture);
                $prestation->setImage($filename);
            }

            // Sauvegarde la prestation en base de données
            $entityManager->persist($prestation);
            $entityManager->flush();

            // Redirige vers la liste des prestations
            return $this->redirectToRoute('app_prestation_index', [], Response::HTTP_SEE_OTHER);
        }

        // Affiche le formulaire de création
        return $this->render('prestation/new.html.twig', [
            'prestation' => $prestation,
            'form' => $form,
        ]);
    }   

    // Affiche le détail d'une prestation
    #[Route('/{id}', name: 'app_prestation_show', methods: ['GET'])]
    public function show(Prestation $prestation): Response
    {
        return $this->render('prestation/show.html.twig', [
            'prestation' => $prestation,
        ]);
    }

    // Modifie une prestation existante (et gère le remplacement de l'image)
    #[Route('/{id}/edit', name: 'app_prestation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Prestation $prestation, EntityManagerInterface $entityManager, Filesystem $filsystem, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(PrestationForm::class, $prestation);
        $form->handleRequest($request);

        // Sauvegarde l'ancien nom de fichier pour suppression si besoin
        $oldFilename = $prestation->getImage();

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupère la nouvelle image si elle existe
            $picture = $form->get('image')->getData();
            if ($picture) {
                // Upload la nouvelle image
                $filename = $fileUploader->upload($picture);
                // Supprime l'ancienne image du disque si elle existe
                if ($oldFilename) {
                    $oldFilepath = $this->getParameter('image_directory') . '/' . $oldFilename;
                    if ($filsystem->exists($oldFilepath)) {
                        $filsystem->remove($oldFilepath);
                    }
                }
                // Met à jour le nom de l'image dans l'entité
                $prestation->setImage($filename);
            }

            // Sauvegarde les modifications
            $entityManager->flush();

            // Redirige vers la liste des prestations
            return $this->redirectToRoute('app_prestation_index', [], Response::HTTP_SEE_OTHER);
        }

        // Affiche le formulaire d'édition
        return $this->render('prestation/edit.html.twig', [
            'prestation' => $prestation,
            'form' => $form,
        ]);
    }

    // Supprime une prestation (et supprime l'image associée du disque)
    #[Route('/{id}', name: 'app_prestation_delete', methods: ['POST'])]
    public function delete(Request $request, Prestation $prestation, EntityManagerInterface $entityManager, Filesystem $filsystem): Response
    {
        // Vérifie le token CSRF avant suppression
        if ($this->isCsrfTokenValid('delete'.$prestation->getId(), $request->getPayload()->getString('_token'))) {
            // Récupère le nom de l'image
            $filename = $prestation->getImage();
            // Supprime l'image du disque si elle existe
            if ($filename) {
                $filepath = $this->getParameter('image_directory') . '/' . $filename;
                if ($filsystem->exists($filepath)) {
                    $filsystem->remove($filepath);
                }
            }
            // Supprime la prestation de la base de données
            $entityManager->remove($prestation);
            $entityManager->flush();
        }

        // Redirige vers la liste des prestations
        return $this->redirectToRoute('app_prestation_index', [], Response::HTTP_SEE_OTHER);
    }
}