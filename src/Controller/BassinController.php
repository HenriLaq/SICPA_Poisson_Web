<?php

namespace App\Controller;

use App\Entity\LotExploitation;
use App\Entity\ExperimentationExploitation;
use App\Repository\LotExploitationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BassinController extends AbstractController
{
    /**
     * @Route("/experimentation/{idExpe}/lot/{idLot}/bassin", name="index_bassin")
     */
    public function index_bassin(LotExploitationRepository $lotExploitationRepository, ExperimentationExploitation $expe, LotExploitation $lot): Response
    {
        $datas = $lotExploitationRepository->findBassinByLot($lot->getIdLot());
        return $this->render('bassin/index.html.twig', [
            'datas' => $datas,
            'idExpe' => $expe->getIdExpe()
        ]);
    }
}
