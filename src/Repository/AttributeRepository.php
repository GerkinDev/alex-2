<?php

namespace App\Repository;

use App\Entity\Attribute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

use App\Entity\AttributeCategory;

class AttributeRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry) {
		parent::__construct($registry, Attribute::class);
	}

	public function findCheapest(AttributeCategory $category) {
		$queryRes = $this->createQueryBuilder('m')
		->where('m.category = :categoryId')
		->setParameter('categoryId', $category->getId())
		->orderBy('m.price', 'ASC')
		->setMaxResults(1)
		->getQuery()
		->getResult()
		;
		if (count($queryRes) === 0) {
			return null;
		} else {
			return $queryRes[0];
		}
	}
	/*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('a')
            ->where('a.something = :value')->setParameter('value', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
