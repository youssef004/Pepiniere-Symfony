<?php

namespace ProduitBundle\Controller;




use ProduitBundle\Entity\CategoryProduit;
use ProduitBundle\Entity\jaimeProd;
use ProduitBundle\Entity\Produit;
use ProduitBundle\Form\jaimeProdType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use UserBundle\Entity\User;


class ProduitMobileController extends Controller
{
    public function LoginMobileAction($email){
        $user=$this->getDoctrine()->getRepository(User::class)->findBy(array('email'=>$email));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($user);
        return new JsonResponse($formatted);

    }
    public function showProdMobileAction(){
        $produits = $this->getDoctrine()->getRepository(Produit::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($produits);
        return new JsonResponse($formatted);

    }
    public function showCategProdMobileAction($id){
       $categ=$this->getDoctrine()->getRepository(CategoryProduit::class)->find($id);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($categ);
        return new JsonResponse($formatted);
    }
    public function highPriceMobileAction()
    {

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Produit::class)->findBy([], ['PriceProduit' => 'DESC']);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($rep);
        return new JsonResponse($formatted);
    }
    public function deleteProdMobileAction($IdProduit)
    {
        $em = $this->getDoctrine()->getManager();
        $prod = $em->getRepository(Produit::class)->find($IdProduit);

        $em->remove($prod);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($em);
        return new JsonResponse($formatted);
    }
    public function AjouterCompteMobileAction(Request $request)

    {   $em = $this->getDoctrine()->getManager();
        $User = new User();
        $User->setUsername($request->get('username'));
        $User->setPassword($request->get('password'));
        $User->setEmail($request->get('email'));
        $em->persist($User);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($User);
        return new JsonResponse($formatted);


    }

    public function jaimemobileAction(Request $request){
        $user1=$request->get('Iduser');
        $user = (int)$user1;

        $id1=$request->get('idAnnonce');
        $id=(int)$id1;

        $jaime= new jaimeProd();

        $f=$this->createForm(jaimeProdType::class, $jaime);



        $jaime1=$this->getDoctrine()->getRepository( jaimeProd::class)->findOneBy(array('idProd'=>$id,'userP'=>$user));

        if($jaime1==null){

            $jaime->setIdProd($id);

            $jaime->setUserP($user);

            $em= $this->getDoctrine()->getManager();
            $em->persist($jaime);

            $em->flush();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize($jaime);
            return new JsonResponse($formatted);




        }else{


            $em= $this->getDoctrine()->getManager();
            $em->remove($jaime1);
            $em->flush();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize(null);
            return new JsonResponse($formatted);





        }


    }

}
