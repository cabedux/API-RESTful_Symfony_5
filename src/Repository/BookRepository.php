<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Book::class);
        $this->manager = $manager;
    }

    public function saveBook($data)
    {
        $newBook = new Book();

        $newBook
            ->setIsbn($data['isbn'])
            ->setTitle($data['title'])
            ->setSubtitle($data['subtitle'])
            ->setAuthor($data['author'])
            ->setPublished(new \DateTime($data['published']))
            ->setPublisher($data['publisher'])
            ->setPages($data['pages'])
            ->setDescription($data['description'])
            ->setWebsite($data['website'])
            ->setCategory($data['category']);

        $this->manager->persist($newBook);
        $this->manager->flush();
    }
    
    public function removeBook(Book $book)
    {
        $this->manager->remove($book);
        $this->manager->flush();
    }

    public function findAllLowerThanYear(int $year): array
    {

        $query = $this->createQueryBuilder('p')
            ->where('YEAR(p.published) < :year')
            ->setParameter('year', $year)
            ->orderBy('p.published', 'ASC');
        
        $response = $query->getQuery();

        return $response->execute();
    }

    // /**
    //  * @return Book[] Returns an array of Book objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Book
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}