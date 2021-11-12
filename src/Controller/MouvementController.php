<?php

namespace App\Controller;

use App\Entity\LotExploitation;
use App\Entity\ExperimentationExploitation;
use App\Repository\LotExploitationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MouvementController extends AbstractController
{
    /**
     * @Route("/experimentation/{idExpe}/lots/{idLot}/mouvement", name="mouvement_index")
     */
    public function index(LotExploitationRepository $lotExploitationRepository, ExperimentationExploitation $expe): Response
    {
        $mouvements = $lotExploitationRepository->findByExpe($expe->getIdExpe());
        return $this->render('mouvement/index.html.twig', [
            'mouvements' => $mouvements,
        ]);
    }
}
