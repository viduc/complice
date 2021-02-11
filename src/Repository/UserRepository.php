<?php

namespace App\Repository;

use App\Entity\User;
use App\Exception\CompliceException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param string $username
     * @return User[] Returns an array of User objects
     */
    final public function findByUsername(string $username) : array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.username = :val')
            ->setParameter('val', $username)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param string $token
     * @return User
     * @throws CompliceException
     */
    final public function findByToken(string $token) : User
    {
        try {
            return $this->createQueryBuilder('u')
                        ->andWhere('u.token = :val')
                        ->setParameter('val', $token)
                        ->setMaxResults(1)
                        ->getQuery()
                        ->getSingleResult()
                ;
        } catch (NoResultException|NonUniqueResultException $ex) {
            throw new CompliceException(
                'Aucun token trouvé',
                104
            );
        }
    }
}
