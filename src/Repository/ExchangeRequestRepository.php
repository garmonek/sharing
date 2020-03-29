<?php

namespace App\Repository;

use App\Entity\ExchangeRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ExchangeRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExchangeRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExchangeRequest[]    findAll()
 * @method ExchangeRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExchangeRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExchangeRequest::class);
    }
}
