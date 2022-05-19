<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\Controller\Api\UserItemController;
use ApiPlatform\Core\Action\NotFoundAction;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use App\Controller\Api\User\UserCreateController;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Email as Email;
use Symfony\Component\Validator\Constraints\Length as Length;
use App\Validator\Constraints\UserProperties as UserProperties;
use Symfony\Component\Validator\Constraints\NotBlank as NotBlank;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity as UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email", groups={"create:user"})
 * @ApiResource(
 *  normalizationContext={"groups"={"read:user"}, "openapi_definition_name"="Collection"},
 *  denormalizationContext={"groups"={"create:user", "edit:user"}, "openapi_definition_name"="Creation"},
 *  collectionOperations={
 *      "me"={ 
 *            "method"="GET", 
 *            "path"="/users/me", 
 *            "controller"=App\Controller\Api\UserController::class, 
 *            "pagination_enabled"=false,
 *            "security"="is_granted('IS_AUTHENTICATED_FULLY')",
 *            "security_message"="You must be logged in to access this resource",
 *            "openapi_context"={"summary"="Get the current user", "security"={{"bearerAuth"={}}}}, 
 *       },
 *      "get"={
 *          "openapi_context"={"security"={{"bearerAuth"={}}}, "summary"="List of all users of Customer"},
 *          "security"="is_granted('ROLE_CUSTOMER') or is_granted('ROLE_ADMIN')",
 *          "security_message"="Only customers or admins can access this resource",
 *       },
 *      "post"= {
 *         "openapi_context"={"security"={{"bearerAuth"={}}}, "summary"="Create a new user resource of Customer"},
 *         "denormalization_context"={"groups"={"create:user"}},
 *         "controller"=App\Controller\Api\AlreadyExistsController::class, 
 *         "security"="is_granted('ROLE_CUSTOMER') or is_granted('ROLE_ADMIN')",
 *         "security_message"="Only admins or customers can add users",
 *       }
 *  },
 *  itemOperations={
 *      "get"= {
 *         "openapi_context"={"security"={{"bearerAuth"={}}}, "summary"="Get a user resource of Customer"},
 *         "security"="is_granted('USER_VIEW', object)",
 *         "security_message"="Restricted to owner customer or admins",
 *       },
 *      "delete"= {
 *         "openapi_context"={"security"={{"bearerAuth"={}}}, "summary"="Delete a user resource of Customer"},
 *         "security"="is_granted('USER_DELETE', object)",
 *         "security_message"="Restricted to owner customer or admins",
 *       },
 *      "patch"= {
 *         "openapi_context"={"security"={{"bearerAuth"={}}}, "summary"="Update a user resource of Customer"},
 *         "denormalization_context"={"groups"={"edit:user"}},
 *         "controller" = App\Controller\Api\AlreadyExistsController::class,
 *         "security"="is_granted('USER_EDIT', object)",
 *         "security_message"="Restricted to owner user or owner customer or admins",
 *       },
 *  }
 * )
 */
 

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:user"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"read:user", "create:user", "edit:user"})
     * @Email(message="The email '{{ value }}' is not a valid email.")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"create:user", "edit:user"})
     * @Length(min=8, minMessage="Your password must be at least 8 characters long.")
     * @NotBlank(message="Your password is required")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"read:user", "create:user", "edit:user"})
     * @NotBlank(message="Your firstname is required")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"read:user", "create:user", "edit:user"})
     * @NotBlank(message="Your lastname is required")
     */
    private $lastname;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read:user"})
     */
    private $customer;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="user")
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

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
            $product->setUser($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless alread:usery changed)
            if ($product->getUser() === $this) {
                $product->setUser(null);
            }
        }

        return $this;
    }
}
