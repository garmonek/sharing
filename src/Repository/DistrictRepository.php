<?php
/**
 * @license MIT
 */

namespace App\Repository;

use App\Entity\District;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\QueryBuilder;

/**
 * @method District|null find($id, $lockMode = null, $lockVersion = null)
 * @method District|null findOneBy(array $criteria, array $orderBy = null)
 * @method District[]    findAll()
 * @method District[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DistrictRepository extends ServiceEntityRepository
{
    /**
     * DistrictRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, District::class);
    }

    /**
     * @param string $value
     * @return QueryBuilder
     */
    public function queryNameLike(string $value): QueryBuilder
    {
        $builder = $this->createQueryBuilder('d');
        if (empty($value)) {
            return $builder;
        }

        return $builder
            ->andWhere('d.name like :val')
            ->setParameter('val', $value . '%', ParameterType::STRING)
            ->orderBy('d.name', 'ASC');
    }
}
