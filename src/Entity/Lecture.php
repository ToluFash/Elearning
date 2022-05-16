<?php

namespace App\Entity;

use App\Repository\LectureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LectureRepository::class)]
class Lecture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\Column(type: 'text', nullable: true)]
    private $video;

    #[ORM\Column(type: 'text', nullable: true)]
    private $file;

    #[ORM\ManyToMany(targetEntity: Instructor::class, inversedBy: 'lectures')]
    private $Instructors;

    #[ORM\ManyToOne(targetEntity: CourseWeek::class, inversedBy: 'lectures')]
    private $CourseWeek;

    public function __construct()
    {
        $this->Instructors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(?string $video): self
    {
        $this->video = $video;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;

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
        }

        return $this;
    }

    public function removeInstructor(Instructor $instructor): self
    {
        $this->Instructors->removeElement($instructor);

        return $this;
    }

    public function getCourseWeek(): ?CourseWeek
    {
        return $this->CourseWeek;
    }

    public function setCourseWeek(?CourseWeek $CourseWeek): self
    {
        $this->CourseWeek = $CourseWeek;

        return $this;
    }
}
