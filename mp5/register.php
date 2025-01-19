<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    
    // Create XML data
    $xml = new SimpleXMLElement("<user></user>");
    $xml->addChild("username", $username);
    $xml->addChild("password", $password);
    $xml->addChild("email", $email);
    
    // Create SOAP client and send the request
    try {
        // Initialize the SOAP client using the WSDL
        $client = new SoapClient("user_registration.wsdl", array('trace' => 1));

        // Send XML as a string, directly to the registerUser function
        $response = $client->registerUser($xml->asXML());

        // Display the response from the server
        echo "<h2>$response</h2>";

    } catch (SoapFault $e) {
        // Handle any errors that occur during the request
        echo "<h2>Error: " . $e->getMessage() . "</h2>";

        // Print the raw SOAP request and response for debugging
        echo "<pre>" . $client->__getLastRequest() . "</pre>";
        echo "<pre>" . $client->__getLastResponse() . "</pre>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registration Form</title>
</head>
<body>
<h2>User Registration</h2>
<form method="POST" action="register.php">
<label for="username">Username:</label><br>
<input type="text" id="username" name="username" required><br><br>
<label for="password">Password:</label><br>
<input type="password" id="password" name="password" required><br><br>
<label for="email">Email:</label><br>
<input type="email" id="email" name="email" required><br><br>
<input type="submit" value="Register">
</form>
</body>
</html>