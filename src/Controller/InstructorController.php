<?php

namespace App\Controller;

use App\Entity\Assignment;
use App\Entity\Course;
use App\Entity\CourseWeek;
use App\Entity\Department;
use App\Entity\Lecture;
use App\Entity\Instructor;
use App\Entity\Submission;
use App\Repository\AssignmentRepository;
use App\Repository\CourseRepository;
use App\Repository\CourseWeekRepository;
use App\Repository\DepartmentRepository;
use App\Repository\FacultyRepository;
use App\Repository\InstructorRepository;
use App\Repository\LectureRepository;
use App\Repository\SubmissionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/instructor')]
class InstructorController extends AbstractController
{
    #[Route('/', name: 'app_instructor')]
    public function index(): Response
    {
        return $this->render('learning/instructor/index.html.twig', [
            'controller_name' => 'InstructorController',
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
    #[Route('/course/{course}', name: 'app_instructor_course')]
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
    #[Route('/courses/{course}/create_week', name: 'app_course_week_create', methods: ["POST"])]
    public function CourseWeekCreate(Request $request, Course $course, CourseWeekRepository $courseWeekRepository): Response
    {
        /* @var $instructor Instructor*/
        if($request->request->get('cardinality')){
            if($courseWeekRepository->findBy(['course'=> $course, 'cardinality'=> $request->request->get('cardinality')])){
                return $this->redirectToRoute('app_instructor_course',['course'=> $course->getId()]);
            }
            else{
                $courseWeek = new CourseWeek();
                $courseWeek->setCourse($course);
                $courseWeek->setCardinality($request->request->get('cardinality'));
                $courseWeekRepository->add($courseWeek, true);
            }
        }
        return $this->redirectToRoute('app_instructor_course',['course'=> $course->getId()]);
    }
    #[Route('/courses/create', name: 'app_course_create', methods: ["POST"])]
    public function CourseCreate(Request $request, CourseRepository $courseRepository, InstructorRepository $instructorRepository): Response
    {
        /* @var $instructor Instructor*/
        $instructor = $this->getUser()->getInstructor();
        if($request->request->get('title') && is_null($courseRepository->findOneBy(['Department'=>$instructor->getDepartment(), 'Title'=>$request->request->get('title')]))){
                $course = new Course;
                $course->setTitle($request->request->get('title'));
                $course->setDescription($request->request->get('description'));
                $course->setDepartment($instructor->getDepartment());
                $course->setCourseHead($instructor);
                foreach ($request->request->all('instructors') as $instructor)
                    if($instructor)
                        $course->addInstructor($instructorRepository->findOneBy(['id'=> $instructor]));
                $courseRepository->add($course, true);
            return $this->redirectToRoute('app_instructor_course',['course'=> $course->getId()]);
            }
        return $this->redirectToRoute('app_instructor_courses');
    }

    #[Route('/courses/{course}/create_lecture', name: 'app_course_lecture_create', methods: ["POST"])]
    public function LectureCreate(LoggerInterface $logger, Request $request,Course $course, CourseWeekRepository $courseWeekRepository,LectureRepository $lectureRepository, InstructorRepository $instructorRepository): Response
    {
        /* @var $instructor Instructor*/
        if($request->request->get('title')){
            $courseWeek = $courseWeekRepository->findOneBy(['id' => $request->request->get('week')]);
            if(is_null($courseWeek) || $lectureRepository->findBy(['title'=> $request->request->get('title'), 'CourseWeek'=> $courseWeek])){

                return $this->redirectToRoute('app_instructor_course',['course'=> $course->getId()]);
            }
            else{
                $lecture = new Lecture;
                $lecture->setCourseWeek($courseWeek);
                $lecture->setTitle($request->request->get('title'));
                $lecture->setDescription($request->request->get('description'));
                $lecture->setFile($request->request->get('file'));
                $lecture->setVideo($request->request->get('video'));
                foreach ($request->request->all('instructors') as $instructor)
                    if($instructor)
                $lecture->addInstructor($instructorRepository->findOneBy(['id'=> $instructor]));
                $lectureRepository->add($lecture, true);
            }
        }
        return $this->redirectToRoute('app_instructor_course',['course'=> $course->getId()]);
    }
    #[Route('/courses/{lecture}/modify_lecture', name: 'app_course_lecture_modify', methods: ["POST"])]
    public function LectureModify(LoggerInterface $logger, Request $request,Lecture $lecture, LectureRepository $lectureRepository): Response
    {
        /* @var $instructor Instructor*/
        if($request->request->get('title')){
                $lecture->setTitle($request->request->get('title'));
                $lecture->setDescription($request->request->get('description'));
                $lecture->setFile($request->request->get('file'));
                $lecture->setVideo($request->request->get('video'));
                $lectureRepository->add($lecture, true);
        }
        return $this->redirectToRoute('app_instructor_lecture',['lecture'=> $lecture->getId()]);
    }
    #[Route('/courses/{course}/create_assignment', name: 'app_course_assignment_create', methods: ["POST"])]
    public function AssignmentCreate(LoggerInterface $logger, Request $request,Course $course, CourseWeekRepository $courseWeekRepository,AssignmentRepository $assignmentRepository): Response
    {
        /* @var $instructor Instructor*/
        if($request->request->get('title')){
            $courseWeek = $courseWeekRepository->findOneBy(['id' => $request->request->get('week')]);
            if(is_null($courseWeek) || $assignmentRepository->findBy(['Title'=> $request->request->get('title'), 'courseWeek'=> $courseWeek])){

                return $this->redirectToRoute('app_instructor_course',['course'=> $course->getId()]);
            }
            else{
                $assignment = new Assignment();
                $assignment->setCourseWeek($courseWeek);
                $assignment->setCourse($courseWeek->getCourse());
                $assignment->setTitle($request->request->get('title'));
                $assignment->setDescription($request->request->get('description'));
                $assignment->setFile($request->request->get('file'));
                $assignment->setInstructor($this->getUser()->getInstructor());
                $assignmentRepository->add($assignment, true);
            }
        }
        return $this->redirectToRoute('app_instructor_course',['course'=> $course->getId()]);
    }
    #[Route('/courses/{assignment}/modify_assignment', name: 'app_course_assignment_modify', methods: ["POST"])]
    public function AssignmentModify(LoggerInterface $logger, Request $request, Assignment $assignment, AssignmentRepository $assignmentRepository): Response
    {
        /* @var $instructor Instructor*/
        if($request->request->get('title')){
                $assignment->setTitle($request->request->get('title'));
                $assignment->setDescription($request->request->get('description'));
                $assignment->setFile($request->request->get('file'));
                $assignmentRepository->add($assignment, true);
        }
        return $this->redirectToRoute('app_instructor_assignment',['assignment'=> $assignment->getId()]);
    }

    #[Route('/courses/{submission}/grade_assignment', name: 'app_course_submission_grade', methods: ["POST"])]
    public function AssignmentGrade(LoggerInterface $logger, Request $request, Submission $submission, SubmissionRepository $submissionRepository): Response
    {
        /* @var $instructor Instructor*/
        if($request->request->get('grade') && $submission->getAssignment()->getInstructor()->getId() === $this->getUser()->getInstructor()->getId()){
                $submission->setGrade($request->request->get('grade'));
                $submissionRepository->add($submission, true);
        }
        return $this->redirectToRoute('app_instructor_assignment',['assignment'=> $submission->getAssignment()->getId()]);
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
        $submission = $submissionRepository->findOneBy([ "Assignment" => $assignment]);
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
    public function isCourseInstructor(Course $course, Instructor $instructor): bool{
        foreach ($course->getInstructors() as $instructor_){
            if($instructor->getId() === $instructor_->getId())
                return true;
        }
        return false;
    }
}
