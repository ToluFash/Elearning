<?php

namespace App\Repository;

use App\Entity\Course;
use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<Course>
 *
 * @method Course|null find($id, $lockMode = null, $lockVersion = null)
 * @method Course|null findOneBy(array $criteria, array $orderBy = null)
 * @method Course[]    findAll()
 * @method Course[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseRepository extends ServiceEntityRepository
{
    private LoggerInterface $logger;
    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, Course::class);
        $this->logger = $logger;
    }

    public function add(Course $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Course $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function studentEnrolled(Course $course, Student $student): bool{
        $conn = $this->getEntityManager()->getConnection();
        $sql = "
            SELECT * FROM course_student
            WHERE course_id=:cid AND student_id=:sid;
            ";

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery([':cid' => $course->getId(), ':sid' => $student->getId()])->fetchAllKeyValue();
        return count($resultSet);
    }

    public function findCourses(string $searchTerm): array
    {

        if($searchTerm){
            $conn = $this->getEntityManager()->getConnection();
            $sql = "
            SELECT * FROM course
            WHERE MATCH(title) AGAINST (:searchTerm IN BOOLEAN MODE);
            ";

            $stmt = $conn->prepare($sql);
            $resultSet = $stmt->executeQuery([':searchTerm' => '+'.$searchTerm.'*'])->fetchAllKeyValue();
            return $this->findBy(['id'=> $resultSet]);
        }
        else{
            return $this->findAll();
        }

    }

//    /**
//     * @return Course[] Returns an array of Course objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Course
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
