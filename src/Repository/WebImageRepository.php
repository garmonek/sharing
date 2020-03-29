<?php
/**
 * @license MIT
 */

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
    /**
     * WebImageRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebImage::class);
    }
}
