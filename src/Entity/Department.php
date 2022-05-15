<?php

namespace App\Entity;

use App\Repository\DepartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepartmentRepository::class)]
class Department
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $Name;

    #[ORM\OneToMany(mappedBy: 'Department', targetEntity: Course::class)]
    private ?Collection $courses;

    #[ORM\OneToMany(mappedBy: 'department', targetEntity: Student::class)]
    private Collection $Students;

    #[ORM\OneToMany(mappedBy: 'department', targetEntity: Instructor::class)]
    private Collection $Instructors;

    #[ORM\ManyToOne(targetEntity: Faculty::class, inversedBy: 'Departments')]
    private ?Faculty $faculty;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->Students = new ArrayCollection();
        $this->Instructors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
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
            $course->setDepartment($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): self
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getDepartment() === $this) {
                $course->setDepartment(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection<int, Student>
     */
    public function getStudents(): Collection
    {
        return $this->Students;
    }

    public function addStudent(Student $student): self
    {
        if (!$this->Students->contains($student)) {
            $this->Students[] = $student;
            $student->setDepartment($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        if ($this->Students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getDepartment() === $this) {
                $student->setDepartment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Instructor>
     */
    public function getInstructors(): Collection
    {
        return $this->Instructors;
    }

    public function addInstructor(Instructor $instructor): self
    {
        if (!$this->Instructors->contains($instructor)) {
            $this->Instructors[] = $instructor;
            $instructor->setDepartment($this);
        }

        return $this;
    }

    public function removeInstructor(Instructor $instructor): self
    {
        if ($this->Instructors->removeElement($instructor)) {
            // set the owning side to null (unless already changed)
            if ($instructor->getDepartment() === $this) {
                $instructor->setDepartment(null);
            }
        }

        return $this;
    }

    public function getFaculty(): ?Faculty
    {
        return $this->faculty;
    }

    public function setFaculty(?Faculty $faculty): self
    {
        $this->faculty = $faculty;

        return $this;
    }
}
