<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    // Affiche le formulaire de connexion et gère l'affichage des erreurs éventuelles
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Récupère l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

        // Récupère le dernier nom d'utilisateur saisi
        $lastUsername = $authenticationUtils->getLastUsername();

        // Affiche la vue du formulaire de connexion avec les infos récupérées
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    // Déconnexion de l'utilisateur
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Cette méthode peut rester vide : elle est interceptée par le firewall de Symfony
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}