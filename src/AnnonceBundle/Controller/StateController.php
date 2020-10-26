<?php

namespace AnnonceBundle\Controller;

use AnnonceBundle\Entity\annonce;
use AnnonceBundle\Entity\categorie;
use AnnonceBundle\Entity\jaime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ob\HighchartsBundle\Highcharts\Highchart;

class StateController extends Controller
{
    public function StateAction()
    {   $categories=$this->getDoctrine()->getRepository( categorie::class)->findAll();
        $ob = new Highchart();
        $ob->chart->renderTo('piechart');
        $ob->title->text('les nombre de particpant dans les categories');
        $ob->plotOptions->pie(array(
            'allowPointSelect'  => true,
            'cursor'    => 'pointer',
            'dataLabels'    => array('enabled' => false),
            'showInLegend'  => true
        ));
        for($i = 0; $i < count($categories); ++$i){
            $data[]=array($categories[$i]->getType(),$categories[$i]->getNbrParticipant());
        }


        $ob->series(array(array('type' => 'pie','name' => 'Browser share', 'data' => $data)));
        return $this->render('@Annonce/state.html.twig', array(
            'chart' => $ob));

    }
    public function pieAction(){
        {   $jaime=$this->getDoctrine()->getRepository( jaime::class)->findAll();
            $ob = new Highchart();
            $ob->chart->renderTo('piechart');
            $ob->title->text('les nombre de particpant dans les categories');
            $ob->plotOptions->pie(array(
                'allowPointSelect'  => true,
                'cursor'    => 'pointer',
                'dataLabels'    => array('enabled' => false),
                'showInLegend'  => true
            ));
            $annonce=$this->getDoctrine()->getRepository(annonce::class)->findAll();
            for($i = 0; $i < count($annonce); ++$i){
                $jaime=$this->getDoctrine()->getRepository(jaime::class)->findBy(array('idAnnonce'=>$annonce[$i]));
                $nbr=count($jaime);
                $data[]=array($annonce[$i]->getTitre(),$nbr);


            }


            $ob->series(array(array('type' => 'pie','name' => 'Browser share', 'data' => $data)));
            return $this->render('@Annonce/state.html.twig', array(
                'chart' => $ob));

        }

        return $this->render('@Annonce/pie.html.twig');

    }



}
