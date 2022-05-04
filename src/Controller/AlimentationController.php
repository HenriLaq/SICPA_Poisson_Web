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
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        if ((($user->getRoles()[0] == "ROLE_SUPER_ADMIN") 
        OR ($user->getRoles()[0] == "ROLE_ADMIN_UNITE" AND $user->getIdUnite() == $expe->getIdUnite())
        OR ($user->getIdUtili() == $expe->getIdEstRespTechn()) 
        OR ($user->getIdUtili() == $expe->getIdUtili()) )
        AND $user->getFinEstMembre() == null){

            $alimentations = $alimentationExploitationRepository->findAlimByLot($lot->getIdLot());

            if (count($alimentations) > 0){
                //Compteur de jours de jeunes
                $jeunes = [$alimentations[0]->getDateConditionAlim()->format("W-Y") => 0];
                $enCours = 0;
                $qtt = [$alimentations[0]->getDateConditionAlim()->format("W-Y") => 0];
                $i = 0;
                foreach ($alimentations as $alimentation) {
                    $semaines[$i] = $alimentation->getDateConditionAlim()->format("W-Y");
                    $semainesAffiche[$i] = $alimentation->getDateConditionAlim()->format("W");
    
                    //Si on change de semaine
                    if ($enCours != $semaines[$i]) {
                        $enCours = $semaines[$i];
                        $jeunes += [$semaines[$i] => 0];
                        $qtt += [$semaines[$i] => $alimentation->getIngere()];
                        //Si on  ne change pas de semaine
                    } else if ($alimentation->getCoeffAlim() == 0) {
                        $jeunes[$semaines[$i]]++;
                        $qtt[$semaines[$i]] = $qtt[$semaines[$i]] + $alimentation->getIngere();
                    }else{
                        $qtt[$semaines[$i]] = $qtt[$semaines[$i]] + $alimentation->getIngere();
                    }
                    $i = $i + 1;
                }
            }else{
                $semaines = null;
                $semainesAffiche = null;
                $jeunes = null;
            }
            
            return $this->render('alimentation/index.html.twig', [
                'alimentations' => $alimentations,
                'semainesAffiche' => $semainesAffiche,
                'semaines' => $semaines,
                'jeunes' => $jeunes,
                'idExpe' => $expe->getIdExpe(),
                'qtt' => $qtt,
            ]);
            
        }else{
            return $this->render('security/interdit.html.twig', []);
        }
        
        
    }
}
