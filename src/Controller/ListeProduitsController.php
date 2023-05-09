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



    #[Route('/femmes', name: 'femmes')]
    public function femmes(ArticlesRepository $produitsRepository): Response
    {

        $em = $this->getDoctrine()->getManager();
$query = $em->createQuery(
    'SELECT p
    FROM App\Entity\Articles p
    WHERE p.tag LIKE :motCle'
)->setParameter('motCle', '%femme%');
$resultats = $query->getResult();


        return $this->render('hommes/femmes.html.twig', [
            'articles' => $resultats,
            
        ]);
    }
    
    #[Route('/enfants', name: 'enfants')]
    public function enfants(ArticlesRepository $produitsRepository): Response
    {

        $em = $this->getDoctrine()->getManager();
$query = $em->createQuery(
    'SELECT p
    FROM App\Entity\Articles p
    WHERE p.tag LIKE :motCle'
)->setParameter('motCle', '%enfant%');
$resultats = $query->getResult();


        return $this->render('hommes/enfants.html.twig', [
            'articles' => $resultats,
            
        ]);
    }

    #[Route('/nouveautes', name: 'nouveautes')]
    public function nouveautes(ArticlesRepository $produitsRepository): Response
    {

        $em = $this->getDoctrine()->getManager();
$query = $em->createQuery(
    'SELECT p
    FROM App\Entity\Articles p
    WHERE p.tag LIKE :motCle'
)->setParameter('motCle', '%nouveau%');
$resultats = $query->getResult();


        return $this->render('hommes/nouveautes.html.twig', [
            'articles' => $resultats,
            
        ]);
    }


    #[Route('/promos', name: 'promos')]
    public function promos(ArticlesRepository $produitsRepository): Response
    {

        $em = $this->getDoctrine()->getManager();
$query = $em->createQuery(
    'SELECT p
    FROM App\Entity\Articles p
    WHERE p.tag LIKE :motCle'
)->setParameter('motCle', '%promo%');
$resultats = $query->getResult();


        return $this->render('hommes/promos.html.twig', [
            'articles' => $resultats,
            
        ]);
    }

    #[Route('/adidas', name: 'adidas')]
    public function adidas(ArticlesRepository $produitsRepository): Response
    {

        $em = $this->getDoctrine()->getManager();
$query = $em->createQuery(
    'SELECT p
    FROM App\Entity\Articles p
    WHERE p.tag LIKE :motCle'
)->setParameter('motCle', '%adidas%');
$resultats = $query->getResult();


        return $this->render('hommes/adidas.html.twig', [
            'articles' => $resultats,
            
        ]);
    }

    #[Route('/nike', name: 'nike')]
    public function nike(ArticlesRepository $produitsRepository): Response
    {

        $em = $this->getDoctrine()->getManager();
$query = $em->createQuery(
    'SELECT p
    FROM App\Entity\Articles p
    WHERE p.tag LIKE :motCle'
)->setParameter('motCle', '%nike%');
$resultats = $query->getResult();


        return $this->render('hommes/nike.html.twig', [
            'articles' => $resultats,
            
        ]);
    }



    #[Route('/puma', name: 'puma')]
    public function puma(ArticlesRepository $produitsRepository): Response
    {

        $em = $this->getDoctrine()->getManager();
$query = $em->createQuery(
    'SELECT p
    FROM App\Entity\Articles p
    WHERE p.tag LIKE :motCle'
)->setParameter('motCle', '%puma%');
$resultats = $query->getResult();


        return $this->render('hommes/puma.html.twig', [
            'articles' => $resultats,
            
        ]);
    }


    #[Route('/reebok', name: 'reebok')]
    public function reebok(ArticlesRepository $produitsRepository): Response
    {

        $em = $this->getDoctrine()->getManager();
$query = $em->createQuery(
    'SELECT p
    FROM App\Entity\Articles p
    WHERE p.tag LIKE :motCle'
)->setParameter('motCle', '%reebok%');
$resultats = $query->getResult();


        return $this->render('hommes/reebok.html.twig', [
            'articles' => $resultats,
            
        ]);
    }



    #[Route('/thenorthface', name: 'thenorthface')]
    public function thenorthface(ArticlesRepository $produitsRepository): Response
    {

        $em = $this->getDoctrine()->getManager();
$query = $em->createQuery(
    'SELECT p
    FROM App\Entity\Articles p
    WHERE p.tag LIKE :motCle'
)->setParameter('motCle', '%thenorthface%');
$resultats = $query->getResult();


        return $this->render('hommes/thenorthface.html.twig', [
            'articles' => $resultats,
            
        ]);
    }
}