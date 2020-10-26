<?php

namespace AnnonceBundle\Controller;


use AnnonceBundle\AnnonceBundle;
use AnnonceBundle\Entity\annonce;
use AnnonceBundle\Entity\jaime;
use AnnonceBundle\Form\annonceType;
use AnnonceBundle\Form\jaimeType;
use http\Client\Curl\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AnnonceBundle\Entity\categorie;
use AnnonceBundle\Form\categorieType;
use AnnonceBundle\Entity\annonceCat;
use AnnonceBundle\Form\annonceCatType;
use Doctrine\Common\Persistence\ObjectManager;



class AnnonceController extends Controller
{
    public function AfficheAnnonceAction()
    {
        return $this->render('@Annonce/Annonce.html.twig');
    }
    public function homeAdminAction()
    {
        return $this->render('@Annonce/homeAdmin.html.twig');
    }
    public function PageAffichageAction(){
        return $this->render('@Annonce/AnnonceAfficher.html.twig');
    }
    public function AnnonceClientAction(){
        $annonces=$this->getDoctrine()->getRepository( annonce::class)->findAll();
        $categories=$this->getDoctrine()->getRepository( categorie::class)->findAll();


        return $this->render('@Annonce/AnnonceClient.html.twig',array('annonces'=>$annonces,'categories'=>$categories));

    }
    public function AjouterCategorieAction(Request $request){
        $categorie=new categorie();
        $f=$this->createForm(categorieType::class, $categorie);
        $f=$f->handleRequest($request);

        if($f->isValid()){
            $sem=$this->getDoctrine()->getManager();
            $date_debut=$categorie->getDateDebut();
            $datefin=$categorie->getDatefin();
            $ann=$categorie->getIdAnnonce();
            $categoriess=$this->getDoctrine()->getRepository( categorie::class)->findOneBy(array('idAnnonce'=> $ann));
           if($categoriess!=null){
               echo "<script>alert(\" choisir autre annonce  \")</script>";
               return $this->render('@Annonce/AjouterCategorie.html.twig',array('f'=> $f->createView()));
           }
            if($datefin>$date_debut){
                $sem->persist($categorie);
                $sem->flush();
                echo "categorie ajouter";
                echo "<script>alert(\" categorie ajouter  \")</script>";
                return $this->redirectToRoute("Ajoutercategorie");

                    }else{

                echo "<script>alert(\" Attention ! verifier les date et nbr de participant\")</script>";
                    }


            }
        return $this->render('@Annonce/AjouterCategorie.html.twig',array('f'=> $f->createView()));

    }

    public function AjouterAnnonceAction(Request $request){
        $annonce=new annonce();
        $f=$this->createForm(annonceType::class, $annonce);
        $f=$f->handleRequest($request);
        if($f->isValid()){
            //var_dump($request->files->get('annoncebundle_annonce')['imageName']);
            $file=$request->files->get('annoncebundle_annonce')['imageName'];
            //var_dump($file);
            $uploads_directory=$this->getParameter('uploads_directory');

            $fileName = $file->getClientOriginalName();
            //var_dump($fileName);

            $file->move(
                $uploads_directory,$fileName
            );
            $annonce->setImageName($fileName);
            $em=$this->getDoctrine()->getManager();
            $em->persist($annonce);
            $em->flush();
            $annonces=$this->getDoctrine()->getRepository( annonce::class)->findAll();
            echo "<script>alert(\" annonce ajouter \")</script>";

            return $this->render('@Annonce/Annonce.html.twig',array('f'=> $f->createView()));}
        return $this->render('@Annonce/Annonce.html.twig',array('f'=> $f->createView()));
    }
    public function read1Action(Request $request){
        $annonces=$this->getDoctrine()->getRepository( annonce::class)->findAll();
        //$dql = "SELECT bp FROM AnnonceBundle:annonce bp";
        //$query=createQuery($dql);
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator=$this->get('knp_paginator');
        $result=$paginator->paginate(
            $annonces,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',3)

        );

        return $this->render('@Annonce/AnnonceAfficher.html.twig',array('annonces'=>$result));
    }
    public function deleteAction($id){
        $em = $this->getDoctrine()->getManager();
        $annonce=$em->getRepository("AnnonceBundle:annonce")->find($id);
        $categorie=$em->getRepository( "AnnonceBundle:categorie")->findOneBy(array('idAnnonce'=>$id));
        $jaime=$em->getRepository(jaime::class)->findBy(array('idAnnonce'=>$id));
        if($jaime!=null){
            for($i=0;$i<count($jaime);$i++){
                $em->remove($jaime[$i]);

            }
        }
       if($categorie!=null){
           $em->remove($categorie);
       }
        $em->remove($annonce);
        $em->flush();
        return $this->redirectToRoute('annonce_Affichage');
    }
    public function modifierAction($id,Request $request){
        $em = $this->getDoctrine()->getManager();
        $annonce=$em->getRepository(annonce::class)->find($id);
        $f = $this->createForm(annonceType::class,$annonce);
        $f = $f->handleRequest($request);
        if($f->isValid()){

            $file=$request->files->get('annoncebundle_annonce')['imageName'];
            $uploads_directory=$this->getParameter('uploads_directory');
            $fileName = $file->getClientOriginalName();
            $file->move(
                $uploads_directory,$fileName
            );
            $annonce->setImageName($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($annonce);
            $em->flush();
            return $this->redirectToRoute('annonce_Affichage');
        }
        return $this->render('@Annonce/AnnonceUpdate.html.twig',array('f'=>$f->createView()));
    }
    public function afficherCategorieAction($id,Request $request){
        $em = $this->getDoctrine()->getManager();
        $categories=$em->getRepository(categorie::class)->findBy(array('idAnnonce'=> $id));
       // var_dump( $categories);
        $annonce=$em->getRepository(annonce::class)->find($id);
        //var_dump( $annonce);
       // die();

        return $this->render('@Annonce/AfficherCategorie.html.twig',array('categories'=> $categories,'annonce'=>$annonce));

    }
    public function participerAction($idCategorie,Request $request){
        $annonceCat=new annonceCat();
        $f1=$this->createForm(annonceCatType::class, $annonceCat);
        $f1=$f1->handleRequest($request);


       // $annonceCat->setIdannonceCat(null);

        $em = $this->getDoctrine()->getManager();
        $categorie=$em->getRepository(categorie::class)->find($idCategorie);
        $f = $this->createForm(categorieType::class,$categorie);
        $f = $f->handleRequest($request);
        $type=$categorie->getType();
        $date_debut=$categorie->getDateDebut();
        $date_fin=$categorie->getDatefin();
        $nbr=$categorie->getNbrParticipant();
        $idannonce=$categorie->getIdAnnonce();
        $categorie->setType($type);
        $categorie->setDateDebut($date_debut);
        $user=$this->container->get('security.token_storage')->getToken()->getUser()->getId();
        $catann=$em->getRepository(annonceCat::class)->findBy(array('idCategorie'=>$idannonce,'User'=>$user));
        $annonceCat->setUser($user);
        $annonceCat->setIdCategorie( $idannonce->getId());
        $categorie->setDatefin($date_fin);
        $categorie->setNbrParticipant($nbr+1);
        $categorie->setIdAnnonce($idannonce);
        if($catann==null){
            $em= $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->persist($annonceCat);
            $em->flush();
            return $this->redirectToRoute('authentification',array('f'=>$f));}
        else{
            echo "<script language='javascript'>";
            echo "if(!alert('tu es deja particper')){
            window.location.reload();}";
            echo "</script>";



        }

        }

    public function affichertousAction(Request $request){

        $categorie=$this->getDoctrine()->getRepository( categorie::class)->findAll();
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator=$this->get('knp_paginator');
        $result=$paginator->paginate(
            $categorie,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',3)

        );



        return $this->render('@Annonce/Affichertous.html.twig',array('categorie'=>$result));

    }
    public function deletecategorieAction($id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categorie=$em->getRepository("AnnonceBundle:categorie")->find($id);
        $em->remove($categorie);
        $em->flush();
        return $this->redirectToRoute('affichertous');
    }
    public function showAction ($id,Request $request){
        $categorie=$this->getDoctrine()->getRepository(categorie::class)->findOneBy(array('Id_categorieAnnonce'=> $id));
        $id_annonce=$categorie->getIdAnnonce();

        $annonce=$this->getDoctrine()->getRepository(annonce::class)->findOneBy(array('Id'=> $id_annonce));
        return $this->render('@Annonce/show.html.twig',array('categorie'=>$categorie,'annonce'=>$annonce));

    }
    public function showUserAction($id,Request $request){
        $annonceCat=$this->getDoctrine()->getRepository(annonceCat::class)->findBy(array('idCategorie'=>$id));
        $list=array();
        $userManager = $this->get('fos_user.user_manager');
         for($i = 0; $i < count($annonceCat); ++$i){
            $userId=$annonceCat[$i]->getUser();
            $users = $userManager->findUserBy(array('id'=>$userId));
            $list[$i]=$users;
        }
        //var_dump($list);
        return $this->render('@Annonce/showUser.html.twig',array('list'=>$list));
    }
    public function AnnonceparticiperAction(Request $request){
        $user=$this->container->get('security.token_storage')->getToken()->getUser()->getId();
        $annonceCat=$this->getDoctrine()->getRepository(annonceCat::class)->findBy(array('User'=>$user));

        $list=array();
        for($i = 0; $i < count($annonceCat); ++$i){
            $idcategorie=$annonceCat[$i]->getIdCategorie();

            $categorie=$this->getDoctrine()->getRepository(annonce::class)->find($idcategorie);
            //var_dump($categorie);

            if($categorie!=null){
                $list[$i]=$categorie;
            }
        }
        return $this->render('@Annonce/Annonceparticiper.html.twig',array('list'=>$list));



    }
    public function annulerAction($id,Request $request){
        $user=$this->container->get('security.token_storage')->getToken()->getUser()->getId();
        $annonceCat=$this->getDoctrine()->getRepository(annonceCat::class)->findOneBy(array('User'=>$user,'idCategorie'=>$id));
        $em = $this->getDoctrine()->getManager();
        $categorie=$em->getRepository(categorie::class)->findOneBy(array('idAnnonce'=>$id));
        $f = $this->createForm(categorieType::class,$categorie);
        $f = $f->handleRequest($request);
        if($categorie!=null){

            $type=$categorie->getType();
            $date_debut=$categorie->getDateDebut();
            $date_fin=$categorie->getDatefin();
            $nbr=$categorie->getNbrParticipant();
            $idannonce=$categorie->getIdAnnonce();
            $categorie->setType($type);
            $categorie->setDateDebut($date_debut);
            $annonceCat->setUser($user);
            $annonceCat->setIdCategorie( $idannonce->getId());
            $categorie->setDatefin($date_fin);
            $categorie->setNbrParticipant($nbr-1);
            $categorie->setIdAnnonce($idannonce);
            $em= $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->remove($annonceCat);
            $em->flush();
            return $this->redirectToRoute('Annonceparticiper');
        }else{
            echo "<script>alert(\" choisir autre annonce  \")</script>";
            $em->remove($annonceCat);
            $em->flush();
            return $this->redirectToRoute('Annonceparticiper');

        }



    }
    public function favoriserAction(Request $request){
        $annonce=$this->getDoctrine()->getRepository( annonce::class)->findAll();
        $category=$this->getDoctrine()->getRepository( categorie::class)->findAll();
        $list=[];
        $list1=[];
        $max=0;
        for($i = 0; $i < count($category); ++$i){
            $x=$category[$i]->getNbrParticipant();
            $max=$max+$x;

        }
        $moy=$max/2;
        for($i = 0; $i < count($annonce); ++$i){
            $Id=$annonce[$i]->getId();
            //var_dump($Id);
            $categories=$this->getDoctrine()->getRepository( categorie::class)->findOneBy(array('idAnnonce'=>$Id));
            if($categories!=null){
               // var_dump($categories);//die();
                $nbr=$categories->getNbrParticipant();
                if(($nbr+$nbr)>$moy-10){
                    $list[$nbr]=$annonce[$i];
                }
            }




        }
        $list1=sort($list);
       // var_dump($list);



        return $this->render('@Annonce/favoriser.html.twig',array('annonces'=>$list));
    }
    public function adoreAction($id,Request $request){

        $jaime= new jaime();

        $f=$this->createForm(jaimeType::class, $jaime);
        $f=$f->handleRequest($request);

        $user=$this->container->get('security.token_storage')->getToken()->getUser()->getId();

        $jaime1=$this->getDoctrine()->getRepository( jaime::class)->findOneBy(array('idAnnonce'=>$id,'user'=>$user));

        if($jaime1==null){

            $jaime->setIdannonce($id);

            $jaime->setUSer($user);

            $em= $this->getDoctrine()->getManager();
            $em->persist($jaime);
            echo "<script>alert(\" tu aime cette page  \")</script>";
            $em->flush();
            $categories=$em->getRepository(categorie::class)->findBy(array('idAnnonce'=> $id));

            $annonce=$em->getRepository(annonce::class)->find($id);


            return $this->render('@Annonce/AfficherCategorie.html.twig',array('categories'=> $categories,'annonce'=>$annonce));



        }else{

            echo "<script>alert(\" jaime annuler  \")</script>";
            $em= $this->getDoctrine()->getManager();
            $em->remove($jaime1);
            $em->flush();
            //var_dump($jaime);

            $categories=$em->getRepository(categorie::class)->findBy(array('idAnnonce'=> $id));

            $annonce=$em->getRepository(annonce::class)->find($id);

            return $this->render('@Annonce/AfficherCategorie.html.twig',array('categories'=> $categories,'annonce'=>$annonce));


        }


    }
    public function showlesjaimeAction($id,Request $request){
        $jaime=$this->getDoctrine()->getRepository(jaime::class)->findBy(array('idAnnonce'=>$id));
        $list=array();
        $userManager = $this->get('fos_user.user_manager');
        for($i = 0; $i < count($jaime); ++$i){
            $userId=$jaime[$i]->getUser();
            $users = $userManager->findUserBy(array('id'=>$userId));
            $list[$i]=$users;
        }
        //var_dump($list);
        return $this->render('@Annonce/showUser.html.twig',array('list'=>$list));

    }


}
