<?php

namespace App\Controller;

use App\Entity\LotExploitation;
use App\Entity\IndividuExploitation;
use App\Entity\ExperimentationExploitation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\IndividuExploitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReleveController extends AbstractController
{

    /**
     * @Route("/experimentation/{idExpe}/lot/{idLot}/individu/{idIndividu}/releve", name="releve_indi_index")
     */
    public function index(IndividuExploitationRepository $individuExploitationRepository, IndividuExploitation $indi, LotExploitation $lot, ExperimentationExploitation $expe): Response
    {
        $releves = $individuExploitationRepository->findRelByIndi($indi->getIdIndi());
        return $this->render('releve_animal/index.html.twig', [
            'idExpe' => $expe->getIdExpe(),
            'idLot' => $lot->getIdLot(),
            'releves' => $releves,
        ]);
    }
}
