<?php

namespace YourBooks\BookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * BookCategory
 *
 * @ORM\Table(name="book_category")
 * @ORM\Entity(repositoryClass="YourBooks\BookBundle\Entity\BookCategoryRepository")
 */
class BookCategory
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="color_category", type="string", length=255)
     */
    private $colorCategory;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="YourBooks\BookBundle\Entity\Book", mappedBy="category")
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
     * Set name
     *
     * @param string $name
     * @return BookCategory
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set colorCategory
     *
     * @param string $colorCategory
     * @return BookCategory
     */
    public function setColorCategory($colorCategory)
    {
        $this->colorCategory = $colorCategory;

        return $this;
    }

    /**
     * Get colorCategory
     *
     * @return string
     */
    public function getColorCategory()
    {
        return $this->colorCategory;
    }

    /**
     * Add books
     *
     * @param \YourBooks\BookBundle\Entity\Book $books
     * @return BookCategory
     */
    public function addBook(\YourBooks\BookBundle\Entity\Book $books)
    {
        $this->books[] = $books;
    
        return $this;
    }

    /**
     * Remove books
     *
     * @param \YourBooks\BookBundle\Entity\Book $books
     */
    public function removeBook(\YourBooks\BookBundle\Entity\Book $books)
    {
        $this->books->removeElement($books);
    }

    /**
     * Get books
     *
     * @return \Doctrine\Common\Collections\Collection 
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
        $this->books = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

}