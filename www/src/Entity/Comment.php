<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\Table(name: '`app_comment`')]
class Comment
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column(type: Types::BIGINT)]
	private int $id;

	#[ORM\Column(type: Types::STRING, length: 150)]
	public string $author;
	#[ORM\Column(type: Types::STRING, length: 200, nullable: true)]
	public ?string $content = null;
	#[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, updatable: false)]
	public DateTimeImmutable $dateCreate;
	#[ORM\Column(type: Types::BIGINT)]
	private int $likesAmount;

	public function getId(): int
	{
		return $this->id;
	}
}