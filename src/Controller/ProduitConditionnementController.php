<?php

namespace App\Controller;

use DateTime;
use App\Services\HandleImage;
use App\Repository\ProductRepository;
use App\Repository\RecetteRepository;
use App\Entity\ProduitConditionnement;
use App\Form\ProduitConditionnementType;
use Doctrine\ORM\EntityManagerInterface;
use App\Search\SearchProductConditionnement;
use App\Repository\ConditionnementRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\SearchProductConditionnementType;
use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ParameterBag;
use App\Repository\ProduitConditionnementRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Services\ImageFinder;

#[Route('admin/produit/conditionnement')]
class ProduitConditionnementController extends AbstractController
{
    // affiche les produits en stock  (un produit est la table relation entre produit et conditionnements)
    #[Route('admin/produit/conditionnement', name: 'produit_conditionnement_index', methods: ['GET'])]
    public function index(Request $request,ProduitConditionnementRepository $produitConditionnementRepository): Response
    {

        $search = new SearchProductConditionnement();

        $form = $this->createForm(SearchProductConditionnementType::class,$search);

        $form->handleRequest($request);

        $produitConditionnement = $produitConditionnementRepository->findByFilter($search);  //filtre 

        return $this->render('admin/produit_conditionnement/index.html.twig', [
            'produit_conditionnements' => $produitConditionnement,'form' => $form->createView()
           
        ]);
    }

    // affiche le formulaire d'un nouveau produit conditionnement
    #[Route('/new', name: 'produit_conditionnement_new', methods: ['GET', 'POST'])]
    public function new( HandleImage $handleImage,Request $request, EntityManagerInterface $entityManager,ProductRepository $productRepository,ConditionnementRepository $conditionnementRepository): Response
    {
        $finder = new ImageFinder();
        $filesTab = $finder->GetUploadDirectory();   // charge les fichiers du r??pertoire uploads
 
        $produitConditionnement = new ProduitConditionnement();
        // charge les produits pour le combo produits et aussi les conditionnements pour le combo conditionnement
        $form = $this->createForm(ProduitConditionnementType::class, $produitConditionnement,['product' => $productRepository->findAll(),'conditionnement' => $conditionnementRepository->findAll() ] );
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
 

            $entityManager->persist($produitConditionnement);
            $entityManager->flush();

            return $this->redirectToRoute('produit_conditionnement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/produit_conditionnement/new.html.twig', [
            'produit_conditionnement' => $produitConditionnement,'file'=>$filesTab,
            'form' => $form,
        ]);
    }

    // affiche le detail d'un produit conditionnement
    #[Route('/{id}', name: 'produit_conditionnement_show', methods: ['GET'])]
    public function show(ProduitConditionnement $produitConditionnement): Response
    {
        return $this->render('admin/produit_conditionnement/show.html.twig', [
            'produit_conditionnement' => $produitConditionnement,
        ]);
    }
    // affiche le formulaire d'edition d'un produit conditionnement
    #[Route('/{id}/edit', name: 'produit_conditionnement_edit', methods: ['GET', 'POST'])]
    public function edit(HandleImage $handleImage,Request $request, ProduitConditionnement $produitConditionnement, EntityManagerInterface $entityManager,ProductRepository $productRepository,ConditionnementRepository $conditionnementRepository): Response
    {

     $finder = new ImageFinder();   
     $filesTab = $finder->GetUploadDirectory();   // charge les fichiers du r??pertoire uploads
     $oldImage = $produitConditionnement->getImagePath();
         
         // charge les produits pour le combo produits et aussi les conditionnements pour le combo conditionnement
         $form = $this->createForm(ProduitConditionnementType::class, $produitConditionnement,['product' => $productRepository->findAll(),'conditionnement' => $conditionnementRepository->findAll() ]);
         $form->handleRequest($request);

        //  dd($filesTab);
          
         if ($form->isSubmitted() && $form->isValid()) {

            //  //Recuperer le fichier 
            // /** @var UploadedFile $file */
            $file = $form->get('image_path')->getData();
             
            //Verifier que il y a bien un fichier
            if($file)
            {
                // charge l'image s??lectionn??e
                $produitConditionnement->setImagepath($file);  
            }
            else
            {
                // Charge l'ancienne
                $produitConditionnement->setImagepath($oldImage);
            }

            $entityManager->flush();

            return $this->redirectToRoute('produit_conditionnement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/produit_conditionnement/edit.html.twig', [
            'produit_conditionnement' => $produitConditionnement,'file'=>$filesTab,
            'form' => $form,
        ]);
    }
    // supprime le produit conditionnement
    #[Route('/{id}', name: 'produit_conditionnement_delete', methods: ['POST'])]
    public function delete(int $id,Request $request,RecetteRepository $recetteRepository,ProduitConditionnementRepository $productConditionnementRepository, ProduitConditionnement $produitConditionnement, EntityManagerInterface $entityManager): Response
    {

      
        if ($this->isCsrfTokenValid('delete'.$produitConditionnement->getId(), $request->request->get('_token'))) {

           

            $entityManager->remove($produitConditionnement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('produit_conditionnement_index', [], Response::HTTP_SEE_OTHER);
    }
}
