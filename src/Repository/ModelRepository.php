<?php

namespace App\Repository;

use App\Entity\Model;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

const ENTITY_NAME = 'model';
const ENTITY_INIT = 'm';

class ModelRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry) {
		parent::__construct($registry, Model::class);
	}

	public function getPaged($pageIndex = 1, $maxResults = 20, $onlyPublic = true) {
		$qb = $this->createQueryBuilder(ENTITY_NAME);
		if($onlyPublic === true){
			$qb
				->select(ENTITY_NAME)
				->where(ENTITY_NAME.'.public = :public')
				->setParameter('public', true);
		} else {
			$qb->select(ENTITY_NAME);
		}
		$qb
			->setFirstResult(($pageIndex - 1) * $maxResults)
			->setMaxResults($maxResults);

		$page = new Paginator($qb, false);
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
