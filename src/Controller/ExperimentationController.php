<?php

namespace App\Controller;

use App\Entity\ExperimentationExploitation;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\ExperimentationExploitationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\IndividuExploitationRepository;
use App\Repository\ExperimentationExploitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/experimentation")
 */
class ExperimentationController extends AbstractController
{
    /**
     * @Route("/", name="experimentation_index")
     */
    public function index(ExperimentationExploitationRepository $experimentationExploitationRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        //faut pas getId mais getIdutili, pour ca faut la colonne Utili dans table: demnader a sophie
        //pas getnomunite mais getidunite
        $experimentations = $experimentationExploitationRepository->findByUser(
            $user->getIdUtili(),
            $user->getRoles()[0],
            $user->getIdEquipe(),
            $user->getFinEstMembre()
        );
        return $this->render('experimentation/index.html.twig', [
            'experimentations' => $experimentations,
        ]);
    }

    /**
     * @Route("/{idExperimentationExploitation}", name="experimentation_show", methods={"GET"})
     */
    public function show(ExperimentationExploitation $experimentationExploitation): Response
    {
        return $this->render('experimentation/show.html.twig', [
            'experimentation_exploitation' => $experimentationExploitation,
        ]);
    }
}
