<?php

namespace App\Controller;

use App\Entity\Assignment;
use App\Entity\Course;
use App\Entity\Department;
use App\Entity\Lecture;
use App\Entity\Instructor;
use App\Entity\Submission;
use App\Repository\AssignmentRepository;
use App\Repository\CourseRepository;
use App\Repository\DepartmentRepository;
use App\Repository\FacultyRepository;
use App\Repository\InstructorRepository;
use App\Repository\SubmissionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/instructor')]
class InstructorController extends AbstractController
{
    #[Route('/', name: 'app_instructor')]
    public function index(AssignmentRepository $assignmentRepository): Response
    {
        return $this->render('learning/instructor/index.html.twig', [
            'controller_name' => 'InstructorController',
            'assignments' => $assignmentRepository->findPendingAssignmentsForInstructor($this->getUser()->getInstructor())
        ]);
    }
    #[Route('/courses', name: 'app_instructor_courses')]
    public function Courses(): Response
    {
        return $this->render('learning/instructor/courses.html.twig', [
            'controller_name' => 'InstructorController',
        ]);
    }
    #[Route('/courses/search', name: 'app_instructor_courses_search', methods: ["POST"])]
    public function SearchCourses(Request $request, CourseRepository $courseRepository): Response
    {
        return $this->render('learning/instructor/courses_search.html.twig', [
            'courses' => $courseRepository->findCourses($request->request->get('searchTerm'))
        ]);
    }
    #[Route('/courses/{course}', name: 'app_instructor_course')]
    public function Course(Course $course, CourseRepository $courseRepository): Response
    {
        /* @var $instructor Instructor*/
        $instructor = $this->getUser()->getInstructor();
        return $this->render('learning/instructor/course.html.twig', [
            'course' => $course, 'enrolled'=>$courseRepository->instructorEnrolled($course, $instructor)
        ]);
    }
    #[Route('/courses/{course}/enroll', name: 'app_instructor_course_enroll', methods: ["POST"])]
    public function CourseEnroll(ManagerRegistry $registry, Course $course): Response
    {
        /* @var $instructor Instructor*/
        $instructor = $this->getUser()->getInstructor();
        $instructor->addCourse($course);
        $entityManager = $registry->getManager();
        $entityManager->persist($instructor);
        $entityManager->flush();
        return $this->redirectToRoute('app_instructor_course',['course'=> $course->getId()]);
    }
    #[Route('/assignments', name: 'app_instructor_assignments')]
    public function Assignments(): Response
    {
        return $this->render('learning/instructor/assignments.html.twig', [
            'controller_name' => 'InstructorController',
        ]);
    }
    #[Route('/assignment/{assignment}', name: 'app_instructor_assignment')]
    public function Assignment(Assignment $assignment, CourseRepository $courseRepository, SubmissionRepository $submissionRepository): Response
    {
        /* @var $instructor Instructor*/
        $instructor = $this->getUser()->getInstructor();
        $course = $assignment->getCourseWeek()->getCourse();
        $submission = $submissionRepository->findOneBy(["Instructor" => $instructor, "Assignment" => $assignment]);
        return $this->render('learning/instructor/assignment.html.twig', [
            'controller_name' => 'InstructorController',
            'assignment'=> $assignment, 'enrolled'=>$courseRepository->instructorEnrolled($course, $instructor),
            'submission' => $submission
        ]);
    }

    #[Route('/assignment/{assignment}/submit', name: 'app_instructor_assignment_submit')]
    public function AssignmentSubmit(Request $request, Assignment $assignment, SubmissionRepository $submissionRepository): Response
    {
        /* @var $instructor Instructor*/
        $instructor = $this->getUser()->getInstructor();
        $submission = $submissionRepository->findOneBy(["Instructor" => $instructor, "Assignment" => $assignment]);
        if($submission){
            $submission->setFiler($request->request->get('file'));
        }
        else{
            $submission = new Submission();
            $submission->setFiler($request->request->get('file'));
            $submission->setAssignment($assignment);
            $submission->setInstructor($instructor);
        }
        $submission->setSubmitDate(new \DateTimeImmutable());
        $submissionRepository->add($submission, true);
        return $this->redirectToRoute('app_instructor_assignment',['assignment'=> $assignment->getId()]);
    }
    #[Route('/lecture/{lecture}', name: 'app_instructor_lecture')]
    public function Lecture(Lecture $lecture, CourseRepository $courseRepository): Response
    {
        /* @var $instructor Instructor*/
        $instructor = $this->getUser()->getInstructor();
        $course = $lecture->getCourseWeek()->getCourse();
        return $this->render('learning/instructor/lecture.html.twig', [
            'controller_name' => 'InstructorController', 'lecture' => $lecture, 'enrolled'=>$courseRepository->instructorEnrolled($course, $instructor)
        ]);
    }
    #[Route('/enroll', name: 'app_instructor_enroll')]
    public function Enroll(Request $request, DepartmentRepository $departmentRepository, FacultyRepository $facultyRepository, InstructorRepository $instructorRepository): Response
    {
        $department = $request->request->get('department');
        if($department){
            $department = $departmentRepository->find($department);
            $instructor = new Instructor();
            $instructor->setDepartment($department);
            $instructor->setUser($this->getUser());
            $instructorRepository->add($instructor, true);
            return $this->redirectToRoute('app_instructor');
        }

        return $this->render('learning/instructor/enroll.html.twig', [
            'controller_name' => 'InstructorController',
            'faculties' => $facultyRepository->findAll(),
            'departments' => $departmentRepository->findAll()
        ]);
    }
}
