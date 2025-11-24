<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/*
 * Représente un utilisateur de l'application.
 *
 * Cette entité gère l'identifiant, le nom d'utilisateur,
 * les rôles et le mot de passe chiffré. Elle implémente
 * UserInterface et PasswordAuthenticatedUserInterface
 * pour l'intégration au système de sécurité Symfony.
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /*
     * Identifiant unique de l'utilisateur.
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /*
     * Nom d'utilisateur servant d'identifiant.
     *
     * @var string|null
     */
    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /*
     * Retourne le nom d'utilisateur.
     *
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /*
     * Définit le nom d'utilisateur.
     *
     * @param string $username
     * @return static
     */
    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * Retourne la liste des rôles associés à l'utilisateur.
     * Ajoute toujours automatiquement ROLE_USER.
     *
     * @see UserInterface
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Définit les rôles de l'utilisateur.
     *
     * @param list<string> $roles
     * @return static
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Retourne le mot de passe chiffré.
     *
     * @see PasswordAuthenticatedUserInterface
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /*
     * Définit le mot de passe chiffré.
     *
     * @param string $password
     * @return static
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Supprime les données sensibles temporaires.
     *
     * @see UserInterface
     * @return void
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
