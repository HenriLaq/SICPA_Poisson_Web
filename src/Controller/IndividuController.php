<?php

namespace App\Controller;

use App\Entity\ExperimentationExploitation;
use App\Entity\LotExploitation;
use App\Entity\IndividuExploitation;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\IndividuExploitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/experimentation")
 */
class IndividuController extends AbstractController
{
    /**
     * @Route("/{idExpe}/lot/{idLot}/individus", name="individu_index")
     */
    public function index(Request $request, IndividuExploitationRepository $individuExploitationRepository, LotExploitation $lot, PaginatorInterface $paginator, ExperimentationExploitation $expe): Response
    {
        $individus = $individuExploitationRepository->findByLot($lot->getIdLot());
        $individus = $paginator->paginate(
            $individus,
            $request->query->getInt('page', 1),
            25
        );
        return $this->render('individu/index.html.twig', [
            'individus' => $individus,
            'idExpe' => $expe->getIdExpe()
        ]);
    }

    /**
     * @Route("/lot/{idIndividuExploitation}", name="individu_show", methods={"GET"})
     */
    public function show(IndividuExploitation $individuExploitation): Response
    {
        return $this->render('individu/show.html.twig', [
            'individu_exploitation' => $individuExploitation,
        ]);
    }
}
