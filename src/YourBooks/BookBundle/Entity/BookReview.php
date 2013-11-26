<?php

namespace YourBooks\BookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use YourBooks\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use YourBooks\BookBundle\Entity\Book;

/**
 * BookReview
 *
 * @ORM\Table(name="book_review")
 * @ORM\Entity(repositoryClass="YourBooks\BookBundle\Entity\BookReviewRepository")
 */
class BookReview
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
     * @ORM\Column(name="summary", type="text")
     */
    protected $summary;

    /**
     * @var integer
     *
     * @ORM\Column(name="criteria_1", type="integer")
     */
    protected $criteria1;

    /**
     * @var integer
     *
     * @ORM\Column(name="criteria_2", type="integer")
     */
    protected $criteria2;

    /**
     * @var integer
     *
     * @ORM\Column(name="criteria_3", type="integer")
     */
    protected $criteria3;

    /**
     * @var integer
     *
     * @ORM\Column(name="criteria_4", type="integer")
     */
    protected $criteria4;

    /**
     * @var integer
     *
     * @ORM\Column(name="criteria_5", type="integer")
     */
    protected $criteria5;

    /**
     * @var string
     *
     * @ORM\Column(name="critic", type="string", length=200)
     */
    protected $critic;

    /**
     * @var string
     *
     * @ORM\Column(name="problems", type="text", nullable=true)
     */
    protected $problems;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Application\Sonata\UserBundle\Entity\User", cascade={"persist"})
     */
    protected $reader;

    /**
     * @var Book
     *
     * @ORM\OneToOne(targetEntity="YourBooks\BookBundle\Entity\Book", inversedBy="review", cascade={"persist"})
     */
    protected $book;

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
     * Set summary
     *
     * @param string $summary
     * @return BookReview
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    
        return $this;
    }

    /**
     * Get summary
     *
     * @return string 
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set criteria1
     *
     * @param integer $criteria1
     * @return BookReview
     */
    public function setCriteria1($criteria1)
    {
        $this->criteria1 = $criteria1;
    
        return $this;
    }

    /**
     * Get criteria1
     *
     * @return integer 
     */
    public function getCriteria1()
    {
        return $this->criteria1;
    }

    /**
     * Set critic
     *
     * @param string $critic
     * @return BookReview
     */
    public function setCritic($critic)
    {
        $this->critic = $critic;
    
        return $this;
    }

    /**
     * Get critic
     *
     * @return string 
     */
    public function getCritic()
    {
        return $this->critic;
    }

    /**
     * Set problems
     *
     * @param string $problems
     * @return BookReview
     */
    public function setProblems($problems)
    {
        $this->problems = $problems;
    
        return $this;
    }

    /**
     * Get problems
     *
     * @return string 
     */
    public function getProblems()
    {
        return $this->problems;
    }

    /**
     * Set criteria2
     *
     * @param integer $criteria2
     * @return BookReview
     */
    public function setCriteria2($criteria2)
    {
        $this->criteria2 = $criteria2;
    
        return $this;
    }

    /**
     * Get criteria2
     *
     * @return integer 
     */
    public function getCriteria2()
    {
        return $this->criteria2;
    }

    /**
     * Set criteria3
     *
     * @param integer $criteria3
     * @return BookReview
     */
    public function setCriteria3($criteria3)
    {
        $this->criteria3 = $criteria3;
    
        return $this;
    }

    /**
     * Get criteria3
     *
     * @return integer 
     */
    public function getCriteria3()
    {
        return $this->criteria3;
    }

    /**
     * Set criteria4
     *
     * @param integer $criteria4
     * @return BookReview
     */
    public function setCriteria4($criteria4)
    {
        $this->criteria4 = $criteria4;
    
        return $this;
    }

    /**
     * Get criteria4
     *
     * @return integer 
     */
    public function getCriteria4()
    {
        return $this->criteria4;
    }

    /**
     * Set criteria5
     *
     * @param integer $criteria5
     * @return BookReview
     */
    public function setCriteria5($criteria5)
    {
        $this->criteria5 = $criteria5;
    
        return $this;
    }

    /**
     * Get criteria5
     *
     * @return integer 
     */
    public function getCriteria5()
    {
        return $this->criteria5;
    }

    /**
     * Set book
     *
     * @param \YourBooks\BookBundle\Entity\Book $book
     * @return BookReview
     */
    public function setBook(\YourBooks\BookBundle\Entity\Book $book = null)
    {
        $this->book = $book;
    
        return $this;
    }

    /**
     * Get book
     *
     * @return \YourBooks\BookBundle\Entity\Book 
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * Set reader
     *
     * @param \Application\Sonata\UserBundle\Entity\User $reader
     * @return BookReview
     */
    public function setReader(\Application\Sonata\UserBundle\Entity\User $reader = null)
    {
        $this->reader = $reader;
    
        return $this;
    }

    /**
     * Get reader
     *
     * @return \Application\Sonata\UserBundle\Entity\User 
     */
    public function getReader()
    {
        return $this->reader;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reader = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add reader
     *
     * @param \Application\Sonata\UserBundle\Entity\User $reader
     * @return BookReview
     */
    public function addReader(\Application\Sonata\UserBundle\Entity\User $reader)
    {
        $this->reader[] = $reader;
    
        return $this;
    }

    /**
     * Remove reader
     *
     * @param \Application\Sonata\UserBundle\Entity\User $reader
     */
    public function removeReader(\Application\Sonata\UserBundle\Entity\User $reader)
    {
        $this->reader->removeElement($reader);
    }
}