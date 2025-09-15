<?php


namespace Solobea\Dashboard\model;

class Program
{
    private $id;
    private $name;
    private $short_name;
    private $intakes;
    private $duration;
    private $capacity;
    private $accreditation_year;
    private $faculty_id;
    private $department_id;
    private $level_id;
    private $description;
    private $content;
    private $fees;
    private $requirements;

    /**
     * @return mixed
     */
    public function getFees()
    {
        return $this->fees;
    }

    /**
     * @param mixed $fees
     */
    public function setFees($fees): void
    {
        $this->fees = $fees;
    }

    /**
     * @return mixed
     */
    public function getRequirements()
    {
        return $this->requirements;
    }

    /**
     * @param mixed $requirements
     */
    public function setRequirements($requirements): void
    {
        $this->requirements = $requirements;
    }
    private $created_by;
    private $created_at;

    // --- Getters & Setters ---
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; }

    public function getShortName() { return $this->short_name; }
    public function setShortName($short_name) { $this->short_name = $short_name; }

    public function getIntakes() { return $this->intakes; }
    public function setIntakes($intakes) { $this->intakes = $intakes; }

    public function getDuration() { return $this->duration; }
    public function setDuration($duration) { $this->duration = $duration; }

    public function getCapacity() { return $this->capacity; }
    public function setCapacity($capacity) { $this->capacity = $capacity; }

    public function getAccreditationYear() { return $this->accreditation_year; }
    public function setAccreditationYear($year) { $this->accreditation_year = $year; }

    public function getFacultyId() { return $this->faculty_id; }
    public function setFacultyId($faculty_id) { $this->faculty_id = $faculty_id; }

    public function getDepartmentId() { return $this->department_id; }
    public function setDepartmentId($department_id) { $this->department_id = $department_id; }

    public function getLevelId() { return $this->level_id; }
    public function setLevelId($level_id) { $this->level_id = $level_id; }

    public function getDescription() { return $this->description; }
    public function setDescription($description) { $this->description = $description; }

    public function getContent() { return $this->content; }
    public function setContent($content) { $this->content = $content; }

    public function getCreatedBy() { return $this->created_by; }
    public function setCreatedBy($user_id) { $this->created_by = $user_id; }

    public function getCreatedAt() { return $this->created_at; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }
}
