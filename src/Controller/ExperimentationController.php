<?php

namespace App\Controller;

use App\Entity\ExperimentationExploitation;
use App\Repository\LotExploitationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\IndividuExploitationRepository;
use App\Repository\MouvementExploitationRepository;
use App\Repository\AlimentationExploitationRepository;
use App\Repository\ReleveAnimalExploitationRepository;
use App\Repository\ExperimentationExploitationRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExperimentationController extends AbstractController
{
    /**
     * @Route("/", name="experimentation_index")
     */
    public function index(ExperimentationExploitationRepository $experimentationExploitationRepository, LotExploitationRepository $lotRepo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $experimentations = $experimentationExploitationRepository->findByUser(
            $user->getIdUtili(),
            $user->getRoles()[0],
            $user->getIdUnite(),
            $user->getFinEstMembre()
        );

        $lotsParExpe = [];
        foreach($experimentations as $experimentation){
            $lotsParExpe[$experimentation->getIdExpe()] = count($lotRepo->findCountByExpe($experimentation->getIdExpe()));
        }

        return $this->render('experimentation/index.html.twig', [
            'experimentations' => $experimentations,
            'lotsParExpe' => $lotsParExpe,
        ]);
    }

    /**
     * @Route("/experimentation/{idExpe}/form", name="experimentation_form")
     */
    public function form(Request $request, MouvementExploitationRepository $mouvRepo ,AlimentationExploitationRepository $alimRepo,ExperimentationExploitation $expe, LotExploitationRepository $lotExploitationRepository, IndividuExploitationRepository $indiRepo, ReleveAnimalExploitationRepository $relRepo): Response
    {
        //Query les lots
        $lotsExploitation = $lotExploitationRepository->findAllByExpe($expe->getIdExpe());

        $idParDate = [];
        foreach($lotsExploitation as $lots){
            $indis = $indiRepo->findByLotForm($lots->getIdLot());
            $rels = [];
            //query les releves
            foreach($indis as $indi){
                $rels = $relRepo->findByIndiForm($indi->getIdIndi());
                foreach($rels as $rel){
                    $idParDate[($rel->getDateRelAni())->format('d-m-Y')] = $rel->getIdRelAni();
                }
            }
        }

        //construire le form
        $form = $this->createFormBuilder()
            ->add('premier', ChoiceType::class, ['label' => 'Premier relevé', 'choices' => ['Date' => $idParDate]])
            ->add('dernier', ChoiceType::class, ['label' => 'Dernier relevé', 'choices' => ['Date' => $idParDate]])
            ->add('valider', SubmitType::class, ['label' => 'Valider'])
            ->getForm();

        //remplir les variables avec les bonnes valeurs
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $array = array();

            //For chaque lot
            foreach($lotsExploitation as $lots){
                $debut = $relRepo->findByRelId($data['premier'])[0]->getDateRelAni();
                $fin = $relRepo->findByRelId($data['dernier'])[0]->getDateRelAni();

                //Le 1er et dernier releves ne sont pas forcement du lot en cours (du 'for') donc on prends le plus proche pour ce lot dans la periode comme debut et fin
                //...mais comment mdr
                $pmi = $relRepo->findByRelId($data['premier'])[0]->getValeurRelAni();
                $pmf = $relRepo->findByRelId($data['dernier'])[0]->getValeurRelAni();

                $joursEcart = $debut->diff($fin)->days ;

                $gp = round(abs($pmf-$pmi)*100/($pmi*$joursEcart),3); 
                $gainind = round(abs($pmf-$pmi)/$joursEcart,3);
                $tspecroi = round(abs(log($pmf)-log($pmi))*100/$joursEcart,3);
                $icj = round( 100*($pmf**(1/3)-$pmi**(1/3))/$joursEcart ,3 );
                $qad = 0;
                $aliment = "";
                
                foreach($alimRepo->findAlimByLotAndDate($lots->getIdLot(), $debut->format('Y-m-d'), $fin->format('Y-m-d')) as $alim){
                    $qad += $alim->getOffert();
                    if(!strpos($aliment, $alim->getNomAliment())){
                        $aliment = $aliment . " " . $alim->getNomAliment() . " : ";
                    }
                }

                $nbmort = 0;
                $pdsmort = 0;
                $nb = 0;
                $pds = 0;
                $nbfin = 0;
                $pdsfin = 0;
                $feedefic = 0;
                //calcul des effectivs avec les donnees de mouvements
                //On assume que les releves sont faits sur les lots entiers
                foreach($indis as $i){
                    $nb += ($mouvRepo->findMouvByIndiAndDateDebut($i->getIdIndi(), $debut)[0])->getNouvelEffectif();
                    $nbfin += ($mouvRepo->findMouvByIndiAndDateFin($i->getIdIndi(), $fin)[0])->getNouvelEffectif();
                }

                $pds = $nb * $pmi;
                
                $pdsfin = $nbfin * $pmf;
                //calcul des effectifs morts avec les donnees de mouvements
                //pour les indivs. On prends les mouvs entre 2 dates. pour les mouvs on ajoute les mortalites, et * le poids.
                foreach($indis as $i){
                    $mouvs = $mouvRepo->findMouvByIndiAndTwoDates($i->getIdIndi(), $debut, $fin);
                    foreach($mouvs as $m){
                        $nbmort += $m->getEffectifMouvement();
                        $pdsmortTemp = $relRepo->findRelByMouvForm($m->getIdMouvement());
                        if (sizeof($pdsmortTemp)>0){
                            $pdsmort += $nbmort * $pdsmortTemp[0]->getValeurRelAni();
                        }
                        
                    }
                }
                
                $indcons = round( $qad/(($pdsfin+$pdsmort)-$pds) , 3);
                $cons = round( abs($qad)*100/((($pdsfin+$pds)/2)*$joursEcart) , 3);
                if($qad!=0){
                    $feedefic = round(abs(($pdsmort+$pdsfin)-$pds)/$qad,3);
                }
                $values = array('aliment' => $aliment, 
                                'pds' => $pds,
                                'nb' =>$nb,
                                'pdsmort' => $pdsmort,
                                'nbmort' => $nbmort,
                                'pmi' => $pmi,
                                'pmf' => $pmf,
                                'pdsfin' => $pdsfin,
                                'nbfin' => $nbfin,
                                'qad' => $qad,
                                'gp' => $gp,
                                'gainind' => $gainind,
                                'tspecroi' => $tspecroi,
                                'icj' => $icj,
                                'indcons' => $indcons,
                                'cons' => $cons,
                                'feedefic' => $feedefic);
                array_push($array, $values);
            }
            
            $return = $this->render('bilan/bilanZootechnique.csv.twig', [
                'expe' => $expe,
                'debut' => $debut->format('d-m-Y'),
                'fin' => $fin->format('d-m-Y'),
                'array' => $array,
            ]);
            $fic = 'Bilan Zootechnique '. \date("d-m-Y") . '.csv';
            $return->headers->set('Content-Disposition','attachment; filename="'.$fic.'"');
            return $return;
        }
        return $this->render('bilan/bilanZootechnique.html.twig', [
            'idExpe' => $expe->getIdExpe(),
            'form' => $form->createView(),
        ]);
    }


}
