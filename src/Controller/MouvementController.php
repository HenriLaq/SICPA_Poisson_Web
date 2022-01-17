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
    public function index_indi(LotExploitationRepository $lotExploitationRepository, IndividuExploitationRepository $individuExploitationRepository, LotExploitation $lot, IndividuExploitation $indi, ExperimentationExploitation $expe): Response
    {
        $mouvements = $lotExploitationRepository->findHistoByLot($lot->getIdLot());
        $releves = $individuExploitationRepository->findRelByIndi($indi->getIdIndi());
        return $this->render('mouvement/index.html.twig', [
            'idExpe' => $expe->getIdExpe(),
            'idLot' => $lot->getIdLot(),
            'mouvements' => $mouvements,
            'releves' => $releves,
        ]);
    }
}
