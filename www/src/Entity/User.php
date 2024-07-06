<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
	#[ORM\Id]
	#[ORM\Column(unique: true)]
	public ?int $vkId = null;

	#[ORM\Column(type: Types::STRING, nullable: true)]
	public ?string $photo = null;

	#[ORM\Column(type: Types::STRING, length: 180)]
	private string $firstname;

	#[ORM\Column(type: Types::STRING, length: 180, nullable: true)]
	private ?string $lastname = null;

	#[ORM\Column(type: Types::STRING, length: 180, nullable: true)]
	private ?string $refreshToken = null;

	#[ORM\Column(type: Types::STRING, length: 180, nullable: true)]
	private ?string $accessToken = null;

	#[ORM\Column]
	private array $roles = [];

	/**
	 * @var string The hashed password
	 */
	#[ORM\Column]
	private ?string $password = null;

	public function getId(): ?int
	{
		return $this->vkId;
	}

	public function setVkId(?int $vkId): void
	{
		$this->vkId = $vkId;
	}

	/**
	 * A visual identifier that represents this user.
	 *
	 * @see UserInterface
	 */
	public function getUserIdentifier(): string
	{
		return (string)$this->vkId;
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

	public function setRoles(array $roles): static
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

	public function setPassword(string $password): static
	{
		$this->password = $password;

		return $this;
	}

	/**
	 * @see UserInterface
	 */
	public function eraseCredentials(): void
	{
		// If you store any temporary, sensitive data on the user, clear it here
		// $this->plainPassword = null;
	}

	public function getFirstname(): string
	{
		return $this->firstname;
	}

	public function setFirstname(string $firstname): User
	{
		$this->firstname = $firstname;

		return $this;
	}

	public function getPhoto(): ?string
	{
		return $this->photo;
	}

	public function setPhoto(?string $photo): User
	{
		$this->photo = $photo;

		return $this;
	}

	public function getLastname(): ?string
	{
		return $this->lastname;
	}

	public function setLastname(?string $lastname): User
	{
		$this->lastname = $lastname;

		return $this;
	}

	public function getRefreshToken(): ?string
	{
		return $this->refreshToken;
	}

	public function setRefreshToken(?string $refreshToken): User
	{
		$this->refreshToken = $refreshToken;

		return $this;
	}

	public function getAccessToken(): ?string
	{
		return $this->accessToken;
	}

	public function setAccessToken(?string $accessToken): User
	{
		$this->accessToken = $accessToken;

		return $this;
	}
}