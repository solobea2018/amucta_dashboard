<?php


namespace Solobea\Dashboard\utils;


use JetBrains\PhpStorm\ArrayShape;
use Solobea\Dashboard\database\Database;

class EmployeeHelper
{
    #[ArrayShape(['query' => "", 'ids' => "array", 'email' => ""])]
    public static function search_employee($q, $email): array
    {
        $ids=[];
        $db=Database::get_instance();
        $stmt=$db->getCon()->stmt_init();
        $query="select id from employee where match(name) against(?) ";
        if ($stmt->prepare($query)){
            $stmt->bind_param("s",$q);
            if ($stmt->execute()){
                $res=$stmt->get_result();

                if ($res->num_rows>0){
                    $results=$res->fetch_all(MYSQLI_ASSOC);
                    foreach ($results as $result) {
                        $ids[]=$result['id'];
                    }
                }
            }
        }
        return ['query'=>$q,'ids'=>$ids,'email'=>$email];
    }
}