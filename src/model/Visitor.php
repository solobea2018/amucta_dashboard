<?php

namespace Solobea\Dashboard\model;
class Visitor
{
    private $id;
    private $ip;
    private $ip_type;
    private $continent;
    private $country;
    private $region;
    private $city;
    private $date;
    private $isp;
    private $url;
    private $is_registered;
    private $visitor_session;

    /**
     * Visitors constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getVisitorSession()
    {
        return $this->visitor_session;
    }

    /**
     * @param mixed $visitor_session
     */
    public function setVisitorSession($visitor_session): void
    {
        $this->visitor_session = $visitor_session;
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
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip): void
    {
        $this->ip = $ip;
    }

    /**
     * @return mixed
     */
    public function getIpType()
    {
        return $this->ip_type;
    }

    /**
     * @param mixed $ip_type
     */
    public function setIpType($ip_type): void
    {
        $this->ip_type = $ip_type;
    }

    /**
     * @return mixed
     */
    public function getContinent()
    {
        return $this->continent;
    }

    /**
     * @param mixed $continent
     */
    public function setContinent($continent): void
    {
        $this->continent = $continent;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country): void
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param mixed $region
     */
    public function setRegion($region): void
    {
        $this->region = $region;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getIsp()
    {
        return $this->isp;
    }

    /**
     * @param mixed $isp
     */
    public function setIsp($isp): void
    {
        $this->isp = $isp;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url): void
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getIsRegistered()
    {
        return $this->is_registered;
    }

    /**
     * @param mixed $is_registered
     */
    public function setIsRegistered($is_registered): void
    {
        $this->is_registered = $is_registered;
    }



}