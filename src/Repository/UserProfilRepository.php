<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\Query;
use App\Entity\UserProfil;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method UserProfil|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserProfil|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserProfil[]    findAll()
 * @method UserProfil[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserProfilRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserProfil::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(UserProfil $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    
   
  
    public function FindAdr(User $user)
    {
                    return $this->createQueryBuilder('p')
                    ->andWhere('p.id = :id')
                    ->setParameter('id', $user->getUserProfil())
                    ->getQuery()
                    ->getResult(); 
    }

    /**0
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(UserProfil $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return UserProfil[] Returns an array of UserProfil objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserProfil
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
