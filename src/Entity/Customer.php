<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CustomerRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\NotBlank as NotBlank;


/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 * @UniqueEntity("name", message="This name is already used.", groups={"create:customer"})
 * @ApiResource(
 *      attributes={
 *        "order"={"createdAt": "DESC"},
 *        "security"="is_granted('ROLE_ADMIN')",
 *        "security_message"="Only admins can access this resource",
 *      },
 *      paginationEnabled=true,
 *      normalizationContext={"groups"={"read:customer"}, "swagger_definition_name"="Read", "exclude_swagger_definition"=true},
 *      denormalizationContext={"groups"={"create:customer"}, "swagger_definition_name"="Create"},
 *  collectionOperations={
 *      "get" = {
 *         "openapi_context"={"security"={{"bearerAuth"={}}}, "summary"="Admin - Customer collection"},
 *      },
 *      "post" = {
 *         "controller" = App\Controller\Api\AlreadyExistsController::class,
 *         "openapi_context"={"security"={{"bearerAuth"={}}}, "summary"="Admin - Create a new customer resource"},
 *     }
 *  },
 *  itemOperations={
 *      "get"={
 *         "openapi_context"={"security"={{"bearerAuth"={}}}, "summary"="Admin - Get a Customer resource"},
 *       },
 *      "patch"= {
 *         "controller" = App\Controller\Api\AlreadyExistsController::class,
 *         "openapi_context"={"security"={{"bearerAuth"={}}}, "summary"="Admin - The updated Customer resource"},
 *       },
 *      "delete"={
 *         "openapi_context"={"security"={{"bearerAuth"={}}}, "summary"="Admin - Delete a Customer resource"},
 *       }
 * }
 * )
 */
class Customer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:customer"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"read:user", "create:customer", "read:customer"})
     * @NotBlank(message="Customer name's is required")
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read:customer"})
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="customer", orphanRemoval=true)
     */
    private $users;


    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCustomer($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCustomer() === $this) {
                $user->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"read:customer"})
     */
    public function getCountUsers(): int
    {
        return count($this->users);
    }
}
