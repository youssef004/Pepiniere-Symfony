<?php

namespace UserBundle\Controller;
use ProduitBundle\Entity\Produit;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $produits=$this->getDoctrine()->getRepository( Produit::class)->findAll();
        $a=1;
        $membre=$this->container->get('security.token_storage')->getToken()->getUser();
        if( $this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))

            return $this->render('@Annonce/homeAdmin.html.twig');
        else if(
        $this->container->get('security.authorization_checker')->isGranted('ROLE_USER'))
            return $this->render('@Produit/Client/afficheProduit.html.twig',array('produits'=>$produits));
        else
            return $this->render('@Produit/Client/afficheProduit.html.twig',array('a'=>$a));
    }




}
