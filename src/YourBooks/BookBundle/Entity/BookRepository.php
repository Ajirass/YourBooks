<?php

namespace YourBooks\BookBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * BookRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BookRepository extends EntityRepository
{
    public function countBooksSubmit($author)
    {
        $qb = $this->createQueryBuilder('b')
            ->select('count(b.id)')
            ->where('b.author = :author')
            ->setParameter(':author', $author)
            ;

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countBooksRead($author)
    {
        $qb = $this->createQueryBuilder('b')
            ->select('count(b.id)')
            ->where('b.author = :author')
            ->andWhere('b.readerValidation = :readerValidation')
            ->setParameter(':author', $author)
            ->setParameter(':readerValidation', true)
            ;

        return $qb->getQuery()->getSingleScalarResult();
    }
}
