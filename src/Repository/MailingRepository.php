<?php

namespace App\Repository;

use App\Entity\Mailing;
use App\Entity\MailingSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class MailingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry){

        parent::__construct($registry, Mailing::class);
    }

    // Get All or with search and exclude softDelete
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

}
