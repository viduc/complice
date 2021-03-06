<?php declare(strict_types=1);
/******************************************************************************/
/*                                   COMPLICE                                 */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/

namespace App\Entity;

use App\Exception\CompliceException;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    public function __construct()
    {
        //$this->roles = ['ROLE_USER'];
    }
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $civilite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $last_login;

    /**
     * @ORM\Column(type="datetime")
     */
    private $cree_le;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modifie_le;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @return array
     */
    final public function getRoles() : array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     * @throws CompliceException
     */
    final public function setRoles(array $roles) : void
    {
        if (!in_array('ROLE_USER', $roles, true))
        {
            $roles[] = 'ROLE_USER';
        }
        foreach ($roles as $role)
        {
            if(strpos($role, 'ROLE_') === FALSE) {
                throw new CompliceException(
                    "Chaque rôle doit commencer par 'ROLE_'",
                    115
                );
            }
        }
        $this->roles = $roles;
    }

    /**
     * @param string $role
     */
    final public function addRole(string $role) : void
    {
        /*if (!in_array($role, $this->roles, true))
        {*/
            $roles[] = $role;
        //}
    }

    /**
     * @param string $role
     */
    final public function removeRole(string $role) : void
    {
        if (in_array($role, $this->roles, true))
        {
            $key = array_search($role, $this->roles, true);
            array_slice($this->roles, $key, 1);
        }
    }

    /**
     * @return mixed
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * @param mixed $actif
     */
    public function setActif($actif): void
    {
        $this->actif = $actif;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(?string $civilite): self
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->last_login;
    }

    public function setLastLogin(?\DateTimeInterface $last_login): self
    {
        $this->last_login = $last_login;

        return $this;
    }

    public function getCreeLe(): ?\DateTimeInterface
    {
        return $this->cree_le;
    }

    public function setCreeLe(\DateTimeInterface $cree_le): self
    {
        $this->cree_le = $cree_le;

        return $this;
    }

    public function getModifieLe(): ?\DateTimeInterface
    {
        return $this->modifie_le;
    }

    public function setModifieLe(?\DateTimeInterface $modifie_le): self
    {
        $this->modifie_le = $modifie_le;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
