<?php

namespace App\Repository;

use App\Entity\WebImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method WebImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebImage[]    findAll()
 * @method WebImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebImage::class);
    }

    // /**
    //  * @return WebImage[] Returns an array of WebImage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WebImage
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
