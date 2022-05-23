<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ApiResource(
 *      attributes={
 *        "order"={"id": "ASC"}
 *      },
 *      normalizationContext={"groups"={"read:product"}},
 *      denormalizationContext={"groups"={"create:product"}},
 *  collectionOperations={
 *      "get" = {"normalization_context"={"groups"={"read:product"}},
 *         "openapi_context"={"security"={{"bearerAuth"={}}}, "summary"="List of all BileMo products"},
 *         "security"="is_granted('IS_AUTHENTICATED_FULLY')",
 *         "security_message"="You must be logged in to access this resource",
 *      },
 *      "post" = {
 *         "denormalization_context"={"groups"={"create:product"}},
 *         "controller" = App\Controller\Api\AlreadyExistsController::class,
 *         "openapi_context"={"security"={{"bearerAuth"={}}}, "summary"="Admin - Create a new product resource of BileMo"},
 *         "security"="is_granted('ROLE_ADMIN')",
 *         "security_message"="Only admins can add products",
 *       }
 *  },
 *  itemOperations={
 *      "get"={"normalization_context"={"groups"={"read:product", "read:product:full"}},
 *         "openapi_context"={"security"={{"bearerAuth"={}}}, "summary"="Get a product resource of BileMo"},
 *         "security"="is_granted('IS_AUTHENTICATED_FULLY')",
 *         "security_message"="You must be logged in to access this resource",
 *       },
 *      "patch"= {
 *         "normalization_context"={"groups"={"read:product"}},
 *         "denormalization_context"={"groups"={"create:product"}},
 *         "controller" = App\Controller\Api\AlreadyExistsController::class,
 *         "openapi_context"={"security"={{"bearerAuth"={}}}, "summary"="Admin - Update a product resource of BileMo"},
 *         "security"="is_granted('ROLE_ADMIN')",
 *         "security_message"="Only admins can edit products",
 *       },
 *      "delete" = {
 *         "openapi_context"={"security"={{"bearerAuth"={}}}, "summary"="Admin - Delete a product resource of BileMo"},
 *         "security"="is_granted('ROLE_ADMIN')",
 *         "security_message"="Only admins can delete products",
 *      }
 * }
 * )
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:product"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"read:product"})
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"read:product", "create:product"})
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"read:product", "create:product"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read:product", "create:product"})
     */
    private $coverImage;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read:product:full"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"read:product", "create:product"})
     */
    private $price;

    /**
     * ApiProperty()
     * @Groups({"read:product"})
     */
    private $currency;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"read:product"})
     */
    private $stock;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="products")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=TypeProduct::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read:product", "create:product"})
     */
    private $typeProduct;

    /**
     * @ORM\OneToMany(targetEntity=MediaPicture::class, mappedBy="product", orphanRemoval=true)
     * @Groups({"read:product:full"})
     */
    private $mediaPictures;

    public function __construct()
    {
        $this->mediaPictures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(?string $coverImage): self
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTypeProduct(): ?TypeProduct
    {
        return $this->typeProduct;
    }

    public function setTypeProduct(?TypeProduct $typeProduct): self
    {
        $this->typeProduct = $typeProduct;

        return $this;
    }

    /**
     * @return Collection<int, MediaPicture>
     */
    public function getMediaPictures(): Collection
    {
        return $this->mediaPictures;
    }

    public function addMediaPicture(MediaPicture $mediaPicture): self
    {
        if (!$this->mediaPictures->contains($mediaPicture)) {
            $this->mediaPictures[] = $mediaPicture;
            $mediaPicture->setProduct($this);
        }

        return $this;
    }

    public function removeMediaPicture(MediaPicture $mediaPicture): self
    {
        if ($this->mediaPictures->removeElement($mediaPicture)) {
            // set the owning side to null (unless already changed)
            if ($mediaPicture->getProduct() === $this) {
                $mediaPicture->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * Get currency()
     */
    public function getCurrency() : ?string
    {
        return "EUR";
    }
}
