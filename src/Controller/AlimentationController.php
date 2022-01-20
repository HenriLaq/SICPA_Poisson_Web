<?php

namespace App\Controller;

use App\Form\JoursDeJeuneType;
use App\Entity\LotExploitation;
use App\Entity\ExperimentationExploitation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AlimentationExploitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AlimentationController extends AbstractController
{
    /**
     * @Route("/experimentation/{idExpe}/lot/{idLot}/alimentation", name="alimentation_index")
     */
    public function index(AlimentationExploitationRepository $alimentationExploitationRepository, ExperimentationExploitation $expe, LotExploitation $lot): Response
    {
        $alimentations = $alimentationExploitationRepository->findAlimByLot($lot->getIdLot());
        $semaines = [];
        $jeunes = [];
        $enCours = 0;
        $i = 0;
        $j = 0;
        foreach ($alimentations as $alimentation) {
            $semaines[$i] = $alimentation->getDateConditionAlim()->format("W");
            //Si on commence
            if ($i == 0) {
                $enCours = $semaines[$i];
                $jeunes += [$semaines[$i] => 0];
            }
            //Si on change de semaine
            if ($enCours != $semaines[$i]) {
                $j++;
                $enCours = $semaines[$i];
                $jeunes += [$semaines[$i] => 0];
                //Si on  ne change pas de semaine
            } else if ($alimentation->getCoeffAlim() == 0) {
                $jeunes[$semaines[$i]]++;
            }
            $i = $i + 1;
        }
        //dd($jeunes);
        return $this->render('alimentation/index.html.twig', [
            'alimentations' => $alimentations,
            'semaines' => $semaines,
            'jeunes' => $jeunes,
            'idExpe' => $expe->getIdExpe()
        ]);
    }
}
