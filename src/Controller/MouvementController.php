<?php

namespace App\Controller;

use App\Entity\LotExploitation;
use App\Entity\ExperimentationExploitation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MouvementExploitationRepository;
use App\Repository\ReleveAnimalExploitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MouvementController extends AbstractController
{

    /**
     * @Route("/experimentation/{idExpe}/lot/{idLot}/mouvements", name="mouvement_index")
     */
    public function index(MouvementExploitationRepository $mouvementExploitationRepository, LotExploitation $lot, ExperimentationExploitation $expe, ReleveAnimalExploitationRepository $relAniRepo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $mouvements = $mouvementExploitationRepository->findHistoByLot($lot->getIdLot());
        $releves = [];
        foreach ($mouvements as $mouvement){
            if (sizeof($relAniRepo->findRelByMouv($mouvement->getIdMouvement())) != 0){
                array_push($releves, $relAniRepo->findOneRelByMouv($mouvement->getIdMouvement()));
            }
        }
        return $this->render('mouvement/index.html.twig', [
            'idExpe' => $expe->getIdExpe(),
            'lot' => $lot,
            'mouvements' => $mouvements,
            'releves' => $releves
        ]);
    }
}
