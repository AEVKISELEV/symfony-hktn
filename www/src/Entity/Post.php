<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`app_post`')]
class Post
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column(type: Types::BIGINT)]
	private int $id;

	#[ORM\Column(type: Types::STRING, length: 150)]
	#[Assert\Length(min: 2, max: 50)]
	public string $title;

	#[ORM\Column(type: Types::STRING, length: 150)]
	public string $author;

	public File $image;

	#[ORM\Column(type: Types::STRING, length: 150, unique: true)]
	public string $vkId;

	#[ORM\Column(type: Types::STRING, length: 200, nullable: true)]
	public ?string $content = null;

	#[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, updatable: false)]
	public \DateTimeImmutable $dateCreate;

	#[ORM\Column(type: Types::BIGINT)]
	private int $likesAmount;


	public function getId(): int
	{
		return $this->id;
	}
}