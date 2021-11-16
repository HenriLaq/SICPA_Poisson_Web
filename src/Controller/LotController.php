<?php

namespace App\Controller;

use App\Entity\ExperimentationExploitation;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\LotExploitationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LotController extends AbstractController
{
    /**
     * @Route("/experimentation/{idExpe}/lots", name="lot_index")
     */
    public function index(LotExploitationRepository $lotExploitationRepository, ExperimentationExploitation $expe, Request $request, PaginatorInterface $paginator): Response
    {
        $lots = $lotExploitationRepository->findAllByExpe($expe->getIdExpe());
        $lots = $paginator->paginate(
            $lots,
            $request->query->getInt('page', 1),
            25
        );
        return $this->render('lot/index.html.twig', [
            'lots' => $lots,
            'idExpe' => $expe->getIdExpe()
        ]);
    }
}
