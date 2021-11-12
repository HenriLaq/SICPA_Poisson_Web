<?php

namespace App\Controller;

use App\Entity\ExperimentationExploitation;
use App\Form\ExperimentationExploitationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\IndividuExploitationRepository;
use App\Repository\ExperimentationExploitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/experimentation")
 */
class ExperimentationController extends AbstractController
{
    /**
     * @Route("/", name="experimentation_index")
     */
    public function index(ExperimentationExploitationRepository $experimentations): Response
    {
        return $this->render('experimentation/index.html.twig', [
            'experimentations' => $experimentations->findAll(), 
            //BY UTILISATEUR
            //on pourra enlever l'équivalent du DISTINCT sur le template en faisant la requete find DISTINCT
        ]);
    }

    /**
     * @Route("/new", name="experimentation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $experimentationExploitation = new ExperimentationExploitation();
        $form = $this->createForm(ExperimentationExploitationType::class, $experimentationExploitation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($experimentationExploitation);
            $entityManager->flush();

            return $this->redirectToRoute('experimentation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('experimentation/new.html.twig', [
            'experimentation_exploitation' => $experimentationExploitation,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{idExperimentationExploitation}", name="experimentation_show", methods={"GET"})
     */
    public function show(ExperimentationExploitation $experimentationExploitation): Response
    {
        return $this->render('experimentation/show.html.twig', [
            'experimentation_exploitation' => $experimentationExploitation,
        ]);
    }
    
}