<?php

namespace App\Repository;

use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;

/**
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 * @method News|null findOneBy(array $criteria, array $orderBy = null)
 * @method News[]    findAll()
 * @method News[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    public function countNews()
    {
        return $this->createQueryBuilder('news')
            ->select('count(news.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function popularNews()
    {
        $qb = $this->createQueryBuilder('news');

        return $qb
            ->innerJoin('news.likes', 'l')
            ->groupBy('news.id')
            ->orderBy('count(l)', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }

    public function printNews($page, $per_page)
    {
        return $this->createQueryBuilder('news')
            ->setFirstResult(($page - 1) * $per_page)
            ->groupBy('news.id')
            ->orderBy('news.id', 'DESC')
            ->setMaxResults($per_page)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return News[] Returns an array of News objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?News
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
