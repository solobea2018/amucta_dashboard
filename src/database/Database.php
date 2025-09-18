<?php
namespace Solobea\Dashboard\database;
use DateTime;
use Exception;
use mysqli;
use Solobea\Dashboard\model\Alumni;
use Solobea\Dashboard\model\Contact;
use Solobea\Dashboard\model\Donation;
use Solobea\Dashboard\model\Error;
use Solobea\Dashboard\model\Program;
use Solobea\Dashboard\model\User;
use Solobea\Dashboard\model\Visitor;
use Solobea\Dashboard\utils\Helper;
use Solobea\Dashboard\utils\ErrorReporter;

class Database
{
    private $host="localhost";
    //private $host="data.tetea.store";

    private $user="amucta_user";
    private $db="dashboard";
    private $pass="@eight8viiiH";

    private $con;

    /**
     * @return mysqli
     */
    public function getCon(): mysqli
    {
        return $this->con;
    }

    public function __construct()
    {
        $this->con = new mysqli($this->host, $this->user,$this->pass,$this->db);
    }

    //Todo errors
    public function report_error(Error $error): bool
    {
        $stmt = $this->con->stmt_init();
        $query = "INSERT INTO errors (title, message, cause_url) VALUES (?, ?, ?)";

        if ($stmt->prepare($query)) {
            // Correctly assigning values from the Error object
            $title = $error->getTitle();
            $message = $error->getMessage();
            $cause_url = $error->getCauseUrl();

            // Bind parameters
            $stmt->bind_param("sss", $title, $message, $cause_url);

            // Execute statement and check for success
            $success = $stmt->execute();

            // Clean up
            $stmt->close();

            return $success;  // Return true if successful, false otherwise
        } else {
            // Handle the error if statement preparation fails
            error_log("Failed to prepare statement: " . $stmt->error);
            return false;
        }
    }
    public function getErrors($status = null): array
    {
        $stmt = $this->con->stmt_init();
        $errors = [];

        if (isset($status)) {
            $query = "SELECT * FROM errors WHERE status = ? order by id desc ";

            if ($stmt->prepare($query)) {
                $stmt->bind_param("s", $status);
                $stmt->execute();
                $result = $stmt->get_result();

                // Fetch all rows as associative array
                $errors = $result->fetch_all(MYSQLI_ASSOC);
            }
        } else {
            $query = "SELECT * FROM errors order by id desc"; // Corrected table name

            if ($stmt->prepare($query)) {
                $stmt->execute();
                $result = $stmt->get_result();

                // Fetch all rows as associative array
                $errors = $result->fetch_all(MYSQLI_ASSOC);
            }
        }

        // Clean up statement resources
        $stmt->close();

        return $errors;
    }
    public function getError($id): array
    {
        $stmt = $this->con->stmt_init();
        $query = "SELECT * FROM errors WHERE id = ?";

        if ($stmt->prepare($query)) {
            $stmt->bind_param("i", $id); // Make sure $id is a string, or use "i" if it's an integer
            $stmt->execute();
            $error= $stmt->get_result()->fetch_assoc();
            // Clean up resources
            $stmt->close();
        } else {
            // Could not prepare statement, return null
            $error = null;
        }

        return $error;
    }
    public function read_error(array $ids): bool
    {
        // Check if the array is empty; return false if so
        if (empty($ids)) {
            return false;
        }

        // Generate placeholders for each ID in the IN clause
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $query = "UPDATE errors SET status = ? WHERE id IN ($placeholders)";

        $stmt = $this->con->stmt_init();
        if ($stmt->prepare($query)) {
            // First parameter is for the "status" field; the rest are for the IDs
            $status = "resolved";
            $types = 's' . str_repeat('i', count($ids)); // 's' for status, 'i' for each ID
            $stmt->bind_param($types, $status, ...$ids); // Bind status and IDs

            // Execute and check for success
            $success = $stmt->execute();

            // Clean up resources
            $stmt->close();

            return $success;
        } else {
            return false;
        }
    }
    public function delete_error(array $ids): bool
    {
        // Check if the array is empty, return false if so
        if (empty($ids)) {
            return false;
        }

        // Dynamically build placeholders for the IN clause
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $query = "DELETE FROM errors WHERE id IN ($placeholders)";

        $stmt = $this->con->stmt_init();
        if ($stmt->prepare($query)) {
            // Bind parameters dynamically
            $types = str_repeat('i', count($ids)); // Assuming all IDs are integers
            $stmt->bind_param($types, ...$ids); // Unpack $ids array into individual arguments

            // Execute and check for success
            $success = $stmt->execute();

            // Clean up resources
            $stmt->close();

            return $success;
        } else {
            return false;
        }
    }

    //Todo contacts
    public function save_contact(Contact $contact): bool
    {
        $stmt = $this->con->stmt_init();
        $query = "INSERT INTO contacts (full_name,title,email,message) value (?,?,?,?)";

        if ($stmt->prepare($query)) {
            // Correctly assigning values from the Error object
            $full_name = $contact->getFullName();
            $message = $contact->getMessage();
            $email = $contact->getEmail();
            $title = $contact->getTitle();

            // Bind parameters
            $stmt->bind_param("ssss", $full_name,$title,$email, $message);

            // Execute statement and check for success
            $success = $stmt->execute();

            // Clean up
            $stmt->close();

            return $success;  // Return true if successful, false otherwise
        } else {
            // Handle the error if statement preparation fails
            return false;
        }
    }

    public function getContacts($status = null): array
    {
        $stmt = $this->con->stmt_init();
        $errors = [];

        if (isset($status)) {
            $query = "SELECT * FROM contacts WHERE read_status = ?";

            if ($stmt->prepare($query)) {
                $stmt->bind_param("s", $status);
                $stmt->execute();
                $result = $stmt->get_result();

                // Fetch all rows as associative array
                $errors = $result->fetch_all(MYSQLI_ASSOC);
            }
        } else {
            $query = "SELECT * FROM contacts"; // Corrected table name

            if ($stmt->prepare($query)) {
                $stmt->execute();
                $result = $stmt->get_result();

                // Fetch all rows as associative array
                $errors = $result->fetch_all(MYSQLI_ASSOC);
            }
        }

        // Clean up statement resources
        $stmt->close();

        return $errors;
    }
    public function getContact($id): array
    {
        $stmt = $this->con->stmt_init();
        $query = "SELECT * FROM contacts WHERE id = ?";

        if ($stmt->prepare($query)) {
            $stmt->bind_param("i", $id); // Make sure $id is a string, or use "i" if it's an integer
            $stmt->execute();
            $error= $stmt->get_result()->fetch_assoc();
            // Clean up resources
            $stmt->close();
        } else {
            // Could not prepare statement, return null
            $error = null;
        }

        return $error;
    }
    public function read_contact(array $ids): bool
    {
        // Check if the array is empty; return false if so
        if (empty($ids)) {
            return false;
        }

        // Generate placeholders for each ID in the IN clause
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $query = "UPDATE contacts SET read_status = ? WHERE id IN ($placeholders)";

        $stmt = $this->con->stmt_init();
        if ($stmt->prepare($query)) {
            // First parameter is for the "status" field; the rest are for the IDs
            $status = "read";
            $types = 's' . str_repeat('i', count($ids)); // 's' for status, 'i' for each ID
            $stmt->bind_param($types, $status, ...$ids); // Bind status and IDs

            // Execute and check for success
            $success = $stmt->execute();

            // Clean up resources
            $stmt->close();

            return $success;
        } else {
            return false;
        }
    }
    public function delete_contact(array $ids): bool
    {
        // Check if the array is empty, return false if so
        if (empty($ids)) {
            return false;
        }

        // Dynamically build placeholders for the IN clause
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $query = "DELETE FROM contacts WHERE id IN ($placeholders)";

        $stmt = $this->con->stmt_init();
        if ($stmt->prepare($query)) {
            // Bind parameters dynamically
            $types = str_repeat('i', count($ids)); // Assuming all IDs are integers
            $stmt->bind_param($types, ...$ids); // Unpack $ids array into individual arguments

            // Execute and check for success
            $success = $stmt->execute();

            // Clean up resources
            $stmt->close();

            return $success;
        } else {
            return false;
        }
    }

    //todo users queries
    public function find_user_by_id($id): ?User

    {

        $query = "SELECT * from users where id=?";

        $stmt = $this->con->stmt_init();

        if (!$stmt->prepare($query)) {
            ErrorReporter::report("Database error",$this->con->error,$_SERVER['REQUEST_URI']);
            return null;

        } else {

            $stmt->bind_param("s", $id);

            if ($stmt->execute()) {

                $result = $stmt->get_result();

                $stmt->close();

                if (!$result) {

                    return null;

                } else {

                    $row = $result->fetch_assoc();

                    if (!($row == null)) {

                        $user = new User();

                        $user->setFullName($row["full_name"]);

                        $user->setUsername($row["username"]);

                        $user->setId($row["id"]);

                        $user->setProfileUrl($row["profile_url"]);

                        $user->setEmail($row["email"]);
                        $user->setRole($row["role"]);
                        $user->setVerified($row["verified"]);

                        return $user;

                    } else return null;

                }

            } else {
                ErrorReporter::report("Database error",$this->con->error,$_SERVER['REQUEST_URI']);
                return null;
            }

        }

    }
    public function find_user_by_username($username): ?User
    {
        $query = "SELECT * from users where username=?";

        $stmt = $this->con->stmt_init();
        if (!$stmt->prepare($query)) {
            ErrorReporter::report("Database error", $this->con->error, $_SERVER['REQUEST_URI']);
            return null;
        } else {
            // Bind the username parameter
            $stmt->bind_param("s", $username);

            // Execute the query
            if ($stmt->execute()) {
                $result = $stmt->get_result();

                // Check if there are results
                if ($result->num_rows === 0) {
                    $stmt->close();
                    return null;
                } else {
                    // Fetch the result as an associative array
                    $row = $result->fetch_assoc();

                    if ($row) {
                        // Create a new User object
                        $user = new User();

                        // Set properties of the User object, excluding recovery question and answer
                        $user->setId($row["id"]);
                        $user->setFullName($row["full_name"]);
                        $user->setUsername($row["username"]);
                        $user->setEmail($row["email"]);
                        $user->setBurned($row['burned']);
                        $user->setActive($row['active']);
                        $user->setProfileUrl($row['profile_url']);
                        $user->setPhoneNumber($row['phone_number']);
                        $user->setRole($row['role']);
                        $user->setCreatedAt($row["created_at"]);
                        $user->setUpdatedAt($row["updated_at"]);

                        // You can choose to set verified or any other fields as needed
                        $user->setVerified($row['verified'] ?? false); // Ensure default value is null if 'verified' is not set
                        // Return the populated User object
                        $stmt->close();
                        return $user;
                    } else {
                        $stmt->close();
                        return null;
                    }
                }

            } else {
                // Report the database error if execution fails
                ErrorReporter::report("Database error", $this->con->error, $_SERVER['REQUEST_URI']);
                return null;
            }
        }
    }
    public function save_user(User $user): bool
    {
        // Set default values for verified and role if they are not set
        $verified = $user->isVerified() ?? false; // default verified to false
        $role = $user->getRole() ?? 'user'; // default role to 'user'

        // Prepare the SQL query to insert user data
        $query = "INSERT INTO users (full_name, username, email, profile_url, phone_number, role, password, verified,recovery_question,recovery_answer_hash, created_at, updated_at) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?,?,?, NOW(), NOW())";

        // Initialize and prepare the statement
        $stmt = $this->con->stmt_init();
        if (!$stmt->prepare($query)) {
            ErrorReporter::report("Database error", $this->con->error, $_SERVER['REQUEST_URI']);
            return false;
        }

        // Assign properties to variables for binding
        $fullName = $user->getFullName();
        $username = $user->getUsername();
        $email = $user->getEmail();
        $profileUrl = $user->getProfileUrl();
        $qn = "jkrtr";
        $ans = "jkj";
        $phoneNumber = $user->getPhoneNumber();
        $password = $user->getPassword();// Ensure this is hashed before calling save_user
        // Bind parameters to the prepared statement using variables
        $stmt->bind_param(
            "sssssssiss",
            $fullName,
            $username,
            $email,
            $profileUrl,
            $phoneNumber,
            $role,
            $password,
            $verified,
            $qn,
            $ans
        );

        // Execute the statement and check if it was successful
        $result = $stmt->execute();

        // Close the statement
        $stmt->close();

        // Return true if insertion was successful, otherwise false
        return $result;
    }
    public function update_user(User $user): bool
    {
        // Default verified and role values if they are not set
        $verified = $user->isVerified() ?? false; // use isVerified() instead of getVerified()
        $role = $user->getRole() ?? 'user'; // default role to 'user'

        // Prepare the SQL query to update user data
        $query = "UPDATE users SET 
                  full_name = ?, 
                  username = ?, 
                  email = ?, 
                  profile_url = ?, 
                  phone_number = ?, 
                  role = ?, 
                  password = ?, 
                  verified = ?, 
                  updated_at = NOW() 
              WHERE id = ?";

        // Initialize and prepare the statement
        $stmt = $this->con->stmt_init();
        if (!$stmt->prepare($query)) {
            ErrorReporter::report("Database error", $this->con->error, $_SERVER['REQUEST_URI']);
            return false;
        }

        // Assign properties to variables for binding
        $fullName = $user->getFullName();
        $username = $user->getUsername();
        $email = $user->getEmail();
        $profileUrl = $user->getProfileUrl();
        $phoneNumber = $user->getPhoneNumber();
        $password = $user->getPassword(); // Ensure this is hashed
        $userId = $user->getId();

        // Bind parameters to the prepared statement using variables
        $stmt->bind_param(
            "sssssssii",
            $fullName,
            $username,
            $email,
            $profileUrl,
            $phoneNumber,
            $role,
            $password,
            $verified,
            $userId
        );

        // Execute the statement and check if it was successful
        $result = $stmt->execute();

        // Close the statement
        $stmt->close();

        // Return true if update was successful, otherwise false
        return $result;
    }
    public function list_all_users(): array
    {
        $users = [];
        $query = "
        SELECT 
       id as id,
       username,
       full_name,
       email,
       profile_url,    
       phone_number,
       role,
       verified,
       active
FROM users";

        // Initialize and prepare the statement
        $stmt = $this->con->stmt_init();
        if (!$stmt->prepare($query)) {
            ErrorReporter::report("Database error", $this->con->error, $_SERVER['REQUEST_URI']);
            return $users;
        }

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch each row as a User object and add it to the array
        while ($row = $result->fetch_assoc()) {

            $users[] = $row;
        }

        // Close the statement
        $stmt->close();

        // Return the array of User objects
        return $users;
    }
    public function delete_user(int $userId): bool
    {
        $query = "DELETE FROM users WHERE id = ?";

        // Initialize and prepare the statement
        $stmt = $this->con->stmt_init();
        if (!$stmt->prepare($query)) {
            ErrorReporter::report("Database error", $this->con->error, $_SERVER['REQUEST_URI']);
            return false;
        }

        // Bind the user ID to the query
        $stmt->bind_param("i", $userId);

        // Execute the statement
        $result = $stmt->execute();

        // Close the statement
        $stmt->close();

        // Return true if the deletion was successful, otherwise false
        return $result;
    }
    public function update_user_password(int $userId, string $newPassword): bool
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?";

        $stmt = $this->con->stmt_init();
        if (!$stmt->prepare($query)) {
            ErrorReporter::report("Database error", $this->con->error, $_SERVER['REQUEST_URI']);
            return false;
        }

        $stmt->bind_param("si", $hashedPassword, $userId);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }
    public function log_user_login(int $userId): bool
    {
        $query = "INSERT INTO user_logins (user_id, login_time, ip_address) VALUES (?, NOW(), ?)";

        $stmt = $this->con->stmt_init();
        if (!$stmt->prepare($query)) {
            ErrorReporter::report("Database error", $this->con->error, $_SERVER['REQUEST_URI']);
            return false;
        }

        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $stmt->bind_param("is", $userId, $ipAddress);

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }
    public function insertUser(User $user): bool
    {
        // Prepare the SQL query with placeholders
        $query = "INSERT INTO users (full_name, email, phone_number, profile_url,password,recovery_question,recovery_answer_hash,username) VALUES (?, ?, ?, ?,?,?,?,?)";

        // Initialize and prepare the statement
        if ($stmt = $this->con->prepare($query)) {
            // Retrieve values using getter methods
            $fullName = $user->getFullName();
            $email = $user->getEmail();
            $phone = $user->getPhoneNumber();
            $profileUrl = $user->getProfileUrl();
            $pass="ociwjeofijociwjeofijwoeijfoiwefwoeijfoiwef";
            $recovery="ociwjeofijwoeijfoiwef";
            $answer="ociwjeofijwoeijfoiwef";

            // Bind the user data to the statement
            $stmt->bind_param('ssssssss', $fullName, $email, $phone, $profileUrl,$pass,
                $recovery,
                $answer,
                $email);

            // Execute the statement and check for success
            if ($stmt->execute()) {
                // Close the statement
                $stmt->close();
                return true;
            } else {
                // Handle execution error
                error_log("Execution error: " . $stmt->error);
                $stmt->close();
                return false;
            }
        } else {
            // Handle preparation error
            error_log("Preparation error: " . $this->con->error);
            return false;
        }
    }
    public function getPasswordByUsername(string $username): ?string
    {
        // Prepare the SQL query to fetch only the password
        $query = "SELECT password FROM users WHERE username = ? LIMIT 1";

        // Initialize and prepare the statement
        if ($stmt = $this->con->prepare($query)) {
            // Bind the username parameter to the statement
            $stmt->bind_param('s', $username);

            // Execute the statement
            $stmt->execute();

            // Fetch the result
            $result = $stmt->get_result();

            // Check if a user was found
            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                $stmt->close();
                return $row['password']; // Return the password
            } else {
                $stmt->close();
                return null; // No user found with that username
            }
        } else {
            // Handle preparation error
            error_log("Preparation error: " . $this->con->error);
            return null;
        }
    }
    public function findUserById(int $id): ?array
    {
        // Prepare the SQL query
        $query = "SELECT id, full_name, email, phone_number as phone, profile_url FROM users WHERE id = ? LIMIT 1";

        // Initialize and prepare the statement
        if ($stmt = $this->con->prepare($query)) {
            // Bind the ID parameter to the statement
            $stmt->bind_param('i', $id);

            // Execute the statement
            $stmt->execute();

            // Fetch the result
            $result = $stmt->get_result();

            // Check if a user was found
            if ($result->num_rows === 1) {
                // Fetch associative array of user data
                $user = $result->fetch_assoc();
                $stmt->close();
                return $user;
            } else {
                $stmt->close();
                return null; // No user found with that ID
            }
        } else {
            // Handle preparation error
            error_log("Preparation error: " . $this->con->error);
            return null;
        }
    }
    public function find_user_by_email(string $email): ?User
    {
        $query = "SELECT * FROM users WHERE email=?";

        $stmt = $this->con->stmt_init();

        if (!$stmt->prepare($query)) {
            ErrorReporter::report("Database error", $this->con->error, $_SERVER['REQUEST_URI']);
            return null;
        }

        // Bind the email parameter
        $stmt->bind_param("s", $email);

        // Execute the query
        if (!$stmt->execute()) {
            ErrorReporter::report("Database error", $stmt->error, $_SERVER['REQUEST_URI']);
            return null;
        }

        // Get the result of the query
        $result = $stmt->get_result();

        // Check if there are results
        if ($result->num_rows === 0) {
            $stmt->close();
            return null;
        }

        // Fetch the result as an associative array
        $row = $result->fetch_assoc();
        $stmt->close(); // Close statement after fetching data

        if (!$row) {
            return null;
        }

        // Create a new User object
        $user = new User();
        $user->setId($row["id"]);
        $user->setFullName($row["full_name"]);
        $user->setUsername($row["username"]);
        $user->setEmail($row["email"]);
        $user->setProfileUrl($row['profile_url']);
        $user->setBurned($row['burned']);
        $user->setActive($row['active']);
        $user->setPhoneNumber($row['phone_number']);
        $user->setRole($row['role']);
        $user->setCreatedAt($row["created_at"]);
        $user->setPassword($row["password"]);
        $user->setRecoveryAnswerHash($row["recovery_answer_hash"]);
        $user->setRecoveryQuestion($row["recovery_question"]);
        $user->setUpdatedAt($row["updated_at"]);

        // Ensure verified is either its DB value or null
        $user->setVerified($row['verified'] ?? null);

        return $user;
    }
    public function find_user_by_access_token($token): ?User
    {
        $query = "SELECT * FROM users join access_tokens a on users.id = a.user_id WHERE a.token=?";

        $stmt = $this->con->stmt_init();

        if (!$stmt->prepare($query)) {
            ErrorReporter::report("Database error", $this->con->error, $_SERVER['REQUEST_URI']);
            return null;
        }

        // Bind the email parameter
        $stmt->bind_param("s", $token);

        // Execute the query
        if (!$stmt->execute()) {
            ErrorReporter::report("Database error", $stmt->error, $_SERVER['REQUEST_URI']);
            return null;
        }

        // Get the result of the query
        $result = $stmt->get_result();

        // Check if there are results
        if ($result->num_rows === 0) {
            $stmt->close();
            return null;
        }

        // Fetch the result as an associative array
        $row = $result->fetch_assoc();
        $stmt->close(); // Close statement after fetching data

        if (!$row) {
            return null;
        }

        // Create a new User object
        $user = new User();
        $user->setId($row["id"]);
        $user->setFullName($row["full_name"]);
        $user->setUsername($row["username"]);
        $user->setEmail($row["email"]);
        $user->setProfileUrl($row['profile_url']);
        $user->setBurned($row['burned']);
        $user->setActive($row['active']);
        $user->setRole($row['role']);
        $user->setPhoneNumber($row['phone_number']);

        return $user;
    }
    public function insertUsers(array $users)
    {
        // Initialize the statement
        $stmt = $this->con->stmt_init();
        // Prepare the SQL query with placeholders
        $query = "INSERT INTO users (full_name, email, phone_number, profile_url,password,recovery_question,recovery_answer_hash,username) VALUES (?, ?, ?, ?,?,?,?,?)";

        if ($stmt->prepare($query)) {
            // Loop through each user object in the array
            foreach ($users as $user) {
                // Retrieve values using getter methods
                $fullName = $user->getFullName();
                $email = $user->getEmail();
                $phone = $user->getPhone();
                $profileUrl = $user->getProfileUrl();

                // Bind the user data to the statement
                $stmt->bind_param(
                    'ssssssss', // 's' specifies the type: string
                    $fullName,
                    $email,
                    $phone,
                    $profileUrl,
                    "iweufjiwuehfiweufh",
                    "jhidwuhoiejoifoweijfoewfwe",
                    "ociwjeofijwoeijfoiwef",
                    $email
                );
                // Execute the statement
                $stmt->execute();
            }
            // Close the statement
            $stmt->close();
        } else {
            // Handle errors in preparing the statement
            echo "Error preparing statement: " . $this->con->error;
        }
    }
    function getDeletionHistory(): array {
        $query = "SELECT user_id, email, reason, deleted_at FROM deleted_users_history ORDER BY deleted_at DESC";
        $result = $this->con->query($query);

        $history = [];
        while ($row = $result->fetch_assoc()) {
            $history[] = $row;
        }

        return $history;
    }
    public function savePhone(string $phone, ?User $user): bool
    {
        // Step 2: Insert new token
        $stmt = $this->con->stmt_init();
        $query = "update users set phone_number=? where email=?";

        if ($stmt->prepare($query)) {
            $username=$user->getEmail();
            $stmt->bind_param("ss", $phone, $username);
            $success = $stmt->execute();
            $stmt->close();
            return $success;
        } else {
            return false;
        }
    }
    function delete_user_by_id($user_id, $reason): bool
    {
        $email=$this->find_user_by_id($user_id)->getEmail();
        $this->con->begin_transaction();

        try {
            if (!$this->logUserDeletion($user_id, $email, $reason)) {
                throw new Exception("Error logging deletion");
            }

            $stmt = $this->con->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);

            if (!$stmt->execute()) {
                throw new Exception("Error deleting user");
            }

            $this->con->commit();
            $stmt->close();
            return true;

        } catch (Exception $e) {
            $this->con->rollback();
            ErrorReporter::report("Database error", $e->getMessage(), $_SERVER['REQUEST_URI']);
            return false;
        }

    }
    function logUserDeletion($user_id, $email, $reason): bool {
        $stmt = $this->con->prepare("INSERT INTO deleted_users_history (user_id, email, reason) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $email, $reason);

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            ErrorReporter::report("Database error", $this->con->error, $_SERVER['REQUEST_URI']);
            return false;
        }
    }
    public function save_visitor(Visitor $visitor){

        $stmt = $this->con->stmt_init();

        $query = "INSERT INTO visitors(ip,ip_type,continent,country,region,city,isp,url,is_registered) value (?,?,?,?,?,?,?,?,?)";

        if ($stmt->prepare($query)) {
            $ip = $visitor->getIp();

            $ip_type = $visitor->getIpType();

            $continent = $visitor->getContinent();

            $country = $visitor->getCountry();

            $isp = $visitor->getIsp();

            $url = $visitor->getUrl();

            $city = $visitor->getCity();

            $region = $visitor->getRegion();

            $is_registered = $visitor->getIsRegistered();

            $stmt->bind_param("ssssssssi", $ip, $ip_type, $continent, $country, $region, $city, $isp, $url, $is_registered);

            if (!$stmt->execute()) {
                ErrorReporter::report("Saving visitor error ",$this->con->error,$_SERVER['REQUEST_URI']);

            }

        }
        else{
            ErrorReporter::report("Saving visitor error ",$this->con->error,$_SERVER['REQUEST_URI']);
        }

    }

//    Access_token
    public function isExistAccessToken($token): bool
    {
        $query = "SELECT 1 FROM access_tokens WHERE token = ? LIMIT 1";
        $stmt = $this->con->stmt_init();

        if (!$stmt->prepare($query)) {
            // log error if needed: $this->con->error
            return false;
        }

        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->store_result();

        $exists = $stmt->num_rows > 0;

        $stmt->free_result();
        $stmt->close();
        return $exists;
    }
    public function saveAccessToken(string $token, $expiry, $user_id): bool
    {
        // Step 1: Delete existing token(s) for this user
        $deleteQuery = "DELETE FROM access_tokens WHERE user_id = ?";
        $deleteStmt = $this->con->prepare($deleteQuery);
        if ($deleteStmt) {
            $deleteStmt->bind_param("i", $user_id);
            $deleteStmt->execute();
            $deleteStmt->close();
        }

        // Step 2: Insert new token
        $stmt = $this->con->stmt_init();
        $query = "INSERT INTO access_tokens (token, expires_at, user_id) VALUES (?, ?, ?)";

        if ($stmt->prepare($query)) {
            $stmt->bind_param("sii", $token, $expiry, $user_id);
            $success = $stmt->execute();
            $stmt->close();
            return $success;
        } else {
            return false;
        }
    }

//    verification Token
    public function is_valid_token($token, User $user): bool

    {
        $stmt = $this->con->stmt_init();

        $query = "SELECT user_id,expire_date FROM verification_tokens WHERE token=?";

        if ($stmt->prepare($query)) {

            $stmt->bind_param("s", $token);

            if (!$stmt->execute()) {
                ErrorReporter::report("Token verification error",$this->con->error,"/");
                return false;

            } else {

                $stmt->bind_result($user_id, $expire_date);

                if ($stmt->fetch()) {

                    try {

                        $expire_date = new DateTime($expire_date);

                        $today = new DateTime();

                        if ($today < $expire_date && $user_id == $user->getId()) {
                            return true;
                        } else return false;

                    } catch (Exception $e) {
                        ErrorReporter::report("Token save Error",$e->getMessage(),Helper::getCurrentUrl());
                        return false;

                    }

                } else return false;

            }

        } else {
            ErrorReporter::report("Token verification error",$this->con->error,"/");
            return false;
        }
    }
    public function delete_token($token){
        $stmt = $this->con->stmt_init();
        $query = "DELETE FROM verification_tokens WHERE token=?";
        if ($stmt->prepare($query)) {
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $stmt->close();
            $this->con->close();
        }
    }
    public function save_verification_token(string $token, $exp_date, $user_id): bool
    {
        $stmt = $this->con->stmt_init();
        $query = "INSERT INTO verification_tokens(user_id,token,expire_date) value (?,?,?)";
        if ($stmt->prepare($query)) {
            $stmt->bind_param("iss", $user_id, $token, $exp_date);
            if (!$stmt->execute()) {
                ErrorReporter::report("Token save error",$this->con->error,"/");
                return false;
            } else return true;
        } else return false;
    }

//    General queries
    public function update($table, $data, $where): bool
    {
        $set = implode(", ", array_map(function($col) { return "$col=?"; }, array_keys($data)));
        $values = array_values($data);

        $whereClause = implode(" AND ", array_map(function($col) { return "$col=?"; }, array_keys($where)));
        $values = array_merge($values, array_values($where));

        $stmt = $this->con->prepare("UPDATE $table SET $set WHERE $whereClause");
        if ($stmt === false) {
            return false;
        }

        $types = str_repeat("s", count($values));
        $stmt->bind_param($types, ...$values);
        return $stmt->execute();
    }
    public function fetch(string $query, array $params = []): ?array
    {
        $stmt = $this->con->prepare($query);
        if (!$stmt) {
            error_log("DB Prepare failed: " . $this->con->error);
            return null;
        }

        if (!empty($params)) {
            // Build the types string: all strings ('s')
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stmt->close();
            return $row;
        }

        $stmt->close();
        return null;
    }
    public function delete($table, $where): bool
    {
        $whereClause = implode(" AND ", array_map(function($col) { return "$col=?"; }, array_keys($where)));
        $values = array_values($where);

        $stmt = $this->con->prepare("DELETE FROM $table WHERE $whereClause");
        if ($stmt === false) {
            return false;
        }

        $types = str_repeat("s", count($values));
        $stmt->bind_param($types, ...$values);
        return $stmt->execute();
    }
    public function insert($table, $data): bool|int|string
    {
        // Keys (columns) and values
        $columns = implode(", ", array_keys($data));
        $values  = implode("', '", array_map([$this->con, 'real_escape_string'], array_values($data)));

        // SQL query
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ('$values')";

        if ($this->con->query($sql)) {
            return $this->con->insert_id; // Return last inserted ID
        } else {
            ErrorReporter::report("Insert error",$this->con->error,$_SERVER['REQUEST_URI']);
            return false;
        }
    }
    public function select(string $query): array
    {
        $stmt=$this->con->prepare($query);
        $data=[];
        if ($stmt->execute()){
            $res=$stmt->get_result();

            while (($row=$res->fetch_assoc())!=null){
                $data[]=$row;
            }
        }
        $stmt->close();
        return $data;
    }
    public function selectOne($sql): ?array
    {
        $result = $this->con->query($sql);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc(); // return first row as array
        }

        return null; // no result
    }


    //Program
    public function save_program(Program $program): bool
    {
        $stmt = $this->con->prepare("
            INSERT INTO program
            (name, short_name, intakes, duration, capacity, 
             accreditation_year, faculty_id, department_id, level_id, 
             description, content, user_id, created_at,entry_requirements,fees) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)
        ");

        if (!$stmt) {
            return false;
        }

        $name              = $program->getName();
        $shortName         = $program->getShortName();
        $intakes           = $program->getIntakes();
        $duration          = $program->getDuration();
        $capacity          = $program->getCapacity();
        $accreditationYear = $program->getAccreditationYear();
        $facultyId         = $program->getFacultyId();
        $departmentId      = $program->getDepartmentId();
        $levelId           = $program->getLevelId();
        $description       = $program->getDescription();
        $content           = $program->getContent();
        $createdBy         = $program->getCreatedBy();
        $req         = $program->getRequirements();
        $createdAt         = $program->getCreatedAt();
        $fee         = $program->getFees();

        // Bind variables
        $stmt->bind_param(
            "ssisdiiiississs",
            $name,
            $shortName,
            $intakes,
            $duration,
            $capacity,
            $accreditationYear,
            $facultyId,
            $departmentId,
            $levelId,
            $description,
            $content,
            $createdBy,
            $createdAt,
            $req,
            $fee
        );

        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    public function find_program_by_name($name, $faculty_id): bool
    {
        $stmt = $this->con->prepare("SELECT id FROM program WHERE name = ? AND faculty_id = ? LIMIT 1");
        $stmt->bind_param("si", $name, $faculty_id);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }


    public function save_alumni(Alumni $alumni): bool
    {
        $stmt = $this->con->stmt_init();
        $query = "INSERT INTO alumni (full_name, email, phone, graduation_year, course, employment_status, message) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";

        if ($stmt->prepare($query)) {
            // Get values from Alumni object
            $full_name        = $alumni->getFullName();
            $email            = $alumni->getEmail();
            $phone            = $alumni->getPhone();
            $graduation_year  = $alumni->getGraduationYear();
            $course           = $alumni->getCourse();
            $employment       = $alumni->getEmploymentStatus();
            $message          = $alumni->getMessage();

            // Bind parameters
            $stmt->bind_param(
                "sssisss",
                $full_name,
                $email,
                $phone,
                $graduation_year,
                $course,
                $employment,
                $message
            );

            // Execute statement and check success
            $success = $stmt->execute();

            // Clean up
            $stmt->close();

            return $success;
        } else {
            // Handle preparation failure
            return false;
        }
    }
    public function get_alumni(): array
    {
        $query = "SELECT id, full_name, email, phone, graduation_year, course, employment_status, message, create_date 
              FROM alumni ORDER BY create_date DESC";

        $result = $this->con->query($query);

        $alumniList = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $alumniList[] = $row;
            }
        }

        return $alumniList;
    }
    public function save_donation(Donation $donation): bool
    {
        $stmt = $this->con->stmt_init();
        $query = "INSERT INTO donations (full_name,email,phone,amount,method,transaction_id) VALUES (?,?,?,?,?,?)";

        if ($stmt->prepare($query)) {
            $fullName = $donation->getFullName();
            $email = $donation->getEmail();
            $amount = $donation->getAmount();
            $phone = $donation->getPhone();
            $method = $donation->getMethod();
            $transactionId = $donation->getTransactionId();
            $stmt->bind_param(
                "sssiss",
                $fullName,
                $email,
                $phone,
                $amount,
                $method,
                $transactionId
            );

            $success = $stmt->execute();
            $stmt->close();
            return $success;
        }
        return false;
    }

}