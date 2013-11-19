<?php

namespace YourBooks\BookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use YourBooks\UserBundle\Entity\User;
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
     * @var User
     */
    protected $reader;

    /**
     * @var Book
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
}
