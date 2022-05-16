<?php

namespace App\Controller;

use App\Entity\Assignment;
use App\Entity\Course;
use App\Entity\Department;
use App\Entity\Lecture;
use App\Entity\Student;
use App\Entity\Submission;
use App\Repository\AssignmentRepository;
use App\Repository\CourseRepository;
use App\Repository\DepartmentRepository;
use App\Repository\FacultyRepository;
use App\Repository\StudentRepository;
use App\Repository\SubmissionRepository;
use Doctrine\Persistence\ManagerRegistry;
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
    #[Route('/courses/search', name: 'app_student_courses_search', methods: ["POST"])]
    public function SearchCourses(Request $request, CourseRepository $courseRepository): Response
    {
        return $this->render('learning/student/courses_search.html.twig', [
            'courses' => $courseRepository->findCourses($request->request->get('searchTerm'))
        ]);
    }
    #[Route('/courses/{course}', name: 'app_student_course')]
    public function Course(Course $course, CourseRepository $courseRepository): Response
    {
        /* @var $student Student*/
        $student = $this->getUser()->getStudent();
        return $this->render('learning/student/course.html.twig', [
            'course' => $course, 'enrolled'=>$courseRepository->studentEnrolled($course, $student)
        ]);
    }
    #[Route('/courses/{course}/enroll', name: 'app_student_course_enroll', methods: ["POST"])]
    public function CourseEnroll(ManagerRegistry $registry, Course $course): Response
    {
        /* @var $student Student*/
        $student = $this->getUser()->getStudent();
        $student->addCourse($course);
        $entityManager = $registry->getManager();
        $entityManager->persist($student);
        $entityManager->flush();
        return $this->redirectToRoute('app_student_course',['course'=> $course->getId()]);
    }
    #[Route('/assignments', name: 'app_student_assignments')]
    public function Assignments(): Response
    {
        return $this->render('learning/student/assignments.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }
    #[Route('/assignment/{assignment}', name: 'app_student_assignment')]
    public function Assignment(Assignment $assignment, CourseRepository $courseRepository, SubmissionRepository $submissionRepository): Response
    {
        /* @var $student Student*/
        $student = $this->getUser()->getStudent();
        $course = $assignment->getCourseWeek()->getCourse();
        $submission = $submissionRepository->findOneBy(["Student" => $student, "Assignment" => $assignment]);
        return $this->render('learning/student/assignment.html.twig', [
            'controller_name' => 'StudentController',
            'assignment'=> $assignment, 'enrolled'=>$courseRepository->studentEnrolled($course, $student),
            'submission' => $submission
        ]);
    }

    #[Route('/assignment/{assignment}/submit', name: 'app_student_assignment_submit')]
    public function AssignmentSubmit(Request $request, Assignment $assignment, SubmissionRepository $submissionRepository): Response
    {
        /* @var $student Student*/
        $student = $this->getUser()->getStudent();
        $submission = $submissionRepository->findOneBy(["Student" => $student, "Assignment" => $assignment]);
        if($submission){
            $submission->setFiler($request->request->get('file'));
        }
        else{
            $submission = new Submission();
            $submission->setFiler($request->request->get('file'));
            $submission->setAssignment($assignment);
            $submission->setStudent($student);
        }
        $submission->setSubmitDate(new \DateTimeImmutable());
        $submissionRepository->add($submission, true);
        return $this->redirectToRoute('app_student_assignment',['assignment'=> $assignment->getId()]);
    }
    #[Route('/lecture/{lecture}', name: 'app_student_lecture')]
    public function Lecture(Lecture $lecture, CourseRepository $courseRepository): Response
    {
        /* @var $student Student*/
        $student = $this->getUser()->getStudent();
        $course = $lecture->getCourseWeek()->getCourse();
        return $this->render('learning/student/lecture.html.twig', [
            'controller_name' => 'StudentController', 'lecture' => $lecture, 'enrolled'=>$courseRepository->studentEnrolled($course, $student)
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
