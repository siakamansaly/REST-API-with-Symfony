<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TypeProductRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TypeProductRepository::class)
 * @ApiResource(
 *      attributes={
 *        "order"={"id": "ASC"},
 *        "security"="is_granted('ROLE_ADMIN')",
 *      },
 *      normalizationContext={"groups"={"read:type"}},
 *      denormalizationContext={"groups"={"create:type"}},
 *  collectionOperations={
 *      "get" ={
 *         "openapi_context"={"security"={{"bearerAuth"={}}}},
 *         "security"="is_granted('IS_AUTHENTICATED_FULLY')",
 *       },
 *      "post" = {
 *         "denormalization_context"={"groups"={"create:type"}},
 *         "openapi_context"={"security"={{"bearerAuth"={}}}}
 *       }
 *  },
 *  itemOperations={
 *      "get" = {
 *          "normalization_context"={"groups"={"read:type", "read:type:full"}},
 *          "openapi_context"={"security"={{"bearerAuth"={}}}},
 *          "security"="is_granted('IS_AUTHENTICATED_FULLY')",
 *       },
 *      "patch"= {
 *         "denormalization_context"={"groups"={"create:type"}},
 *         "openapi_context"={"security"={{"bearerAuth"={}}}}
 *       },
 *      "delete" = {
 *          "openapi_context"={"security"={{"bearerAuth"={}}}}
 *       }
 * }
 * )
 */
class TypeProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:product", "read:type"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:product", "create:product", "read:type", "create:type"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="typeProduct")
     * @Groups({"read:type:full"})
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setTypeProduct($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getTypeProduct() === $this) {
                $product->setTypeProduct(null);
            }
        }

        return $this;
    }
}
