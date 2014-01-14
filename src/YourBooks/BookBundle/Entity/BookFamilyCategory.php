<?php

namespace YourBooks\BookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * BookCategory
 *
 * @ORM\Table(name="book_family_category")
 * @ORM\Entity(repositoryClass="YourBooks\BookBundle\Entity\BookFamilyCategoryRepository")
 *
 */
class BookFamilyCategory
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
     * @ORM\OneToMany(targetEntity="YourBooks\BookBundle\Entity\BookCategory", mappedBy="familyCategory")
     */
    protected $categories;


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
    * @return BookFamilyCategory
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
     * Add categories
     *
     * @param \YourBooks\BookBundle\Entity\BookCategory $categories
     * @return BookFamilyCategory
     */
    public function addCategory(\YourBooks\BookBundle\Entity\BookCategory $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \YourBooks\BookBundle\Entity\BookCategory $categories
     */
    public function removeCategory(\YourBooks\BookBundle\Entity\Book $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}