<?php

namespace App\Entity\Ai\Post\Analytic;

use App\Entity\Post;
use App\Repository\Ai\Post\Analytic\GenerateResultRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenerateResultRepository::class)]
#[ORM\Table(name: '`app_ai_post_analytic_generate_result`')]
class GenerateResult
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column(type: Types::BIGINT)]
	private int $id;

	#[ORM\Column(type: Types::TEXT)]
	public string $vkPostId;

	#[ORM\Column(type: Types::STRING, length: 200)]
	public string $content;

	#[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, updatable: false)]
	public DateTimeImmutable $dateCreate;

	public function getId(): int
	{
		return $this->id;
	}
}