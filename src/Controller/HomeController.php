<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProduitRepository $produitRepository): Response
{
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'produits' => $produitRepository->findAll(),
        ]);
    }
}
    }
