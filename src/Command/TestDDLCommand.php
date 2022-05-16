<?php
namespace App\Command;

use App\Repository\CourseRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestDDLCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:test-ddl';
    private CourseRepository $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        parent::__construct();
        $this->courseRepository =$courseRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $result = $this->courseRepository->findCourses('E');

        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }
}