<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(): Response
    {
        return $this->render('base/index.html.twig', [
          
        ]);
    }

    #[Route('/contact', name: 'contact')] // étape 1
    public function contact(): Response // étape 2
    {
        return $this->render('base/contact.html.twig', [ // étape 3
            
        ]);
    }

    #[Route('/produits', name: 'produits')] // étape 1
    public function produits(): Response // étape 2
    {
        return $this->render('base/produits.html.twig', [ // étape 3
            
        ]);
    }

    #[Route('/conditions', name: 'conditions')] // étape 1
    public function conditions(): Response // étape 2
    {
        return $this->render('base/conditions.html.twig', [ // étape 3
            
        ]);
    }
}
