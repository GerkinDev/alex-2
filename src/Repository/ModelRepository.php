<?php

namespace App\Repository;

use App\Entity\Model;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ModelRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry) {
		parent::__construct($registry, Model::class);
	}

	public function getPage($firstResult, $maxResults = 20)
	{
		$qb = $this->createQueryBuilder('model');
		$qb
			->select('model')
			->setFirstResult($first_result)
			->setMaxResults($max_results);

		$pag = new Paginator($qb);
		return $pag;
	}
	/*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('m')
            ->where('m.something = :value')->setParameter('value', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
