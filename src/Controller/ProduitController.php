<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/produit', name: 'produit_')]
final class ProduitController extends CommonBaseController
{
    #[Route('/', name: 'index')]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('frontend/produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request): Response
    {
        $form = $this->createForm(ProduitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $produit = $form->getData();
            $this->getEm()->persist($produit);
            $this->getEm()->flush();

        }
        return $this->render('frontend/produit/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
