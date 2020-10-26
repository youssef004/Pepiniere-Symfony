<?php


namespace AnnonceBundle\Entity;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 */
class annonce
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $Id;
    /**
     * @ORM\Column(type="string",length=255)
     */
    private $Titre;
    /**
     * @ORM\Column(type="string",length=255)
     **/
    private $Type;
    /**
     * @ORM\Column(type="date")
     **/
    private $DateAnnonce;
    /**
     * @ORM\Column(type="string",length=255)
     **/
    private $Description;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $imageName;
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->Id;
    }

    /**
     * @param mixed $Id
     */
    public function setId($Id)
    {
        $this->Id = $Id;
    }

    /**
     * @return mixed
     */
    public function getTitre()
    {
        return $this->Titre;
    }

    /**
     * @param mixed $Titre
     */
    public function setTitre($Titre)
    {
        $this->Titre = $Titre;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->Type;
    }

    /**
     * @param mixed $Type
     */
    public function setType($Type)
    {
        $this->Type = $Type;
    }

    /**
     * @return mixed
     */
    public function getDateAnnonce()
    {
        return $this->DateAnnonce;
    }

    /**
     * @param mixed $DateAnnonce
     */
    public function setDateAnnonce($DateAnnonce)
    {
        $this->DateAnnonce = $DateAnnonce;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->Description;
    }

    /**
     * @param mixed $Description
     */
    public function setDescription($Description)
    {
        $this->Description = $Description;
    }

    /**
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * @param string $imageName
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;
    }




}