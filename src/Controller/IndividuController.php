<?php

namespace App\Controller;

use App\Entity\LotExploitation;
use App\Entity\IndividuExploitation;
use App\Entity\ExperimentationExploitation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\IndividuExploitationRepository;
use App\Repository\MouvementExploitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/experimentation")
 */
class IndividuController extends AbstractController
{
    /**
     * @Route("/{idExpe}/lot/{idLot}/individu", name="individu_index")
     */
    public function index(IndividuExploitationRepository $individuExploitationRepository, LotExploitation $lot, ExperimentationExploitation $expe, MouvementExploitationRepository $mvmtRepo): Response
    {
        $individus = $individuExploitationRepository->findAllByLot($lot->getIdLot());

        //GetEffectifs
        $effectifParIndi = [];
        foreach ($individus as $individu){
            $effectifParIndi[$individu->getIdIndi()] = ($mvmtRepo->findEffectifByIndi($individu->getIdIndi()))[0]->getNouvelEffectif();
        }

        return $this->render('individu/index.html.twig', [
            'idLot' => $lot->getIdLot(),
            'individus' => $individus,
            'idExpe' => $expe->getIdExpe(),
            'effectifParIndi' => $effectifParIndi,
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
