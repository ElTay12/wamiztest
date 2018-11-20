<?php

namespace App\Repository;

use App\Entity\Mailing;
use App\Entity\MailingSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Mailing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mailing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mailing[]    findAll()
 * @method Mailing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MailingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Mailing::class);
    }

    public function findAllVisible(MailingSearch $search = null)
    {

        $query = $this->createQueryBuilder('m')
                    ->where('m.deleted_at IS null');

        if($search && $search->getEmail()){
            $query->andWhere('m.email like :mail')
                ->setParameter('mail', '%'.$search->getEmail().'%');
        }

        return $query->orderBy('m.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findSearch($value)
    {
        return $this->createQueryBuilder('m')
            ->where('m.deleted_at IS null')
            ->andWhere('m.email like :mail')
            ->setParameter('mail', '%'.$value.'%')
            ->orderBy('m.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Mailing[] Returns an array of Mailing objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Mailing
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
