<?php

namespace App\Controller;

use App\Entity\LotExploitation;
use App\Entity\ExperimentationExploitation;
use App\Repository\LotExploitationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HistoriqueController extends AbstractController
{
    /**
     * @Route("/experimentation/{idExpe}/lot/{idLot}/historique", name="historique_index")
     */
    public function index(LotExploitationRepository $lotExploitationRepository, ExperimentationExploitation $expe, LotExploitation $lot): Response
    {
        $datas = $lotExploitationRepository->findHistoByLot($lot->getIdLot());
        return $this->render('historique/index.html.twig', [
            'datas' => $datas,
            'idExpe' => $expe->getIdExpe()
        ]);
    }
}
