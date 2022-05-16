<?php

namespace App\Entity;

use App\Repository\InstructorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstructorRepository::class)]
class Instructor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToMany(targetEntity: Course::class, mappedBy: 'Instructors')]
    private $courses;

    #[ORM\OneToOne(mappedBy: 'CourseHead', targetEntity: Course::class, cascade: ['persist', 'remove'])]
    private $course;

    #[ORM\OneToMany(mappedBy: 'CourseHead', targetEntity: Course::class)]
    private $mycourses;

    #[ORM\OneToOne(inversedBy: 'instructor', targetEntity: User::class, cascade: ['persist', 'remove'])]
    private $User;

    #[ORM\ManyToOne(targetEntity: Department::class, inversedBy: 'Instructors')]
    private $department;

    #[ORM\OneToMany(mappedBy: 'Instructor', targetEntity: Assignment::class)]
    private $assignments;

    #[ORM\ManyToMany(targetEntity: Lecture::class, mappedBy: 'Instructors')]
    private $lectures;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->mycourses = new ArrayCollection();
        $this->assignments = new ArrayCollection();
        $this->lectures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses[] = $course;
            $course->addInstructor($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): self
    {
        if ($this->courses->removeElement($course)) {
            $course->removeInstructor($this);
        }

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        // unset the owning side of the relation if necessary
        if ($course === null && $this->course !== null) {
            $this->course->setCourseHead(null);
        }

        // set the owning side of the relation if necessary
        if ($course !== null && $course->getCourseHead() !== $this) {
            $course->setCourseHead($this);
        }

        $this->course = $course;

        return $this;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getMycourses(): Collection
    {
        return $this->mycourses;
    }

    public function addMycourse(Course $mycourse): self
    {
        if (!$this->mycourses->contains($mycourse)) {
            $this->mycourses[] = $mycourse;
            $mycourse->setCourseHead($this);
        }

        return $this;
    }

    public function removeMycourse(Course $mycourse): self
    {
        if ($this->mycourses->removeElement($mycourse)) {
            // set the owning side to null (unless already changed)
            if ($mycourse->getCourseHead() === $this) {
                $mycourse->setCourseHead(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return Collection<int, Assignment>
     */
    public function getAssignments(): Collection
    {
        return $this->assignments;
    }

    public function addAssignment(Assignment $assignment): self
    {
        if (!$this->assignments->contains($assignment)) {
            $this->assignments[] = $assignment;
            $assignment->setInstructor($this);
        }

        return $this;
    }

    public function removeAssignment(Assignment $assignment): self
    {
        if ($this->assignments->removeElement($assignment)) {
            // set the owning side to null (unless already changed)
            if ($assignment->getInstructor() === $this) {
                $assignment->setInstructor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Lecture>
     */
    public function getLectures(): Collection
    {
        return $this->lectures;
    }

    public function addLecture(Lecture $lecture): self
    {
        if (!$this->lectures->contains($lecture)) {
            $this->lectures[] = $lecture;
            $lecture->addInstructor($this);
        }

        return $this;
    }

    public function removeLecture(Lecture $lecture): self
    {
        if ($this->lectures->removeElement($lecture)) {
            $lecture->removeInstructor($this);
        }

        return $this;
    }
}
