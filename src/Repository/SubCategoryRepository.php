<?php

namespace App\Repository;

use App\Entity\SubCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SubCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubCategory[]    findAll()
 * @method SubCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubCategory::class);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findAllWithRelationsForOne(string $name): SubCategory
    {
        return $this->createQueryBuilder('sub_category')
            ->select('sub_category','category', 'threads')
            ->leftJoin('sub_category.category','category')
            ->leftJoin('sub_category.threads','threads')
            ->where('sub_category.name = :name')
            ->setParameter(':name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }
//
//    public function findAllWithRelationsForOne(): array
//    {
//        return $this->createQueryBuilder('sub_category')
//            ->select('sub_category','category', 'threads', 'posts')
//            ->leftJoin('sub_category.category','category')
//            ->leftJoin('sub_category.threads','threads')
//            ->leftJoin('threads.posts','posts')
//            ->where('sub_category.name = :name')
//            ->getQuery()
//            ->getResult();
//    }

    // /**
    //  * @return SubCategory[] Returns an array of SubCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SubCategory
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
