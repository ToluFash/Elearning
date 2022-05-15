<?php

namespace App\Entity;

use App\Repository\FacultyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FacultyRepository::class)]
class Faculty
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Name;

    #[ORM\OneToMany(mappedBy: 'faculty', targetEntity: Department::class)]
    private Collection $Departments;

    public function __construct()
    {
        $this->Departments = new ArrayCollection();
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
     * @return Collection<int, Department>
     */
    public function getDepartments(): Collection
    {
        return $this->Departments;
    }

    public function addDepartment(Department $department): self
    {
        if (!$this->Departments->contains($department)) {
            $this->Departments[] = $department;
            $department->setFaculty($this);
        }

        return $this;
    }

    public function removeDepartment(Department $department): self
    {
        if ($this->Departments->removeElement($department)) {
            // set the owning side to null (unless already changed)
            if ($department->getFaculty() === $this) {
                $department->setFaculty(null);
            }
        }

        return $this;
    }
    public function __toString(){
        return $this->getName();
    }

}
