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
     * @Route("/experimentation/{idExpe}/lot/{idLot}/historique", name="lot_historique")
     */
    public function historique(LotExploitationRepository $lotExploitationRepository, ExperimentationExploitation $expe, LotExploitation $lot): Response
    {
        $datas = $lotExploitationRepository->findLocaliseByLot($lot->getIdLot());
        return $this->render('lot/historique.html.twig', [
            'datas' => $datas,
            'idExpe' => $expe->getIdExpe()
        ]);
    }
}
