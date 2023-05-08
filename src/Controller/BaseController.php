<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Articles;
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



class BaseController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(): Response
    {
        return $this->render('base/index.html.twig', [
          
        ]);
    }

    #[Route('/hommes', name: 'hommes')]
    public function hommes(): Response
    {
        return $this->render('hommes/hommes.html.twig', [
            
        ]);
    }

    #[Route('/touthomme', name: 'touthomme')]
    public function touthomme(): Response
    {
        return $this->render('hommes/touthomme.html.twig', [
            
        ]);
    }



    #[Route('/private-ajout-produit', name: 'ajout-produit')]
        /**
         * @Route("/articles/new", name="articles_new")
         */
        public function new(Request $request, SluggerInterface $slugger): Response
        {
            $article = new Articles();
            $form = $this->createForm(AjoutArticleType::class, $article);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $imageFile = $form->get('image')->getData();
    
                // Vérifie si un fichier a été uploadé
                if ($imageFile) {
                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
    
                    // Déplace le fichier dans le répertoire où les images sont stockées
                    try {
                        $imageFile->move(
                            $this->getParameter('file_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // Gère les erreurs éventuelles lors du téléchargement du fichier
                        throw new \Exception("Une erreur s'est produite lors du téléchargement de l'image");
                    }
    
                    // Met à jour le champ image pour stocker le nom du fichier enregistré dans la base de données
                    $article->setImage($newFilename);
                }
    
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($article);
                $entityManager->flush();

                $this->addFlash('notice', 'Article Ajouté');
                return $this->redirectToRoute('ajout-produit');

            }


        return $this->render('base/ajout-produit.html.twig', [
            'form'=> $form->createView(),

            
        ]);
    }




    #[Route('base/private-modif-produit', name: 'modif-produit')]
public function modifproduit(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManagerInterface): Response
{
    $repoProduit = $entityManagerInterface->getRepository(Articles::class);
    $produits = $repoProduit->findAll();

    return $this->render('base/modif-produit.html.twig', [
        'articles' => $produits,
        'article' => new Articles() // ou un autre objet Article spécifique si nécessaire
    ]);
}



    #[Route('base/cliquemodif{id}', name: 'cliquemodif')]
public function editArticle(Request $request, $id, SluggerInterface $slugger, EntityManagerInterface $entityManager)
{
    $article = $this->getDoctrine()->getRepository(Articles::class)->find($id);

    if (!$article) {
        throw $this->createNotFoundException('L\'article avec l\'ID ' . $id . ' n\'existe pas.');
    }

    $form = $this->createFormBuilder($article)
        ->add('designation', TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('description', TextareaType::class, array('required' => false, 'attr' => array('class' => 'form-control')))
        ->add('prix', NumberType::class, array('attr' => array('class' => 'form-control')))
        ->add('tag', TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('image', FileType::class, array(
            'attr' => array('class' => 'form-control'),
            'required' => false,
            'empty_data' => $article->getImage(),
            'data_class' => null
        ))
        ->add('ajouter', SubmitType::class, array('label' => 'Modifier', 'attr' => array('class' => 'btn btn-primary mt-3')))
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        $oldImage = $article->getImage();

        if ($form->get('image')->getData() instanceof UploadedFile) {
            $image = $form->get('image')->getData();
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $imageName = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
            try {
                $image->move(
                    $this->getParameter('file_directory'),
                    $imageName
                );
                $article->setImage($imageName);

                // Supprimer l'ancienne image s'il en existe une
                if ($oldImage) {
                    $oldImagePath = $this->getParameter('file_directory').'/'.$oldImage;
                    if (file_exists($oldImagePath)) {
                        remove($oldImage);
                    }
                }
            } catch (FileException $e) {
                $form->get('image')->addError(new FormError('Une erreur s\'est produite lors du téléchargement de l\'image.'));
                // handle exception if something happens during file upload
            }
        } elseif ($form->get('image')->getData() === null) {
            $article->setImage(null);

            // Supprimer l'ancienne image s'il en existe une
            if ($oldImage) {
                $oldImagePath = $this->getParameter('file_directory').'/'.$oldImage;
                if (file_exists($oldImagePath)) {
                    remove($oldImage);
                }
            }
        }

        $entityManager->flush();

        return $this->redirectToRoute('modif-produit');
    }

    // initialise la valeur de l'image à null si elle n'existe pas
    if (!$article->getImage()) {
        $form->get('image')->setData(null);
    }

    return $this->render('base/cliquemodif.html.twig', [
        'form' => $form->createView()
    ]);
}


#[Route('base/suppmodif{id}', name: 'suppmodif')]
public function affichersuppProduit($id): Response
    {
        $produit = $this->getDoctrine()->getRepository(Articles::class)->find($id);

        if (!$produit) {
            throw $this->createNotFoundException('Le produit n\'existe pas.');
        }

        return $this->render('base/suppmodif.html.twig', [
            'articles' => $produit,
        ]);
    }

#[Route('base/supprimer-produit{id}', name: 'supprimer-produit')]
public function deleteProduct(Request $request, int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $product = $entityManager->getRepository(Articles::class)->find($id);

    if (!$product) {
        throw $this->createNotFoundException('Produit non trouvé avec id ' . $id);
    }

    $entityManager->remove($product);
    $entityManager->flush();

    return $this->redirectToRoute('modif-produit');
}
}
