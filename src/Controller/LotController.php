<?php

namespace App\Controller;

use App\Entity\ExperimentationExploitation;
use App\Repository\LotExploitationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LotController extends AbstractController
{
    /**
     * @Route("/experimentation/{idExpe}/lots", name="lot_index")
     */
    public function index(LotExploitationRepository $lotExploitationRepository, ExperimentationExploitation $expe): Response
    {
        $lots = $lotExploitationRepository->findByExpe($expe->getIdExpe());
        return $this->render('lot/index.html.twig', [
            'lots' => $lots,
            'idExpe' => $expe->getIdExpe()
        ]);
    }
}
