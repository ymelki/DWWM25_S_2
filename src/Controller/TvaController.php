<?php

namespace App\Controller;

use App\Form\TvaType;
use App\Service\TvaService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TvaController extends AbstractController
{
    /**
     * @Route("/tva", name="app_tva")
     */
    public function index(Request $request, TvaService $tva): Response
    {
        // Recuperer le formulaire issue de TvaType::class
        $formulaire=$this->createForm(TvaType::class);

             //Lit les données envoyé via l'url
             $formulaire->handleRequest($request);

             // on vérifie si les donnée sont envoyé
             if ($formulaire->isSubmitted()){
     
                 // on recuperer les donnée envoyé
                 $data=$formulaire->getData();

                 // ancienne méthode $calcul=$data['prix']*1.2;
                 // nouvelle méthode
                 $calcul=$tva->calcul($data['prix']);

                 // on redirige vers la page envoye.html.twig
                 // avec la variable data['nom']
                 return $this->renderForm('tva/envoye.html.twig', [
                     'data' => $data,
                     'calcul' => $calcul
                 ]);
             }
             // si non on renvoie vers la page de TVA avec le form
             else {
          
                return $this->renderForm('tva/index.html.twig', [
                    'form' => $formulaire,
                ]);
             }



    }
}
