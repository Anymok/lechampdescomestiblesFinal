<?php

namespace App\Entity;

use App\Entity\Plants;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PictureRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation As Vich;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass=PictureRepository::class)
 * @Vich\Uploadable()
 */
#[ApiResource()]
class Picture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['read:Plants'])]
    private $filename;

    
    /**
     * @var File|null
     * @Assert\Image(
     *      mimeTypes="image/jpeg"
     * )
     * @Vich\UploadableField(mapping="plants_image", fileNameProperty="filename")
     * 
     */
    private $imageFile;

    /**
     * @ORM\ManyToOne(targetEntity=Plants::class, inversedBy="pictures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $plants;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getPlants(): ?Plants
    {
        return $this->plants;
    }

    public function setPlants(?Plants $plants): self
    {
        $this->plants = $plants;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): self
    {
        $this->imageFile = $imageFile;
        return $this;
    }
}
