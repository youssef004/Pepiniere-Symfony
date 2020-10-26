<?php


namespace AnnonceBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Extension\Core\DataTransformer\IntegerToLocalizedStringTransformer;


/**
 * @ORM\Entity
 */
class categorie
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $Id_categorieAnnonce;
    /**
     * @ORM\Column(type="string",length=255)
     */

    private $Type;
    /**
     * @ORM\Column(type="date")
     **/
    private $DateDebut;
    /**
     * @ORM\Column(type="date")
     **/
    private $Datefin;
    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $nbrParticipant;
    /**
     * @ORM\OneToOne(targetEntity="annonce")
     * @ORM\JoinColumn(name="idAnnonce", referencedColumnName="id")
     */
    private $idAnnonce;
    /**
     * @return mixed
     */
    public function getIdCategorieAnnonce()
    {
        return $this->Id_categorieAnnonce;
    }

    /**
     * @param mixed $Id_categorieAnnonce
     */
    public function setIdCategorieAnnonce($Id_categorieAnnonce)
    {
        $this->Id_categorieAnnonce = $Id_categorieAnnonce;
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
    public function getDateDebut()
    {
        return $this->DateDebut;
    }

    /**
     * @param mixed $DateDebut
     */
    public function setDateDebut($DateDebut)
    {
        $this->DateDebut = $DateDebut;
    }

    /**
     * @return mixed
     */
    public function getDatefin()
    {
        return $this->Datefin;
    }

    /**
     * @param mixed $Datefin
     */
    public function setDatefin($Datefin)
    {
        $this->Datefin = $Datefin;
    }

    /**
     * @return int
     */
    public function getNbrParticipant()
    {
        return $this->nbrParticipant;
    }

    /**
     * @param int $nbrParticipant
     */
    public function setNbrParticipant($nbrParticipant)
    {
        $this->nbrParticipant = $nbrParticipant;
    }

    /**
     * @return mixed
     */
    public function getIdAnnonce()
    {
        return $this->idAnnonce;
    }

    /**
     * @param mixed $idAnnonce
     */
    public function setIdAnnonce($idAnnonce)
    {
        $this->idAnnonce = $idAnnonce;
    }

}