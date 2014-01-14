<?php

namespace YourBooks\BookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * BookCategory
 *
 * @ORM\Table(name="book_category")
 * @ORM\Entity(repositoryClass="YourBooks\BookBundle\Entity\BookCategoryRepository")
 * @ORM\HasLifecycleCallbacks
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
     * @Assert\File(
     *     maxSize = "2M",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png"},
     *     mimeTypesMessage = "Le fichier choisi ne correspond pas à un fichier valide",
     *     notFoundMessage = "Le fichier n'a pas été trouvé sur le disque",
     *     uploadErrorMessage = "Erreur dans l'upload du fichier"
     * )
     */
    protected $file;

    /**
     * @var string
     *
     * @ORM\Column(name="file_path", type="string", length=255)
     */
    protected $fileName;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="YourBooks\BookBundle\Entity\Book", mappedBy="category")
     */
    protected $books;

    /**
     * @var BookFamilyCategory
     *
     * @ORM\ManyToOne(targetEntity="YourBooks\BookBundle\Entity\BookFamilyCategory", inversedBy="categories", cascade={"persist"})
     */
    protected $familyCategory;

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
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp  = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }


    /**
     * @return null|string
     */
    public function getAbsolutePath()
    {
        return null === $this->fileName ? null : $this->getUploadRootDir().'/'.$this->fileName;
    }

    /**
     * @return null|string
     */
    public function getWebPath()
    {
        return null === $this->fileName ? null : $this->getUploadDir().'/'.$this->fileName;
    }

    /**
     * @return string
     */
    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    /**
     * @return string
     */
    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/images';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->fileName = $filename.'.'.$this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->fileName);

        // check if we have an old file path
        if (isset($this->temp)) {
            // check if we have a true path
            if (is_file($filePath = $this->getUploadRootDir().'/'.$this->temp)) {
                // delete the old file
                unlink($filePath);
                // clear the temp file path
                $this->temp = null;
            }
        }
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
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
     * Set familyCategory
     *
     * @param \YourBooks\BookBundle\Entity\BookFamilyCategory $familyCategory
     * @return BookCategory
     */
    public function setFamilyCategory(\YourBooks\BookBundle\Entity\BookFamilyCategory $familyCategory = null)
    {
        $this->familyCategory = $familyCategory;
        $familyCategory->addCategory($this);
        return $this;
    }

    /**
     * Get FamilyCategory
     *
     * @return \YourBooks\BookBundle\Entity\BookFamilyCategory
     */
    public function getFamilyCategory()
    {
        return $this->familyCategory;
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