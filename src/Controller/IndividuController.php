<?php

namespace App\Controller;

use App\Entity\LotExploitation;
use App\Entity\IndividuExploitation;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\IndividuExploitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/experimentation/lot")
 */
class IndividuController extends AbstractController
{
    /**
     * @Route("/{idLot}/individus", name="individu_index")
     */
    public function index(Request $request, IndividuExploitationRepository $individuExploitationRepository, LotExploitation $lot, PaginatorInterface $paginator): Response
    {
        $individus = $individuExploitationRepository->findByLot($lot->getIdLot());
        $individus = $paginator->paginate(
            $individus,
            $request->query->getInt('page', 1),
            25
        );
        return $this->render('individu/index.html.twig', [
            'individus' => $individus,
        ]);
    }

    /**
     * @Route("/{idIndividuExploitation}", name="individu_show", methods={"GET"})
     */
    public function show(IndividuExploitation $individuExploitation): Response
    {
        return $this->render('individu/show.html.twig', [
            'individu_exploitation' => $individuExploitation,
        ]);
    }
}