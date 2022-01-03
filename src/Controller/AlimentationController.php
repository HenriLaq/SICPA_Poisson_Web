<?php

namespace App\Controller;

use App\Form\JoursDeJeuneType;
use App\Entity\LotExploitation;
use App\Entity\ExperimentationExploitation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AlimentationExploitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AlimentationController extends AbstractController
{
    /**
     * @Route("/experimentation/{idExpe}/lot/{idLot}/alimentation", name="alimentation_index")
     */
    public function index(Request $request, AlimentationExploitationRepository $alimentationExploitationRepository, ExperimentationExploitation $expe, LotExploitation $lot): Response
    {
        $datas = $alimentationExploitationRepository->findAlimByLot($lot->getIdLot());

        //compter les jours de jeune
        $form = $this->createForm(JoursDeJeuneType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $dates = $form->getData();
            $dateDiff = $dates['Fin']->diff($dates['Debut'])->format("%a");
            return $this->render('alimentation/index.html.twig', [
                'datas' => $datas,
                'idExpe' => $expe->getIdExpe(),
                'form' => $form->createView(),
                'dates' => $dates,
                'dateDiff' => $dateDiff,
            ]);
        }

        return $this->render('alimentation/index.html.twig', [
            'datas' => $datas,
            'idExpe' => $expe->getIdExpe(),
            'form' => $form->createView(),
        ]);
    }
}
