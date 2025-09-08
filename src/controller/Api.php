<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Go\errors\ErrorReporter;

class Api
{
    public function delete()
    {
        $auth = new Authentication();
        if (!$auth->is_admin()) {
            echo json_encode([
                "status" => "error",
                "message" => "Not authorized"
            ]);
            return;
        }

        // Read JSON body
        $input = json_decode(file_get_contents("php://input"), true);

        if (isset($input['table']) && isset($input['id'])) {
            try {
                $id = intval($input['id']);
                $table = preg_replace('/[^a-zA-Z0-9_]/', '', $input['table']); // sanitize

                $db = new Database();
                if ($db->delete($table, ['id' => $id])) {
                    echo json_encode([
                        "status" => "success",
                        "message" => "Delete successful"
                    ]);
                } else {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Delete failed"
                    ]);
                }
            } catch (\Exception $exception) {
                ErrorReporter::report("Delete {$input['table']} failed", $exception->getMessage());
                echo json_encode([
                    "status" => "error",
                    "message" => "Failed with exception"
                ]);
            }
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Missing parameters"
            ]);
        }
    }

    public function update()
    {
        $auth = new Authentication();
        if (!$auth->is_admin()) {
            echo json_encode([
                "status" => "error",
                "message" => "Not authorized"
            ]);
            return;
        }

        // Read JSON body
        $input = json_decode(file_get_contents("php://input"), true);

        if (isset($input['table']) && isset($input['id']) && isset($input['data'])) {
            try {
                $id = intval($input['id']);
                $table = preg_replace('/[^a-zA-Z0-9_]/', '', $input['table']); // sanitize
                $data = $input['data']; // associative array: ["column" => "value"]

                if (!is_array($data) || empty($data)) {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Invalid data format"
                    ]);
                    return;
                }

                $db = new Database();
                if ($db->update($table, $data, ['id' => $id])) {
                    echo json_encode([
                        "status" => "success",
                        "message" => "Update successful"
                    ]);
                } else {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Update failed"
                    ]);
                }
            } catch (\Exception $exception) {
                ErrorReporter::report("Update {$input['table']} failed", $exception->getMessage());
                echo json_encode([
                    "status" => "error",
                    "message" => "Failed with exception"
                ]);
            }
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Missing parameters"
            ]);
        }
    }

}