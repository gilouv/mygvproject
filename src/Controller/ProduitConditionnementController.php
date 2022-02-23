<?php

namespace App\Controller;

 
use App\Services\HandleImage;
use App\Repository\ProductRepository;
use App\Entity\ProduitConditionnement;
use App\Form\ProduitConditionnementType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConditionnementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitConditionnementRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('admin/produit/conditionnement')]
class ProduitConditionnementController extends AbstractController
{
    #[Route('admin/produit/conditionnement', name: 'produit_conditionnement_index', methods: ['GET'])]
    public function index(ProduitConditionnementRepository $produitConditionnementRepository): Response
    {
        return $this->render('admin/produit_conditionnement/index.html.twig', [
            'produit_conditionnements' => $produitConditionnementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'produit_conditionnement_new', methods: ['GET', 'POST'])]
    public function new( HandleImage $handleImage,Request $request, EntityManagerInterface $entityManager,ProductRepository $productRepository,ConditionnementRepository $conditionnementRepository): Response
    {
        $produitConditionnement = new ProduitConditionnement();
        $form = $this->createForm(ProduitConditionnementType::class, $produitConditionnement,['product' => $productRepository->findAll(),'conditionnement' => $conditionnementRepository->findAll()] );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Recuperer le fichier 
            /** @var UploadedFile $file */
            $file = $form->get('image_path')->getData();
            //Verifier que il y a bien un fichier
            if($file)
            {
                $produitConditionnement->setImagepath($handleImage->save($file));
            }

            $entityManager->persist($produitConditionnement);
            $entityManager->flush();

            return $this->redirectToRoute('produit_conditionnement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/produit_conditionnement/new.html.twig', [
            'produit_conditionnement' => $produitConditionnement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'produit_conditionnement_show', methods: ['GET'])]
    public function show(ProduitConditionnement $produitConditionnement): Response
    {
        return $this->render('admin/produit_conditionnement/show.html.twig', [
            'produit_conditionnement' => $produitConditionnement,
        ]);
    }

    #[Route('/{id}/edit', name: 'produit_conditionnement_edit', methods: ['GET', 'POST'])]
    public function edit(HandleImage $handleImage,Request $request, ProduitConditionnement $produitConditionnement, EntityManagerInterface $entityManager,ProductRepository $productRepository,ConditionnementRepository $conditionnementRepository): Response
    {
     $oldImage = $produitConditionnement->getImagePath();

        $form = $this->createForm(ProduitConditionnementType::class, $produitConditionnement,['product' => $productRepository->findAll(),'conditionnement' => $conditionnementRepository->findAll()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

             //Recuperer le fichier 
            /** @var UploadedFile $file */
            $file = $form->get('image_path')->getData();
            //Verifier que il y a bien un fichier
            if($file)
            {
                $produitConditionnement->setImagepath($handleImage->save($file));
                $handleImage->edit($file,(string)$oldImage);
            }

            $entityManager->flush();

            return $this->redirectToRoute('produit_conditionnement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/produit_conditionnement/edit.html.twig', [
            'produit_conditionnement' => $produitConditionnement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'produit_conditionnement_delete', methods: ['POST'])]
    public function delete(Request $request, ProduitConditionnement $produitConditionnement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produitConditionnement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produitConditionnement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('produit_conditionnement_index', [], Response::HTTP_SEE_OTHER);
    }
}