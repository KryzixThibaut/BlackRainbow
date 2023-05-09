<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Articles;
use App\Entity\Panier;
use App\Entity\Ajouter;
use App\Form\AjoutArticleType;
use App\Form\ModifArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PanierController extends AbstractController
{
    #[Route('/profile-ajout-panier', name: 'ajout-panier')]
    public function ajoutPanier(Request $request, EntityManagerInterface $entityManagerInterface): Response
{
   $id = $request->get('id');
   $page = $request->headers->get('referer');
   if ($this->getUser()->getPanier() == null) {
       $panier = new Panier();
       $this->getUser()->setPanier($panier);
   }

   // Vérifie si le produit est déjà présent dans le panier
   $ajouter = $entityManagerInterface->getRepository(Ajouter::class)
       ->findOneBy(['panier' => $this->getUser()->getPanier(), 'produit' => $id]);

   if ($ajouter) {
       // Si le produit est déjà présent, on ajoute 1 à la quantité existante
       $ajouter->setQte($ajouter->getQte() + 1);
   } else {
       // Si le produit n'est pas encore dans le panier, on l'ajoute avec une quantité de 1
       $ajouter = new Ajouter();
       $ajouter->setQte(1);
       $produit = $entityManagerInterface->getRepository(Articles::class)->find($id);
       if ($produit != null) {
           $ajouter->setProduit($produit);
           $ajouter->setPanier($this->getUser()->getPanier());
           $entityManagerInterface->persist($ajouter);
       }
   }

   $entityManagerInterface->flush();
   return new RedirectResponse($page);
}



#[Route('/profile-retirer-panier', name: 'retirer-panier')]
    public function retraitPanier(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $id = $request->get('id');
        $page = $request->headers->get('referer');
        $ajouter = $entityManagerInterface->getRepository(Ajouter::class)->find($id);
        
        if ($ajouter != null) {
            $qte = $ajouter->getQte() - 1;
            if ($qte <= 0) {
                $entityManagerInterface->remove($ajouter);
            } else {
                $ajouter->setQte($qte);
                $entityManagerInterface->persist($ajouter);
            }
            $entityManagerInterface->flush();
        }

        return new RedirectResponse($page);
    }

}
