<?php

namespace App\Controller;

use App\Entity\ExperimentationExploitation;
use App\Entity\IndividuExploitation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\IndividuExploitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/experimentation/individu")
 */
class IndividuController extends AbstractController
{
    /**
     * @Route("/", name="individu_index")
     */
    public function index(IndividuExploitationRepository $individuExploitationRepository): Response
    {
        return $this->render('individu/index.html.twig', [
            'individu_exploitations' => $individuExploitationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{idIndividuExploitation}", name="individu_show", methods={"GET"})
     */
    public function show(IndividuExploitation $individuExploitation): Response
    {
        return $this->render('individu/show.html.twig', [
            'individu_exploitation' => $individuExploitation,
        ]);
    }
}
