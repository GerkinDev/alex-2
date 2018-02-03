<?php

namespace App\Repository;

use App\Entity\Material;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class MaterialRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry) {
		parent::__construct($registry, Material::class);
	}

	public function findCheapest()
	{
		$queryRes = $this->createQueryBuilder('m')
		->orderBy('m.price', 'ASC')
		->setMaxResults(1)
		->getQuery()
		->getResult()
		;
		if(count($queryRes) === 0){
			return null;
		} else {
			return $queryRes[0];
		}
	}
}
