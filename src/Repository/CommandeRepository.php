<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Commande;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Commande $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function FindByUser(User $user)
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata('App\Entity\Commande', 'Commande');
     
        $sql = 'SELECT * FROM commande c
        WHERE c.user_id = ?
       ';
        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $user->getId());
        return $query->getResult();
    }

    public function FindCommandePDF(Int $id)
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager(), ResultSetMappingBuilder::COLUMN_RENAMING_INCREMENT);
        $rsm->addRootEntityFromClassMetadata('App\Entity\Commande', 'commande', array('id' => 'cid'));
        
     
        $sql = 'SELECT c.id As cid, c.* FROM commande c
        WHERE c.id = ?
       ';
        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $id);
        return $query->getResult();
    }


}
