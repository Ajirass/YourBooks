<?php

namespace YourBooks\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * Espace Presse
 *
 * @ORM\Table(name="espace_presse")
 * @ORM\Entity(repositoryClass="YourBooks\MainBundle\Entity\EspacePresseRepository")
 * @ORM\HasLifecycleCallbacks
 */
class EspacePresse
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
     * @ORM\Column(name="content", type="text")
     */
    protected $content;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    protected $url;


    /**
     * @Assert\File(
     *     maxSize = "2M",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png"},
     *     mimeTypesMessage = "Le fichier choisi ne correspond pas à un fichier valide",
     *     notFoundMessage = "Le fichier n'a pas été trouvé sur le disque",
     *     uploadErrorMessage = "Erreur dans l'upload du fichier"
     * )
     */
    protected $image;

    /**
     * @var string
     *
     * @ORM\Column(name="image_path", type="string", length=255, nullable=true)
     */
    protected $imageName;

    /**
     * @Assert\File(
     *      mimeTypes={"application/pdf", "application/x-pdf"},
     *      mimeTypesMessage="Only PDF"
     * )
     */
    protected $file;

    /**
     * @var string
     *
     * @ORM\Column(name="file_path", type="string", length=255, nullable=true)
     */
    protected $fileName;

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
     * @return EspacePresse
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
     * Set content
     *
     * @param string $content
     * @return EspacePresse
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return EspacePresse
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets image.
     *
     * @param UploadedFile $image
     */
    public function setImage(UploadedFile $image = null)
    {
        $this->image = $image;
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
     * Get image.
     *
     * @return UploadedFile
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $imageName
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;
    }

    /**
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }


    /**
     * @return null|string
     */
    public function getImageAbsolutePath()
    {
        return null === $this->imageName ? null : $this->getImageUploadRootDir().'/'.$this->imageName;
    }

    /**
     * @return null|string
     */
    public function getImageWebPath()
    {
        return null === $this->imageName ? null : $this->getImageUploadDir().'/'.$this->imageName;
    }

    /**
     * @return string
     */
    protected function getImageUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getImageUploadDir();
    }

    /**
     * @return string
     */
    protected function getImageUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/footer';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preImageUpload()
    {
        if (null !== $this->getImage()) {
            // do whatever you want to generate a unique name
            $imagename = sha1(uniqid(mt_rand(), true));
            $this->imageName = $imagename.'.'.$this->getImage()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function uploadImage()
    {
        if (null === $this->getImage()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getImage()->move($this->getImageUploadRootDir(), $this->imageName);

        // check if we have an old file path
        if (isset($this->temp)) {
            // check if we have a true path
            if (is_file($imagePath = $this->getImageUploadRootDir().'/'.$this->temp)) {
                // delete the old file
                unlink($imagePath);
                // clear the temp file path
                $this->temp = null;
            }
        }
        $this->image = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeImageUpload()
    {
        if ($image = $this->getImageAbsolutePath()) {
            unlink($image);
        }
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
        return 'uploads/footer';
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

}