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
     * @param int|null    $cityId
     * @param string|null $value
     *
     * @return QueryBuilder
     */
    public function queryNameLikeForCity(?int $cityId, ?string $value): QueryBuilder
    {
        $builder = $this->queryNameLike($value);
        if (!$cityId || 0 >= $cityId) {
            return $builder;
        }

        $builder = $this->queryNameLike($value)
            ->andWhere('d.city = :cityId')
            ->setParameter(':cityId', $cityId, ParameterType::INTEGER);

        return $builder;
    }

    /**
     * @param string $value
     *
     * @return QueryBuilder
     */
    public function queryNameLike(?string $value): QueryBuilder
    {
        $builder = $this->createQueryBuilder('d');
        if (!$value || empty($value)) {
            return $builder;
        }

        return $builder
            ->andWhere('d.name like :val')
            ->setParameter('val', $value.'%', ParameterType::STRING)
            ->orderBy('d.name', 'ASC');
    }
}
