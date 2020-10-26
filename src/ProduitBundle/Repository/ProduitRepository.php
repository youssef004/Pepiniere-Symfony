<?php
namespace ProduitBundle\Repository;
use Doctrine\ORM\Mapping as ORM;

class ProduitRepository extends \Doctrine\ORM\EntityRepository {
    public function getProduitByPrix(){
        $query = $this->getEntityManager()->createQuery("SELECT a from ProduitBundle:Produit a ORDER By a.PriceProduit ASC ");
        return $query->getResult();
    }

    public function getProduitByPrixDESC(){
        $query = $this->getEntityManager()->createQuery("SELECT a from ProduitBundle:Produit a ORDER By a.PriceProduit DESC ");
        return $query->getResult();
    }





}