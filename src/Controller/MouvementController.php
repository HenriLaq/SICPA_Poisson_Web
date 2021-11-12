<?php

namespace App\Controller;

use App\Entity\LotExploitation;
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
}
