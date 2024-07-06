<?php

namespace App\Entity\Ai\Post\Analytic;

use App\Repository\Ai\Post\Analytic\GenerateResultRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenerateResultRepository::class)]
#[ORM\Table(name: '`app_ai_post_analytic_generate_result`')]
class GenerateResult
{
	const string GENERAL = 'general';
	const string IMAGE = 'image';

	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column(type: Types::BIGINT)]
	private int $id;

	#[ORM\Column(type: Types::TEXT)]
	public string $vkPostId;
	#[ORM\Column(type: Types::TEXT)]
	public string $vkGroupId;

	#[ORM\Column(type: Types::TEXT)]
	public string $content;

	#[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, updatable: false)]
	public DateTimeImmutable $dateCreate;

	#[ORM\Column(type: Types::TEXT)]
	public string $type = self::GENERAL;

	public function getId(): int
	{
		return $this->id;
	}
}