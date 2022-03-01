<?php

namespace App\Controller;

use App\Entity\LotExploitation;
use App\Entity\ExperimentationExploitation;
use App\Repository\LotExploitationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\IndividuExploitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LotController extends AbstractController
{
    /**
     * @Route("/experimentation/{idExpe}/lot", name="lot_index")
     */
    public function index(LotExploitationRepository $lotExploitationRepository, ExperimentationExploitation $expe): Response
    {
        $lots = $lotExploitationRepository->findAllByExpe($expe->getIdExpe());

        return $this->render('lot/index.html.twig', [
            'lots' => $lots,
            'idExpe' => $expe->getIdExpe()
        ]);
    }
}
