<?php

namespace App\Controller;

use App\Entity\LotExploitation;
use App\Entity\ExperimentationExploitation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AlimentationExploitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AlimentationController extends AbstractController
{
    /**
     * @Route("/experimentation/{idExpe}/lot/{idLot}/alimentation", name="alimentation_index")
     */
    public function index(AlimentationExploitationRepository $alimentationExploitationRepository, ExperimentationExploitation $expe, LotExploitation $lot): Response
    {
        $datas = $alimentationExploitationRepository->findAlimByLot($lot->getIdLot());
        return $this->render('alimentation/index.html.twig', [
            'datas' => $datas,
            'idExpe' => $expe->getIdExpe()
        ]);
    }
}
