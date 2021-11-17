<?php

namespace App\Controller;

use App\Entity\LotExploitation;
use App\Entity\IndividuExploitation;
use App\Entity\ExperimentationExploitation;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\LotExploitationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MouvementController extends AbstractController
{

    /**
     * @Route("/experimentation/{idExpe}/lots/{idLot}/mouvement", name="mouvement_index")
     */
    /*
    public function index(LotExploitationRepository $lotExploitationRepository, ExperimentationExploitation $expe, Request $request, PaginatorInterface $paginator): Response
    {
        $mouvements = $lotExploitationRepository->findMouvByExpe($expe->getIdExpe());
        $mouvements = $paginator->paginate(
            $mouvements,
            $request->query->getInt('page', 1),
            25
        );
        return $this->render('mouvement/index.html.twig', [
            'mouvements' => $mouvements,
        ]);
    }
    */

    /**
     * @Route("/experimentation/{idExpe}/lots/{idLot}/individu/{idIndividu}/mouvement", name="mouvement_indi_index")
     */
    public function index_indi(LotExploitationRepository $lotExploitationRepository, IndividuExploitation $indi, Request $request, PaginatorInterface $paginator, LotExploitation $lot, ExperimentationExploitation $expe): Response
    {
        $mouvements = $lotExploitationRepository->findMouvByIndi($indi->getIdIndi());
        return $this->render('mouvement/index.html.twig', [
            'idExpe' => $expe->getIdExpe(),
            'idLot' => $lot->getIdLot(),
            'mouvements' => $mouvements,
        ]);
    }
}
