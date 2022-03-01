<?php

namespace App\Controller;

use App\Entity\LotExploitation;
use App\Entity\ExperimentationExploitation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MouvementExploitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MouvementController extends AbstractController
{

    /**
     * @Route("/experimentation/{idExpe}/lot/{idLot}/mouvements", name="mouvement_index")
     */
    public function index(MouvementExploitationRepository $mouvementExploitationRepository, LotExploitation $lot, ExperimentationExploitation $expe): Response
    {
        $mouvements = $mouvementExploitationRepository->findHistoByLot($lot->getIdLot());

        return $this->render('mouvement/index.html.twig', [
            'idExpe' => $expe->getIdExpe(),
            'Lot' => $lot,
            'mouvements' => $mouvements,
        ]);
    }
}
