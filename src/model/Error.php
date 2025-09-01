<?php

namespace Solobea\Dashboard\model;

class Error
{
    private $id;
    private $title;
    private $message;
    private $status;
    private $cause_url;
    private $create_date;
    private $update_date;

    /**
     * Error constructor.
     * @param $title
     * @param $message
     * @param $cause_url
     */
    public function __construct($title, $message, $cause_url)
    {
        $this->title = $title;
        $this->message = $message;
        $this->cause_url = $cause_url;
    }


    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
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
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getCauseUrl()
    {
        return $this->cause_url;
    }

    /**
     * @param mixed $cause_url
     */
    public function setCauseUrl($cause_url): void
    {
        $this->cause_url = $cause_url;
    }

    /**
     * @return mixed
     */
    public function getCreateDate()
    {
        return $this->create_date;
    }

    /**
     * @param mixed $create_date
     */
    public function setCreateDate($create_date): void
    {
        $this->create_date = $create_date;
    }

    /**
     * @return mixed
     */
    public function getUpdateDate()
    {
        return $this->update_date;
    }

    /**
     * @param mixed $update_date
     */
    public function setUpdateDate($update_date): void
    {
        $this->update_date = $update_date;
    }

}