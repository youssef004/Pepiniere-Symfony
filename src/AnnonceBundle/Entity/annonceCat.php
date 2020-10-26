<?php


namespace AnnonceBundle\Entity;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 */
class annonceCat
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $IdannonceCat;

    /**
     * @ORM\Column(type="integer",nullable=false)
     * @var integer
     */
    private $idCategorie;
    /**
     * @ORM\Column(type="integer",nullable=false)
     * @var integer
     */
    private $User;

    /**
     * @return mixed
     */
    public function getIdannonceCat()
    {
        return $this->IdannonceCat;
    }

    /**
     * @param mixed $IdannonceCat
     */
    public function setIdannonceCat($IdannonceCat)
    {
        $this->IdannonceCat = $IdannonceCat;
    }

    /**
     * @return int
     */
    public function getIdCategorie()
    {
        return $this->idCategorie;
    }

    /**
     * @param int $idCategorie
     */
    public function setIdCategorie($idCategorie)
    {
        $this->idCategorie = $idCategorie;
    }

    /**
     * @return int
     */
    public function getUser()
    {
        return $this->User;
    }

    /**
     * @param int $User
     */
    public function setUser($User)
    {
        $this->User = $User;
    }








}