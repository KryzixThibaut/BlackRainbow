<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Articles;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ArticlesRepository;

class ListeProduitsController extends AbstractController
{
    #[Route('/hommes', name: 'hommes')]
    public function hommes(ArticlesRepository $produitsRepository): Response
    {

        $em = $this->getDoctrine()->getManager();
$query = $em->createQuery(
    'SELECT p
    FROM App\Entity\Articles p
    WHERE p.tag LIKE :motCle'
)->setParameter('motCle', '%homme%');
$resultats = $query->getResult();


        return $this->render('hommes/hommes.html.twig', [
            'articles' => $resultats,
            
        ]);
    }
}