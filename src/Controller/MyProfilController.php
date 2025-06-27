<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\ProfilUserType;
use App\Service\FileUploader;
use App\Repository\UserRepository;
use App\Controller\MyProfilController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class MyProfilController extends AbstractController
{
    #[Route('/my/profil', name: 'app_my_profil')]
    public function index(UserRepository $UserRepository): Response{


 $user = $UserRepository->findOneBy([
            'id' => $this->getUser(),
        
        ]);
    
        return $this->render('my_profil/index.html.twig', [
            'user' => $UserRepository->findAll(),
        ]);
    
}



#[Route('/{id}/edit', name: 'app_my_profil_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserRepository $UserRepository, Filesystem $filsystem, FileUploader $fileUploader): Response
{
    $form = $this->createForm(ProfilUserType::class, $user);
    $form->handleRequest($request);
    $oldAvatar = $user->getAvatar();

    if ($form->isSubmitted() && $form->isValid()) {
        // Avatar
        $avatar = $form->get('avatar')->getData();
        if ($avatar) {
            $avatar = $fileUploader->upload($avatar);
            if ($oldAvatar) {
                $oldFilepath = $this->getParameter('image_directory') . '/' . $oldAvatar;
                if ($filsystem->exists($oldFilepath)) {
                    $filsystem->remove($oldFilepath);
                }
            }
            $user->setAvatar($avatar);
        }



        $entityManager->flush();

        return $this->redirectToRoute('app_my_profil', [], Response::HTTP_SEE_OTHER);
    }





        return $this->render('my_profil/editProfil.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
