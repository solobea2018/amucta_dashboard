<?php


namespace Solobea\Dashboard\utils;


class Config
{
    public static array $ORG=[
        "name"=>"AMUCTA",
        "description"=>"Archbishp Mihayo University College of Tabora",
        "box"=>"P. O. Box 123",
        "address"=>"Tabora, Tanzania",
        "logo"=>"https://amucta.ac.tz/logo.png",
        "email"=>"amucta@amucta.ac.tz",
        "phone"=>"+255 123 123 1213",
    ];
    public static array $APP=[
        "title"=>"AMUCTA",
        "name"=>"AMUCTA",
        "channel"=>"channel_name",
        "api_key"=>"API Keys Here",
        "sms_sender"=> "0629077526",
    ];
    public static array $DB=[
        "db_name"=>"amuctaac_database",
        "db_host"=>"localhost",
        "db_user"=> "amuctaac_user",
        "db_pass"=> "",
    ];
    public static array $SMTP=[
        "server"=>"amucta.ac.tz",
        "password"=>"@eight8viiiH",
        "username"=> "no-reply@amucta.ac.tz",
        "port"=> 587,
    ];
}