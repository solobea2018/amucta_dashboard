<?php


namespace Solobea\Dashboard\model;

use Solobea\Dashboard\database\Database;

class Alumni
{
    private $id;
    private $full_name;
    private $email;
    private $phone;
    private $graduation_year;
    private $course;
    private $employment_status;
    private $message;
    private $create_date;
    private $update_date;

    public function __construct()
    {
    }
    public function getId() { return $this->id; }
    public function setFullName($name) { $this->full_name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); }
    public function getFullName() { return $this->full_name; }

    public function setEmail($email) { $this->email = filter_var($email, FILTER_SANITIZE_EMAIL); }
    public function getEmail() { return $this->email; }

    public function setPhone($phone) { $this->phone = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'); }
    public function getPhone() { return $this->phone; }

    public function setGraduationYear($year) { $this->graduation_year = $year; }
    public function getGraduationYear() { return $this->graduation_year; }

    public function setCourse($course) { $this->course = htmlspecialchars($course, ENT_QUOTES, 'UTF-8'); }
    public function getCourse() { return $this->course; }

    public function setEmploymentStatus($status) { $this->employment_status = htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); }
    public function getEmploymentStatus() { return $this->employment_status; }

    public function setMessage($message) { $this->message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); }
    public function getMessage() { return $this->message; }

    public function save(): bool
    {
        $db=new Database();
        return $db->save_alumni($this);
    }
}
