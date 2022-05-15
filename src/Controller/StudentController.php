<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\Student;
use App\Repository\AssignmentRepository;
use App\Repository\DepartmentRepository;
use App\Repository\FacultyRepository;
use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/student')]
class StudentController extends AbstractController
{
    #[Route('/', name: 'app_student')]
    public function index(AssignmentRepository $assignmentRepository): Response
    {
        return $this->render('learning/student/index.html.twig', [
            'controller_name' => 'StudentController',
            'assignments' => $assignmentRepository->findPendingAssignmentsForStudent($this->getUser()->getStudent())
        ]);
    }
    #[Route('/courses', name: 'app_student_courses')]
    public function Courses(): Response
    {
        return $this->render('learning/student/courses.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }
    #[Route('/assignments', name: 'app_student_assignments')]
    public function Assignments(): Response
    {
        return $this->render('learning/student/assignments.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }
    #[Route('/enroll', name: 'app_student_enroll')]
    public function Enroll(Request $request, DepartmentRepository $departmentRepository, FacultyRepository $facultyRepository, StudentRepository $studentRepository): Response
    {
        $department = $request->request->get('department');
        if($department){
            $department = $departmentRepository->find($department);
            $student = new Student();
            $student->setDepartment($department);
            $student->setUser($this->getUser());
            $studentRepository->add($student, true);
            return $this->redirectToRoute('app_student');
        }

        return $this->render('learning/student/enroll.html.twig', [
            'controller_name' => 'StudentController',
            'faculties' => $facultyRepository->findAll(),
            'departments' => $departmentRepository->findAll()
        ]);
    }
}
