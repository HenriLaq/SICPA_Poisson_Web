<?php

namespace App\Controller;

use App\Entity\LotExploitation;
use App\Entity\ZoneExploitation;
use App\Entity\BassinExploitation;
use App\Entity\ExperimentationExploitation;
use App\Repository\LotExploitationRepository;
use App\Repository\ZoneExploitationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BassinExploitationRepository;
use App\Repository\AlimentationEauExploitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BassinController extends AbstractController
{
    /**
     * @Route("/experimentation/{idExpe}/lot/{idLot}/bassin", name="bassin_index")
     */
    public function index(BassinExploitationRepository $bassinExploitationRepository, ExperimentationExploitation $expe, LotExploitation $lot, AlimentationEauExploitationRepository $alimRepo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        if ((($user->getRoles()[0] == "ROLE_SUPER_ADMIN") 
        OR ($user->getRoles()[0] == "ROLE_ADMIN_UNITE" AND $user->getIdUnite() == $expe->getIdUnite())
        OR ($user->getIdUtili() == $expe->getIdEstRespTechn()) 
        OR ($user->getIdUtili() == $expe->getIdUtili()) )
        AND $user->getFinEstMembre() == null){

            $bassins = $bassinExploitationRepository->findBassinById($lot->getIdBassin());
            return $this->render('bassin/index.html.twig', [
                'idLot' => $lot->getIdLot(),
                'bassins' => $bassins,
                'idExpe' => $expe->getIdExpe()
            ]);
            
        }else{
            return $this->render('security/interdit.html.twig', []);
        }
        
    }

    
    /**
     * @Route("/experimentation/{idExpe}/lot/{idLot}/bassin/{idBassin}/zone", name="bassin_zone")
     */
    public function showZone(BassinExploitation $bassin, ZoneExploitationRepository $zoneExploitationRepository, ExperimentationExploitation $expe): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        if ((($user->getRoles()[0] == "ROLE_SUPER_ADMIN") 
        OR ($user->getRoles()[0] == "ROLE_ADMIN_UNITE" AND $user->getIdUnite() == $expe->getIdUnite())
        OR ($user->getIdUtili() == $expe->getIdEstRespTechn()) 
        OR ($user->getIdUtili() == $expe->getIdUtili()) )
        AND $user->getFinEstMembre() == null){

            $zone = $zoneExploitationRepository->findOneZone($bassin->getIdZone());
        
            $plan = $zone->getPlanZone();
            if (isset($plan)){
                $data = stream_get_contents($plan);
                $response = new Response($data, 200, array('Content-Type' => 'application/pdf'));
                return $response;
            }
                
            $data = "Pas de Plan pour cette zone.";

            return $this->render('bassin/show.html.twig', [
                'data' => $data
            ]);
            
        }else{
            return $this->render('security/interdit.html.twig', []);
        }
        
    }
}
