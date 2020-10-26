<?php


namespace ProduitBundle\Entity;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity

 */
class jaimeProd
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private  $idjaime;
    /**
     * @ORM\Column(type="string",length=255)
     *
     */
    private  $userP;

    /**
     * @ORM\Column(type="integer")
     *
     */
    private  $idProd;

    /**
     * @return mixed
     */
    public function getIdjaime()
    {
        return $this->idjaime;
    }

    /**
     * @param mixed $idjaime
     */
    public function setIdjaime($idjaime)
    {
        $this->idjaime = $idjaime;
    }

    /**
     * @return mixed
     */
    public function getUserP()
    {
        return $this->userP;
    }

    /**
     * @param mixed $userP
     */
    public function setUserP($userP)
    {
        $this->userP = $userP;
    }

    /**
     * @return mixed
     */
    public function getIdProd()
    {
        return $this->idProd;
    }

    /**
     * @param mixed $idProd
     */
    public function setIdProd($idProd)
    {
        $this->idProd = $idProd;
    }











}