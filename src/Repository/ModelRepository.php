<?php

namespace App\Repository;

use App\Entity\Model;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ModelRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry) {
		parent::__construct($registry, Model::class);
	}

	public function getPaged($page = 0, $maxResults = 20) {
		$qb = $this->createQueryBuilder('model');
		$qb
			->select('model')
			->setFirstResult($page * $maxResults)
			->setMaxResults($maxResults);

		$page = new Paginator($qb, false);
		//var_dump($page->getQuery());
		return $page;
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
