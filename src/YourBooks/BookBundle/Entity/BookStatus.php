<?php

namespace YourBooks\BookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use YourBooks\UserBundle\Entity\User;

/**
 * BookStatus
 *
 * @ORM\Table(name="book_status")
 * @ORM\Entity(repositoryClass="YourBooks\BookBundle\Entity\BookStatusRepository")
 */
class BookStatus
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=25)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     */
    private $user;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToOne(targetEntity="YourBooks\BookBundle\Entity\Book", inversedBy="statuss")
     */
    protected $books;


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
     * Set title
     *
     * @param string $title
     * @return BookStatus
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return BookStatus
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return BookStatus
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }
    
    /**
     * Add user
     *
     * @param \Application\Sonata\UserBundle\Entity\User $user
     * @return BookStatus
     */
    public function addUser(\Application\Sonata\UserBundle\Entity\User $user)
    {
        $this->user[] = $user;
    
        return $this;
    }

    /**
     * Remove user
     *
     * @param \Application\Sonata\UserBundle\Entity\User $user
     */
    public function removeUser(\Application\Sonata\UserBundle\Entity\User $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param \Application\Sonata\UserBundle\Entity\User $user
     * @return BookStatus
     */
    public function setUser(\Application\Sonata\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Set books
     *
     * @param \YourBooks\BookBundle\Entity\Book $books
     * @return BookStatus
     */
    public function setBooks(\YourBooks\BookBundle\Entity\Book $books = null)
    {
        $this->books = $books;

        return $this;
    }

    /**
     * Get books
     *
     * @return \YourBooks\BookBundle\Entity\Book
     */
    public function getBooks()
    {
        return $this->books;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
    }
}