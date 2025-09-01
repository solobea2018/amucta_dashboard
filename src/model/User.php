<?php

namespace Solobea\Dashboard\model;

class User
{
    private $id;
    private $full_name;
    private $username;
    private $email;
    private $profile_url;
    private $phone_number;
    private $role;
    private $password;

    /**
     * @return bool
     */
    public function isBurned(): bool
    {
        return $this->burned;
    }

    /**
     * @param bool $burned
     */
    public function setBurned(bool $burned): void
    {
        $this->burned = $burned;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
    private bool $burned;
    private bool $active;
    private $recovery_question;
    private $recovery_answer_hash;
    private bool $verified;
    private $created_at;
    private $updated_at;

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->verified;
    }

    /**
     * @param bool $verified
     */
    public function setVerified(bool $verified): void
    {
        $this->verified = $verified;
    }


    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role): void
    {
        $this->role = $role;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->full_name;
    }

    /**
     * @param mixed $full_name
     */
    public function setFullName($full_name): void
    {
        $this->full_name = $full_name;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getProfileUrl()
    {
        return $this->profile_url;
    }

    /**
     * @param mixed $profile_url
     */
    public function setProfileUrl($profile_url): void
    {
        $this->profile_url = $profile_url;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }

    /**
     * @param mixed $phone_number
     */
    public function setPhoneNumber($phone_number): void
    {
        $this->phone_number = $phone_number;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getRecoveryQuestion()
    {
        return $this->recovery_question;
    }

    /**
     * @param mixed $recovery_question
     */
    public function setRecoveryQuestion($recovery_question): void
    {
        $this->recovery_question = $recovery_question;
    }

    /**
     * @return mixed
     */
    public function getRecoveryAnswerHash()
    {
        return $this->recovery_answer_hash;
    }

    /**
     * @param mixed $recovery_answer_hash
     */
    public function setRecoveryAnswerHash($recovery_answer_hash): void
    {
        $this->recovery_answer_hash = $recovery_answer_hash;
    }


}