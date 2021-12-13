<?php

namespace App\Controller;

use App\Entity\ExperimentationExploitation;
use App\Entity\LotExploitation;
use App\Entity\IndividuExploitation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\IndividuExploitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/experimentation")
 */
class IndividuController extends AbstractController
{
    /**
     * @Route("/{idExpe}/lot/{idLot}/individu", name="individu_index")
     */
    public function index(IndividuExploitationRepository $individuExploitationRepository, LotExploitation $lot, ExperimentationExploitation $expe): Response
    {
        $individus = $individuExploitationRepository->findByLot($lot->getIdLot());
        return $this->render('individu/index.html.twig', [
            'idLot' => $lot->getIdLot(),
            'individus' => $individus,
            'idExpe' => $expe->getIdExpe()
        ]);
    }

    /**
     * @Route("/lot/{idIndividuExploitation}", name="individu_show", methods={"GET"})
     */
    public function show(IndividuExploitation $individuExploitation): Response
    {
        return $this->render('individu/show.html.twig', [
            'individu_exploitation' => $individuExploitation,
        ]);
    }
}
