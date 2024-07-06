<?php

namespace App\Repository\Ai\Post\Analytic;

use App\Entity\Ai\Post\Analytic\GenerateResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GenerateResult>
 *
 * @method GenerateResult|null find($id, $lockMode = null, $lockVersion = null)
 * @method GenerateResult|null findOneBy(array $criteria, array $orderBy = null)
 * @method GenerateResult[]    findAll()
 * @method GenerateResult[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenerateResultRepository extends ServiceEntityRepository
{
	public function __construct(
		ManagerRegistry $registry,
	)
	{
		parent::__construct($registry, GenerateResult::class);
	}

	public function save(GenerateResult $GenerateResult): void
	{
		$this->getEntityManager()->persist($GenerateResult);
		$this->getEntityManager()->flush();
	}
}