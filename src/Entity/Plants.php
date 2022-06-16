<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\StringType;
use App\Repository\PlantsRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Filter\Validator\Length;
use App\Controller\PlantsCountController;
use App\Controller\PlantsPublishController;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=PlantsRepository::class)
 * @UniqueEntity("title")
 */
#[ApiResource(
    normalizationContext: [
        'groups' => ['read:Plants:collection', 'read:Plants'],
        'openapi_definition_name' => 'Collection'
    ],
    denormalizationContext: ['groups' => ['write:Plants']],
    paginationItemsPerPage: 6,
    paginationMaximumItemsPerPage: 6,
    paginationClientItemsPerPage: true,
    collectionOperations: [
        'get',
        'post',
        'count' => [
            'method' => 'GET',
            'path' => '/plants/count',
            'controller' => PlantsCountController::class,
            'filters' => [],
            'pagination_enabled' => false,
            'openapi_context' => [
                'summary' => 'Récupère le nombre total de plante',
                'parameters' => [
                    [
                    'in' => 'query',
                    'name' => 'online',
                    'schema' => [
                        'type' => 'integer',
                        'maximum' => 1,
                        'minimum' => 0
                    ],
                    'description' => 'Filtre les plantes en vente'
                    ]
                ],
                'reponses' => [
                    '200' => [
                        'description' => 'OK',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'integer',
                                    'example' => 3
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
        ],
    itemOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['read:Plants:collection', 'read:Plants:item', 'read:Plants']],
            'openapi_definition_name' => 'Detail'
            ]
        ]
        ),
    ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'title' => 'partial', 'color' => 'partial', 'type' => 'partial'])
    ]
class Plants
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['read:Plants:collection'])]
    private $id;

    /**
     * @Assert\Length(min = 2, max = 255)
     * @ORM\Column(type="string", length=255)
     */
    #[
        Groups(['read:Plants:collection', 'write:Plants']),
        Length(min: 3, groups: ['create:Plants'])
    ]
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    #[Groups(['read:Plants:collection', 'write:Plants'])]
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    #[Groups(['read:Plants:collection', 'write:Plants'])]
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['read:Plants:collection', 'write:Plants'])]
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['read:Plants:collection', 'write:Plants'])]
    private $color;

    /**
     * @ORM\Column(type="integer")
     */
    #[Groups(['read:Plants:item', 'write:Plants'])]
    private $quantity;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="boolean")
     */
    #[
        Groups(['read:Plants:collection']),
        ApiProperty(openapiContext: ['type' => 'boolean', 'description' => 'En vente'])
    ]
    private $online = false;

    /**
     * @ORM\OneToMany(targetEntity=Picture::class, mappedBy="plants", orphanRemoval=true, cascade={"persist"})
     */
    #[Groups(['read:Plants:collection'])]
    private $pictures;

    /**
     * @Assert\All({
     *  @Assert\Image(mimeTypes="image/jpeg")
     * })
     */
    private $pictureFiles;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
        $this->pictures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    #[Groups(['read:Plants:collection', 'write:Plants'])]
    public function getSlug() : string {
        return (new Slugify())->slugify($this->getTitle());
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getFormattedPrice(): string
    {
        return number_format($this->price, 0, '', ' ');
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getOnline(): ?bool
    {
        return $this->online;
    }

    public function setOnline(bool $online): self
    {
        $this->online = $online;

        return $this;
    }

    /**
     * @return Collection<int, Picture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }
  
    public function getPicture(): ?Picture
    {
        if ($this->pictures->isEmpty()) {
            return null;
        }
            return $this->pictures->first();
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setPlants($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getPlants() === $this) {
                $picture->setPlants(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPictureFiles()
    {
        return $this->pictureFiles;
    }

    /**
     * @param mixed $pictureFiles
     * @return Plants
     */
    public function setPictureFiles($pictureFiles): self
    {
        foreach($pictureFiles as $pictureFile)
        {
            $picture = new Picture();
            $picture->setImageFile($pictureFile);
            $this->addPicture($picture);
        }
        $this->pictureFiles = $pictureFiles;
        return $this;
    }


}
