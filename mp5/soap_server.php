<?php
// Enable SOAP server
ini_set("soap.wsdl_cache_enabled", "0"); // Disable WSDL cache for development
$server = new SoapServer("user_registration.wsdl");
// Register the class that handles registration
$server->setClass("UserRegistration");
$server->handle();
class UserRegistration
{
    // Database connection parameters
    private $host = "localhost";
    private $dbname = "user_registration";
    private $username = "root";
    private $password = "";

    // Connect to the database
    private function connect()
    {
        try {
            // Attempt to create a new PDO connection
            $conn = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname",
                $this->username,
                $this->password
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception mode
            return $conn;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return null;
        }
    }

    public function registerUser($xml)
    {
        $userData = simplexml_load_string($xml);
        if ($userData === false) {
            return "Error parsing XML.";
        }
        
        $username = (string) $userData->username;
        $password = (string) $userData->password;
        $email = (string) $userData->email;
        $conn = $this->connect();
        if ($conn) {
            try {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
                $stmt->bindParam(":username", $username);
                $stmt->bindParam(":password", $hashedPassword);
                $stmt->bindParam(":email", $email);

                if ($stmt->execute()) {
                    return "Registration successful!";
                } else {
                    return "Error: Could not register user.";
                }
            } catch (PDOException $e) {
                return "Database query failed: " . $e->getMessage();
            }
        } else {
            return "Database connection error.";
        }
    }
}
?>
