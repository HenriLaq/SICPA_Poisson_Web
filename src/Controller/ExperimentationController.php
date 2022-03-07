<?php

namespace App\Controller;

use App\Entity\ExperimentationExploitation;
use App\Form\ExperimentationExploitationType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\IndividuExploitationRepository;
use App\Repository\ExperimentationExploitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExperimentationController extends AbstractController
{
    /**
     * @Route("/", name="experimentation_index")
     */
    public function index(ExperimentationExploitationRepository $experimentationExploitationRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
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

}
