<?php

namespace YourBooks\BookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Mapping\Annotation as Gedmo;
use YourBooks\PaymentBundle\Entity\Payment;

/**
 * Book
 *
 * @ORM\Table(name="book")
 * @ORM\Entity(repositoryClass="YourBooks\BookBundle\Entity\BookRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Book
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="summary", type="text")
     */
    protected $summary;

    /**
     * @Assert\File(
     *      mimeTypes={"application/pdf", "application/x-pdf"},
     *      mimeTypesMessage="Only PDF"
     * )
     */
    protected $file;

    /**
     * @var bool
     *
     * @ORM\Column(name="payed", type="boolean", nullable=true)
     */
    protected $payed;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="payed_at", type="datetime", nullable=true)
     */
    protected $payedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="file_path", type="string", length=255)
     */
    protected $fileName;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="send_to_reader_at", type="datetime", nullable=true)
     */
    protected $sendToReaderAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="retracted", type="boolean", nullable=true)
     */
    protected $retracted;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     */
    protected $enabled;

    /**
     * @var bool
     *
     * @ORM\Column(name="received_by_reader", type="boolean", nullable=true)
     */
    protected $receivedByReader;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="received_by_reader_at", type="datetime", nullable=true)
     */
    protected $receivedByReaderAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="download_by_reader", type="boolean", nullable=true)
     */
    protected $downloadByReader;

    /**
     * @var bool
     *
     * @ORM\Column(name="send_by_reader", type="boolean", nullable=true)
     */
    protected $sendByReader;

    /**
     * @var bool
     *
     * @ORM\Column(name="reader_validation", type="boolean", nullable=true)
     */
    protected $readerValidation;

    /**
     * @var bool
     *
     * @ORM\Column(name="edited", type="boolean", nullable=true)
     */
    protected $edited;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     */
    protected $author;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", cascade={"persist"})
     */
    protected $reader;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     */
    protected $editor;

    /**
     * @var BookCategory
     *
     * @ORM\ManyToOne(targetEntity="YourBooks\BookBundle\Entity\BookCategory", inversedBy="books", cascade={"persist"})
     */
    protected $category;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="YourBooks\BookBundle\Entity\BookStatus", mappedBy="book")
     */
    protected $statuss;

    /**
     * @var BookReview
     *
     * @ORM\OneToOne(targetEntity="YourBooks\BookBundle\Entity\BookReview", mappedBy="book", cascade={"remove"})
     */
    protected $review;

    /**
     * @var Payment
     *
     * @ORM\OneToOne(targetEntity="YourBooks\PaymentBundle\Entity\Payment", cascade={"persist"})
     */
    protected $payment;

    private $temp;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->retracted = false;
        $this->enabled = false;
        $this->receivedByReader = false;
        $this->downloadByReader = false;
        $this->sendByReader = false;
        $this->readerValidation = false;
        $this->edited = false;
    }

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
     * @return Book
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
     * Set summary
     *
     * @param string $summary
     * @return Book
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Book
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Book
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set retracted
     *
     * @param boolean $retracted
     * @return Book
     */
    public function setRetracted($retracted)
    {
        $this->retracted = $retracted;

        return $this;
    }

    /**
     * Get retracted
     *
     * @return boolean
     */
    public function getRetracted()
    {
        return $this->retracted;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Book
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    
        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set receivedByReader
     *
     * @param boolean $receivedByReader
     * @return Book
     */
    public function setReceivedByReader($receivedByReader)
    {
        $this->receivedByReader = $receivedByReader;

        return $this;
    }

    /**
     * Get receivedByReader
     *
     * @return boolean
     */
    public function getReceivedByReader()
    {
        return $this->receivedByReader;
    }

    /**
     * Set downloadByReader
     *
     * @param boolean $downloadByReader
     * @return Book
     */
    public function setDownloadByReader($downloadByReader)
    {
        $this->downloadByReader = $downloadByReader;

        return $this;
    }

    /**
     * Get downloadByReader
     *
     * @return boolean
     */
    public function getDownloadByReader()
    {
        return $this->downloadByReader;
    }


    /**
     * Set sendByReader
     *
     * @param boolean $sendByReader
     * @return Book
     */
    public function setSendByReader($sendByReader)
    {
        $this->sendByReader = $sendByReader;

        return $this;
    }

    /**
     * Get sendByReader
     *
     * @return boolean
     */
    public function getSendByReader()
    {
        return $this->sendByReader;
    }

    /**
     * Set readerValidation
     *
     * @param boolean $readerValidation
     * @return Book
     */
    public function setReaderValidation($readerValidation)
    {
        $this->readerValidation = $readerValidation;
    
        return $this;
    }

    /**
     * Get readerValidation
     *
     * @return boolean 
     */
    public function getReaderValidation()
    {
        return $this->readerValidation;
    }

    /**
     * Set edited
     *
     * @param boolean $edited
     * @return Book
     */
    public function setEdited($edited)
    {
        $this->edited = $edited;
    
        return $this;
    }

    /**
     * Get edited
     *
     * @return boolean 
     */
    public function getEdited()
    {
        return $this->edited;
    }

    /**
     * Set author
     *
     * @param \Application\Sonata\UserBundle\Entity\User $author
     * @return Book
     */
    public function setAuthor(\Application\Sonata\UserBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }


    /**
     * Set reader
     *
     * @param \Application\Sonata\UserBundle\Entity\User $reader
     * @internal param \Application\Sonata\UserBundle\Entity\User $editor
     * @return Book
     */
    public function setReader(\Application\Sonata\UserBundle\Entity\User $reader = null)
    {
        $this->reader = $reader;
        if (null !== $reader)
            $reader->addBook($this);
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
     * Set editor
     *
     * @param \Application\Sonata\UserBundle\Entity\User $editor
     * @return Book
     */
    public function setEditor(\Application\Sonata\UserBundle\Entity\User $editor = null)
    {
        $this->editor = $editor;

        return $this;
    }

    /**
     * Get editor
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getEditor()
    {
        return $this->editor;
    }

    /**
     * Set category
     *
     * @param \YourBooks\BookBundle\Entity\BookCategory $category
     * @return Book
     */
    public function setCategory(\YourBooks\BookBundle\Entity\BookCategory $category = null)
    {
        $this->category = $category;
        $category->addBook($this);
        return $this;
    }

    /**
     * Get category
     *
     * @return \YourBooks\BookBundle\Entity\BookCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set review
     *
     * @param \YourBooks\BookBundle\Entity\BookReview $review
     * @return Book
     */
    public function setReview(\YourBooks\BookBundle\Entity\BookReview $review = null)
    {
        $this->review = $review;

        return $this;
    }

    /**
     * Get review
     *
     * @return \YourBooks\BookBundle\Entity\BookReview
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * Add statuss
     *
     * @param \YourBooks\BookBundle\Entity\BookStatus $statuss
     * @return Book
     */
    public function addStatus(\YourBooks\BookBundle\Entity\BookStatus $statuss)
    {
        $this->statuss[] = $statuss;

        return $this;
    }

    /**
     * Remove statuss
     *
     * @param \YourBooks\BookBundle\Entity\BookStatus $statuss
     */
    public function removeStatus(\YourBooks\BookBundle\Entity\BookStatus $statuss)
    {
        $this->statuss->removeElement($statuss);
    }

    /**
     * Get statuss
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStatuss()
    {
        return $this->statuss;
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
        return 'uploads/books';
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
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }

    /**
     * Set sendToReaderAt
     *
     * @param \DateTime $sendToReaderAt
     * @return Book
     */
    public function setSendToReaderAt($sendToReaderAt)
    {
        $this->sendToReaderAt = $sendToReaderAt;
        return $this;
    }

    /**
     * Get sendToReaderAt
     *
     * @return \DateTime
     */
    public function getSendToReaderAt()
    {
        return $this->sendToReaderAt;
    }

    /**
     * Set receivedByReaderAt
     *
     * @param \DateTime $receivedByReaderAt
     * @return Book
     */
    public function setReceivedByReaderAt($receivedByReaderAt)
    {
        $this->receivedByReaderAt = $receivedByReaderAt;
        return $this;
    }

    /**
     * Get receivedByReaderAt
     *
     * @return \DateTime
     */
    public function getReceivedByReaderAt()
    {
        return $this->receivedByReaderAt;
    }


    /**
     * Set payed
     *
     * @param boolean $payed
     * @return Book
     */
    public function setPayed($payed)
    {
        $this->payed = $payed;

	    $this->payedAt = new \DateTime("now");

        return $this;
    }

    /**
     * Get payed
     *
     * @return boolean 
     */
    public function getPayed()
    {
        return $this->payed;
    }

    /**
     * Set payedAt
     *
     * @param \DateTime $payedAt
     * @return Book
     */
    public function setPayedAt($payedAt)
    {
        $this->payedAt = $payedAt;
    
        return $this;
    }

    /**
     * Get payedAt
     *
     * @return \DateTime 
     */
    public function getPayedAt()
    {
        return $this->payedAt;
    }

    /**
     * @param \YourBooks\PaymentBundle\Entity\Payment $payment
     * @return $this
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * @return \YourBooks\PaymentBundle\Entity\Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }
}