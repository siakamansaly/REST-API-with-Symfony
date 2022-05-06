<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CustomerRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\Api\CustomerCreateController;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\Api\Customer\CustomerDeleteController;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\NotBlank as NotBlank;


/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 * @UniqueEntity("name", message="This name is already used.", groups={"create:customer"})
 * @ApiResource(
 *      attributes={
 *        "order"={"createdAt": "DESC"},
 *        "security"="is_granted('ROLE_ADMIN')"
 *      },
 *      paginationEnabled=false,
 *      normalizationContext={"groups"={"read:customer"}},
 *      denormalizationContext={"groups"={"create:customer"}},
 *  collectionOperations={
 *      "get" = {"normalization_context"={"groups"={"read:customer"}},
 *      "openapi_context"={"security"={{"bearerAuth"={}}}},
 *      },
 *      "post" = {
 *         "denormalization_context"={"groups"={"create:customer"}},
 *         "controller" = App\Controller\Api\AlreadyExistsController::class,
 *         "openapi_context"={"security"={{"bearerAuth"={}}}},
 *     }
 *  },
 *  itemOperations={
 *      "get"={"normalization_context"={"groups"={"read:customer", "read:customer:full"}},
 *             "openapi_context"={"security"={{"bearerAuth"={}}}},
 *       },
 *      "patch"= {
 *          "normalization_context"={"groups"={"read:customer"}},
 *         "denormalization_context"={"groups"={"create:customer"}},
 *         "controller" = App\Controller\Api\AlreadyExistsController::class,
 *         "openapi_context"={"security"={{"bearerAuth"={}}}},
 *       },
 *      "delete"={"openapi_context"={"security"={{"bearerAuth"={}}}},}
 * }
 * )
 * @ApiFilter(SearchFilter::class, properties={"name": "partial"})
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
     * @Groups({"read:customer:full"})
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
