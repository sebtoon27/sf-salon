<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitForm;
use App\Service\FileUploader;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/produit')]
final class ProduitController extends AbstractController
{
    // Affiche la liste de tous les produits
    #[Route(name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        // Récupère tous les produits et les passe à la vue
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    // Crée un nouveau produit (formulaire + upload jusqu'à 3 images)
    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader ): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitForm::class, $produit);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de la première image
            $picture1 = $form->get('image1')->getData();
            if ($picture1) {
                $filename1 = $fileUploader->upload($picture1);
                $produit->setImage1($filename1);
            }

            // Gestion de la deuxième image
            $picture2 = $form->get('image2')->getData();
            if ($picture2) {
                $filename2 = $fileUploader->upload($picture2);
                $produit->setImage2($filename2);
            }

            // Gestion de la troisième image
            $picture3 = $form->get('image3')->getData();
            if ($picture3) {
                $filename3 = $fileUploader->upload($picture3);
                $produit->setImage3($filename3);
            }

            // Enregistre le produit en base de données
            $entityManager->persist($produit);
            $entityManager->flush();

            // Redirige vers la liste des produits
            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        // Affiche le formulaire de création
        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    // Affiche le détail d'un produit
    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    // Modifie un produit existant (et gère le remplacement des images)
    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager, Filesystem $filsystem, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(ProduitForm::class, $produit);
        $form->handleRequest($request);

        // On récupère les anciens noms de fichiers AVANT modification
        $oldFilename1 = $produit->getImage1();
        $oldFilename2 = $produit->getImage2();
        $oldFilename3 = $produit->getImage3();

        if ($form->isSubmitted() && $form->isValid()) {
            // Image 1 : upload et suppression de l'ancienne si besoin
            $picture1 = $form->get('image1')->getData();
            if ($picture1) {
                $filename1 = $fileUploader->upload($picture1);
                if ($oldFilename1) {
                    $oldFilepath1 = $this->getParameter('image_directory') . '/' . $oldFilename1;
                    if ($filsystem->exists($oldFilepath1)) {
                        $filsystem->remove($oldFilepath1);
                    }
                }
                $produit->setImage1($filename1);
            }

            // Image 2 : upload et suppression de l'ancienne si besoin
            $picture2 = $form->get('image2')->getData();
            if ($picture2) {
                $filename2 = $fileUploader->upload($picture2);
                if ($oldFilename2) {
                    $oldFilepath2 = $this->getParameter('image_directory') . '/' . $oldFilename2;
                    if ($filsystem->exists($oldFilepath2)) {
                        $filsystem->remove($oldFilepath2);
                    }
                }
                $produit->setImage2($filename2);
            }

            // Image 3 : upload et suppression de l'ancienne si besoin
            $picture3 = $form->get('image3')->getData();
            if ($picture3) {
                $filename3 = $fileUploader->upload($picture3);
                if ($oldFilename3) {
                    $oldFilepath3 = $this->getParameter('image_directory') . '/' . $oldFilename3;
                    if ($filsystem->exists($oldFilepath3)) {
                        $filsystem->remove($oldFilepath3);
                    }
                }
                $produit->setImage3($filename3);
            }

            // Enregistre les modifications
            $entityManager->flush();

            // Redirige vers la liste des produits
            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        // Affiche le formulaire d'édition
        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    // Supprime un produit (et supprime les images associées du disque)
    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager, Filesystem $filsystem): Response
    {
        // Vérifie le token CSRF avant suppression
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->getPayload()->getString('_token'))) {
            // Suppression de la première image si elle existe
            $filename1 = $produit->getImage1();
            if ($filename1) {
                $filepath1 = $this->getParameter('image_directory') . '/' . $filename1;
                if ($filsystem->exists($filepath1)) {
                    $filsystem->remove($filepath1);
                }
            }

            // Suppression de la deuxième image si elle existe
            $filename2 = $produit->getImage2();
            if ($filename2) {
                $filepath2 = $this->getParameter('image_directory') . '/' . $filename2;
                if ($filsystem->exists($filepath2)) {
                    $filsystem->remove($filepath2);
                }
            }

            // Suppression de la troisième image si elle existe
            $filename3 = $produit->getImage3();
            if ($filename3) {
                $filepath3 = $this->getParameter('image_directory') . '/' . $filename3;
                if ($filsystem->exists($filepath3)) {
                    $filsystem->remove($filepath3);
                }
            }

            // Supprime le produit de la base de données
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        // Redirige vers la liste des produits
        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }

}