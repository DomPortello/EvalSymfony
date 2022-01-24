<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\Thread;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getAllPostsFromAThread(int $id): QueryBuilder
    {
        return $this->createQueryBuilder('post')
            ->select('post', 'category', 'sub_category', 'thread')
            ->leftJoin('post.thread','thread')
            ->leftJoin('thread.subCategory','sub_category')
            ->leftJoin('sub_category.category','category')
            ->where('thread.id = :id')
            ->setParameter(':id', $id);
    }

    public function getAllPostsFromAnUser(int $id): QueryBuilder
    {
        return $this->createQueryBuilder('post')
            ->select('post', 'category', 'sub_category', 'thread')
            ->leftJoin('post.thread','thread')
            ->leftJoin('thread.subCategory','sub_category')
            ->leftJoin('sub_category.category','category')
            ->leftJoin('post.user','user')
            ->where('user.id = :id')
            ->setParameter(':id', $id);
    }

    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
