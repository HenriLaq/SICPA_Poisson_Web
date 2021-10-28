<?php

namespace App\Controller;

use App\Entity\ExperimentationExploitation;
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
    public function index(ExperimentationExploitationRepository $experimentationExploitationRepository): Response
    {
        return $this->render('experimentation/index.html.twig', [
            'experimentation_exploitations' => $experimentationExploitationRepository->findAll(), //BY UTILISATEUR
        ]);
    }

    /**
     * @Route("/new", name="experimentation_exploitation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $experimentationExploitation = new ExperimentationExploitation();
        $form = $this->createForm(ExperimentationExploitation1Type::class, $experimentationExploitation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($experimentationExploitation);
            $entityManager->flush();

            return $this->redirectToRoute('experimentation_exploitation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('experimentation_exploitation/new.html.twig', [
            'experimentation_exploitation' => $experimentationExploitation,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{idExpe}/lots", name="experimentation_lots")
     */
    public function lots(IndividuExploitationRepository $individuExploitationRepository, ExperimentationExploitation $expe): Response
    {
        $indivs = $individuExploitationRepository->findByExpe($expe->getIdExpe());
        return $this->render('individu/lots.html.twig', [
            'indivs' => $indivs
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
