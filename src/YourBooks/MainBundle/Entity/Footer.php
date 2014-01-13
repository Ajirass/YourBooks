<?php

namespace YourBooks\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Footer
 *
 * @ORM\Table(name="footer")
 * @ORM\Entity(repositoryClass="YourBooks\MainBundle\Entity\FooterRepository")
 */
class Footer
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="presse", type="text")
     */
    protected $presse;

    /**
     * @var string
     *
     * @ORM\Column(name="team", type="text")
     */
    protected $team;

    /**
     * @var string
     *
     * @ORM\Column(name="charte", type="text")
     */
    protected $charte;

    /**
     * @var string
     *
     * @ORM\Column(name="engagements", type="text")
     */
    protected $engagements;


    /**
     * @var string
     *
     * @ORM\Column(name="partenaires", type="text")
     */
    protected $partenaires;


    /**
     * @var string
     *
     * @ORM\Column(name="mentionslegales", type="text")
     */
    protected $mentionslegales;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set presse
     *
     * @param string $presse
     * @return Footer
     */
    public function setPresse($presse)
    {
        $this->presse = $presse;
    
        return $this;
    }

    /**
     * Get presse
     *
     * @return string 
     */
    public function getPresse()
    {
        return $this->presse;
    }

    /**
     * Set team
     *
     * @param string $team
     * @return Footer
     */
    public function setTeam($team)
    {
        $this->team = $team;
    
        return $this;
    }

    /**
     * Get team
     *
     * @return string 
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Set charte
     *
     * @param string $charte
     * @return Footer
     */
    public function setCharte($charte)
    {
        $this->charte = $charte;
    
        return $this;
    }

    /**
     * Get charte
     *
     * @return string 
     */
    public function getCharte()
    {
        return $this->charte;
    }

    /**
     * Set engagements
     *
     * @param string $engagements
     * @return Footer
     */
    public function setEngagements($engagements)
    {
        $this->engagements = $engagements;
    
        return $this;
    }

    /**
     * Get engagements
     *
     * @return string 
     */
    public function getEngagements()
    {
        return $this->engagements;
    }

    /**
     * Set partenaires
     *
     * @param string $partenaires
     * @return Footer
     */
    public function setPartenaires($partenaires)
    {
        $this->partenaires = $partenaires;
    
        return $this;
    }

    /**
     * Get partenaires
     *
     * @return string 
     */
    public function getPartenaires()
    {
        return $this->partenaires;
    }

    /**
     * Set mentionslegales
     *
     * @param string $mentionslegales
     * @return Footer
     */
    public function setMentionslegales($mentionslegales)
    {
        $this->mentionslegales = $mentionslegales;
    
        return $this;
    }

    /**
     * Get mentionslegales
     *
     * @return string 
     */
    public function getMentionslegales()
    {
        return $this->mentionslegales;
    }
}