<?php

namespace App\Controller;

use App\Entity\LotExploitation;
use App\Entity\IndividuExploitation;
use App\Entity\ExperimentationExploitation;
use App\Repository\LotExploitationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\IndividuExploitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MouvementController extends AbstractController
{

    /**
     * @Route("/experimentation/{idExpe}/lot/{idLot}/mouvements", name="mouvement_index")
     */
    public function index(LotExploitationRepository $lotExploitationRepository, LotExploitation $lot, ExperimentationExploitation $expe): Response
    {
        $mouvements = $lotExploitationRepository->findHistoByLot($lot->getIdLot());

        return $this->render('mouvement/index.html.twig', [
            'idExpe' => $expe->getIdExpe(),
            'idLot' => $lot->getIdLot(),
            'mouvements' => $mouvements,
        ]);
    }
}
