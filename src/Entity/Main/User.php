<?php

namespace App\Entity\Main;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;



/**
 * @ORM\Table(name="users")
 *
 * @ORM\Entity(repositoryClass="App\Repository\Main\UserRepository")
 *
 * @UniqueEntity(fields="username", message="Ce nom d'utilisateur est déjà utilisé")
 * @UniqueEntity(fields="user_password", message="Ce mot de passe est dejà utilisé")
 * @UniqueEntity(fields="token", message="Ce token est dejà utilisé")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=false, nullable=false)
     */
    private $id_local;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    private $password_hash;

    /**
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    private $database_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $data;

    /**
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    private $role;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $is_admin;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $provisional_pwd;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $token;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Main\Permission", inversedBy="users")
     */
    private $permissions;

    public function __construct()
    {
        $this->permissions = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdLocal(): ?int
    {
        return $this->id_local;
    }

    public function setIdLocal(int $id_local): self
    {
        $this->id_local = $id_local;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPasswordHash(): ?string
    {
        return $this->password_hash;
    }

    public function setPasswordHash(string $password_hash): self
    {
        $this->password_hash = $password_hash;

        return $this;
    }


    public function getDatabaseName(): ?string
    {
        return $this->database_name;
    }


    public function setDatabaseName(string $database_name): self
    {
        $this->database_name = $database_name;

        return $this;
    }


    public function getData(): ?string
    {
        return $this->data;
    }


    public function setData(string $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getIsAdmin(): ?bool
    {
        return $this->is_admin;
    }

    public function setIsAdmin(bool $is_admin): ?self
    {
        $this->is_admin = $is_admin;

        return $this;
    }

    public function getProvisionalPwd(): ?string
    {
        return $this->provisional_pwd;
    }

    public function setProvisionalPwd(string $provisional_pwd): self
    {
        $this->provisional_pwd = $provisional_pwd;

        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token): self
    {
        $this->token = $token;

        return $this;
    }


    /***  Symfony\Component\Security\Core\User\UserInterface method start  ***/
    /**
     * Don't remove
     * REQUIRED FOR PROVIDER AND PWD HASH AND PWD HASH
     *
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->getLogin();
    }

    /**
     * Don't remove
     * REQUIRED FOR PROVIDER AND PWD HASH
     *
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return string[] The user roles
     */
    public function getRoles()
    {
        return [
          $this->getRole()
        ];
    }

    /**
     * Don't remove
     * REQUIRED FOR PROVIDER AND PWD HASH
     *
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string|null The encoded password if any
     */
    public function getPassword()
    {
        return $this->getPasswordHash();
    }

    /**
     * Don't remove
     * REQUIRED FOR PROVIDER AND PWD HASH
     *
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt(){ }

    /**
     * Don't remove
     * REQUIRED FOR PROVIDER AND PWD HASH
     *
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials(){ }

    /**
     * @return Collection|Permission[]
     */
    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    public function addPermission(Permission $permission): self
    {
        if (!$this->permissions->contains($permission)) {
            $this->permissions[] = $permission;
        }

        return $this;
    }

    public function removePermission(Permission $permission): self
    {
        if ($this->permissions->contains($permission)) {
            $this->permissions->removeElement($permission);
        }

        return $this;
    }
    /***  UserInterface method end  ***/

}
