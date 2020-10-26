<?php

namespace ProduitBundle\Controller;

use ProduitBundle\Entity\CategoryProduit;
use ProduitBundle\Entity\Produit;
use ProduitBundle\Entity\Util;
use ProduitBundle\Entity\Inutil;
use ProduitBundle\Entity\Promotion;
use ProduitBundle\ProduitBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ProduitBundle\Form\ProduitType;
use ProduitBundle\Form\CategoryProduitType;
use AnnonceBundle\Form\categorieType;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProduitController extends Controller
{
    public function affichageAction()
    {
        return $this->render('@Produit/Client/afficheProduit.html.twig');
    }

    public function affichage1Action()
    {
        return $this->render('@Produit/Admin/afficheProduitAdmin.html.twig');
    }

    public function createAction(Request $request)
    {
        $memebre = $this->container->get('security.token_storage')->getToken()->getUser();
        $produit = new Produit();
        $f = $this->createForm(ProduitType::class, $produit);
        $f = $f->handleRequest($request);
        if ($f->isValid()) {
            $produit->setDateProduit(date_create());
            $file = $request->files->get('produitbundle_produit')['imageName'];
            //var_dump($file);
            $uploads_directory = $this->getParameter('uploads_directory');

            $fileName = $file->getClientOriginalName();

            //var_dump($fileName);


            $file->move(
                $uploads_directory, $fileName
            );
            $produit->setPictureProduit($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();
            $produit = $this->getDoctrine()->getRepository(Produit::class)->findAll();
            return $this->redirectToRoute('showProd');
        }
        return $this->render('@Produit/Admin/ajouterProduit.html.twig', array('f' => $f->createView()));
        $this->addFlash("success", "projet creer avec succee");
    }

    public function showProduitAction(Request $request)
    {
        $produits = $this->getDoctrine()->getRepository(Produit::class)->findAll();

        //$dql = "SELECT bp FROM AnnonceBundle:annonce bp";
        //$query=createQuery($dql);
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */

        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $produits,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 3)

        );
        return $this->render('@Produit/Admin/afficheProduitAdmin.html.twig', array('produits' => $result));

    }

    public function showHistoryProduitAction()
    {
        $produits = $this->getDoctrine()->getRepository(Produit::class)->findAll();
        return $this->render('@Produit/Admin/afficheHistoryProduitAdmin.html.twig', array('produits' => $produits));

    }

    public function deleteProdAction($IdProduit)
    {
        $em = $this->getDoctrine()->getManager();
        $prod = $em->getRepository("ProduitBundle:Produit")->find($IdProduit);

        $em->remove($prod);
        $em->flush();
        return $this->redirectToRoute("showProd");

    }

    public function updateProdAction($IdProduit, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $prod = $em->getRepository(Produit::class)->find($IdProduit);
        $f = $this->createForm(ProduitType::class, $prod);

        $f = $f->handleRequest($request);
        if ($f->isValid()) {

            $file = $request->files->get('produitbundle_produit')['imageName'];
            //var_dump($file);
            $uploads_directory = $this->getParameter('uploads_directory');

            $fileName = $file->getClientOriginalName();

            //var_dump($fileName);


            $file->move(
                $uploads_directory, $fileName
            );
            $prod->setPictureProduit($fileName);
            $prod->setDateProduit(date_create());
            $em = $this->getDoctrine()->getManager();
            $em->persist($prod);
            $em->flush();
            return $this->redirectToRoute('showProd');
        }


        return $this->render('@Produit/Admin/modifierProduitAdmin.html.twig', array('f' => $f->createView()));
    }

    public function showProduitClientAction(Request $request)
    {
        $produits = $this->getDoctrine()->getRepository('ProduitBundle:Produit')->findAll();
        $produitsDes = $this->getDoctrine()->getRepository('ProduitBundle:Produit')->getProduitByPrix();

        return $this->render('@Produit/Client/afficheProduit.html.twig', array('produits' => $produits));

    }

    public function showStockProduitAction(Request $request)
    {
        $stock = $this->getDoctrine()->getRepository('ProduitBundle:Produit')->findAll();

        return $this->render('@Produit/Admin/etatStockProduitAdmin.html.twig', array('stock' => $stock));

    }
    public function showMapProduitAction(Request $request)
    {
        $produits = $this->getDoctrine()->getRepository('ProduitBundle:Produit')->findAll();

        return $this->render('@Produit/Client/afficheMap.html.twig', array('produits' => $produits));

    }

    public function createCategAction(Request $request)
    {
        $Categ = new CategoryProduit();
        $f = $this->createForm(CategoryProduitType::class, $Categ);
        $f = $f->handleRequest($request);
        if ($f->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($Categ);
            $em->flush();
            return $this->redirectToRoute('showCategProd');
        }
        return $this->render('@Produit/Admin/ajouterCategory.html.twig', array('f' => $f->createView()));
    }

    public function showCategoryProduitAction()
    {
        $produits = $this->getDoctrine()->getRepository(CategoryProduit::class)->findAll();

        return $this->render('@Produit/Admin/afficheCategoryProduitAdmin.html.twig', array('categorys' => $produits));
    }

    public function deleteCategProdAction($IdCategoryProd)
    {
        $em = $this->getDoctrine()->getManager();
        $categ = $em->getRepository("ProduitBundle:CategoryProduit")->find($IdCategoryProd);
        $em->remove($categ);
        $em->flush();

        return $this->redirectToRoute("showCategProd");
    }

    public function AddUtilAction(Request $request)
    {

        $id = $request->get('id');
        var_dump($id);
        $em = $this->getDoctrine()->getManager();
        $isliked = $em->getRepository('ProduitBundle:Util')
            ->findBy(array('IdProduit' => $id, 'idMembre' => $this->getUser()->getId()));


        if (empty($isliked)) {

            $dislike = $em->getRepository('ProduitBundle:Inutil')
                ->findOneBy(array('IdProduit' => $id, 'idMembre' => $this->getUser()->getId()));
            if ($dislike != null) {
                $em->remove($dislike);
                $em->flush();
            }
            $rep = $em->getRepository('ProduitBundle:Produit')->find($id);

            $User = $this->getUser();

            $b = $em->getRepository('UserBundle:User')->find($this->getUser()->getId());

            $lik = new Util();
            $lik->setIdMembre($b);
            $lik->setIdProduit($rep);
            $em = $this->getDoctrine()->getManager();
            $em->persist($lik);
            $em->flush();
        }
        $rep = $em->getRepository('ProduitBundle:Util')->findBy(array('IdProduit' => $id));
        $number = count($rep);
        return new JsonResponse(array('number' => $number, 'id' => $id));
    }

    public function AddInutilAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $disliked = $em->getRepository('ProduitBundle:Inutil')
            ->findBy(array('IdProduit' => $id, 'idMembre' => $this->getUser()->getId()));

        if (empty($disliked)) {

            $islike = $em->getRepository('ProduitBundle:Util')
                ->findOneBy(array('IdProduit' => $id, 'idMembre' => $this->getUser()->getId()));
            if ($islike != null) {
                $em->remove($islike);
                $em->flush();
            }

            $RepliesId = $request->get('OffreId');

            $rep = $em->getRepository('ProduitBundle:Produit')->find($id);

            $b = $em->getRepository('UserBundle:User')->find($this->getUser()->getId());

            $User = $this->getUser();


            $dislik = new Inutil();

            $dislik->setIdMembre($b);

            $dislik->setIdProduit($rep);

            $em = $this->getDoctrine()->getManager();
            $em->persist($dislik);
            $em->flush();
        }
        return $this->redirectToRoute('showProdClient',array('id'=> $id));
    }

        public function CountAction(Request $request)
        {
            $id = $request->get('id');
            $em = $this->getDoctrine()->getManager();
            $rep = $em->getRepository('ProduitBundle:Util')->findBy(array('IdProduit' => $id));
            $number = count($rep);
            return new JsonResponse(array('number' => $number, 'id' => $id));
        }

    public function highAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('ProduitBundle:Produit')->findBy([], ['PriceProduit' => 'DESC']);
        return $this->render( '@Produit/Client/afficheProduit.html.twig', array('produits'=> $rep));
    }
    public function lowAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('ProduitBundle:Produit')->findBy([], ['PriceProduit' => 'ASC']);
        return $this->render( '@Produit/Client/afficheProduit.html.twig', array('produits'=> $rep));
    }

    public function annulerAction($IdProduit,Request $request){


    $em = $this->getDoctrine()->getManager();
    $produits=$em->getRepository(Produit::class)->findOneBy(array('IdProduit'=>$IdProduit));
    $f = $this->createForm(ProduitType::class,$produits);
    $f = $f->handleRequest($request);
    if($produits!=null){

        $name=$produits->getNameProduit();
        $type=$produits->getTypeProduit();
        $qunatity=$produits->getQuantityProduit();
        $picture=$produits->getPictureProduit();
        $description=$produits->getDescriptionProduit();
        $date=$produits->getDateProduit();
        //$categorie=$produits->getCategory();
        $promo=$produits->getPromotion();
        $localisation=$produits->getLocalisation();
        $produits->setNameProduit($name);
        $produits->setTypeProduit($type);
        $produits->setQuantityProduit($qunatity-1);
        $produits->setPictureProduit($picture);
        $produits->setDescriptionProduit($description);
        $produits->setDateProduit($date);
        $produits->setPromotion($promo);
        $produits->setLocalisation($localisation);
        $em= $this->getDoctrine()->getManager();
        $em->persist($produits);
        $em->flush();
        return $this->redirectToRoute('showProd');
    }if($produits->getQuantityProduit()==0){
            $produits1=$em->getRepository(Produit::class)->findOneBy(array('IdProduit'=>$id));
            $em->remove($produits1);
            $em->flush();
            return $this->redirectToRoute('showProd');




    }



}
    }
