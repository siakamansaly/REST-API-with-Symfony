<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MediaPictureRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=MediaPictureRepository::class)
 * @ApiResource(
 *      attributes={
 *        "order"={"id": "ASC"}
 *      },
 *      denormalizationContext={"groups"={"create:media"}},
 *  collectionOperations={
 *      "get" = {
 *          "openapi_context"={"security"={{"bearerAuth"={}}}},
 *          "security"="is_granted('IS_AUTHENTICATED_FULLY')",
 *          "security_message"="You must be logged in to access this resource",
 *     },
 *      "post" = {
 *         "denormalization_context"={"groups"={"create:media"}},
 *         "controller" = App\Controller\Api\UploadController::class,
 *         "openapi_context"={"security"={{"bearerAuth"={}}}, "summary"="Admin - Create a new media resource"},
 *         "security"="is_granted('ROLE_ADMIN')",
 *         "security_message"="Only admins can add media"
 *       }
 *  },
 *  itemOperations={
 *      "get" ={
 *          "openapi_context"={"security"={{"bearerAuth"={}}}},
 *          "security"="is_granted('IS_AUTHENTICATED_FULLY')",
 *          "security_message"="You must be logged in to access this resource",
 *       },
 *      "patch"= {
 *         "denormalization_context"={"groups"={"create:media"}},
 *         "openapi_context"={"security"={{"bearerAuth"={}}}, "summary"="Admin - Update media"},
 *         "security"="is_granted('ROLE_ADMIN')",
 *         "security_message"="Only admins can edit media",
 *       },
 *      "delete" = {
 *          "openapi_context"={"security"={{"bearerAuth"={}}}, "summary"="Admin - Delete media"},
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "security_message"="Only admins can delete media",
 *      }
 * }
 * )
 */
class MediaPicture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:product"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:product", "create:media"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="mediaPictures")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"create:media"})
     */
    private $product;

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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
