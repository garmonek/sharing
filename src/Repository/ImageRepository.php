<?php
/**
 * @license MIT
 */

namespace App\Repository;

use App\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Image|null find($id, $lockMode = null, $lockVersion = null)
 * @method Image|null findOneBy(array $criteria, array $orderBy = null)
 * @method Image[]    findAll()
 * @method Image[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageRepository extends ServiceEntityRepository
{
    /**
     * ImageRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }

    /**
     * @param int $offerId
     *
     * @return QueryBuilder
     */
    public function queryOfferImages(int $offerId): QueryBuilder
    {
        $builder = $this->createQueryBuilder('i');

        $builder->innerJoin('i.offers', 'o', 'WITH', 'o.id = :offerId')
            ->setParameter('offerId', $offerId, ParameterType::INTEGER);

        return $builder;
    }
}
