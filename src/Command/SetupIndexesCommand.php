<?php
namespace App\Command;

use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetupIndexesCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:setup-indexes';
    private CourseRepository $courseRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(CourseRepository $courseRepository, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->courseRepository =$courseRepository;
        $this->entityManager =$entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
            $conn = $this->entityManager->getConnection();
            $sql = "
            ALTER TABLE course ADD FULLTEXT(title);
            ALTER TABLE course ADD FULLTEXT(description);
            ";

            $stmt = $conn->prepare($sql);
            $stmt->executeQuery();
        return Command::SUCCESS;
    }
}