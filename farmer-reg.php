<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "farmer-reg";

try {
    $conn = new mysqli($servername, $username, $password, $database);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $check_email_sql = "SELECT COUNT(*) FROM users WHERE email = ?";
        $check_email_stmt = $conn->prepare($check_email_sql);
        $check_email_stmt->bind_param("s", $email);
        $check_email_stmt->execute();
        $check_email_stmt->bind_result($email_count);
        $check_email_stmt->fetch();
        $check_email_stmt->close();

        if ($email_count > 0) {
            echo "<script>alert('Email $email already exists. Please use a different email address.');</script>";
            exit();
        }

        // Check if mobile number already exists
        $check_mobile_sql = "SELECT COUNT(*) FROM users WHERE mobile = ?";
        $check_mobile_stmt = $conn->prepare($check_mobile_sql);
        $check_mobile_stmt->bind_param("s", $mobile);
        $check_mobile_stmt->execute();
        $check_mobile_stmt->bind_result($mobile_count);
        $check_mobile_stmt->fetch();
        $check_mobile_stmt->close();

        if ($mobile_count > 0) {
            echo "<script>alert('Mobile number $mobile already exists. Please use a different mobile number.');</script>";
            exit();
        }

        // Check if username already exists
        $check_username_sql = "SELECT COUNT(*) FROM users WHERE username = ?";
        $check_username_stmt = $conn->prepare($check_username_sql);
        $check_username_stmt->bind_param("s", $username);
        $check_username_stmt->execute();
        $check_username_stmt->bind_result($username_count);
        $check_username_stmt->fetch();
        $check_username_stmt->close();

        if ($username_count > 0) {
            echo "<script>alert('Username $username already exists. Please choose a different username.');</script>";
            exit();
        }

        // Proceed with user registration if all checks passed
        $sql = "INSERT INTO users (name, email, mobile, username, password)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Error preparing SQL statement: " . $conn->error);
        }

        $stmt->bind_param("ssiss", $name, $email, $mobile, $username, $hashed_password);

        if ($stmt->execute()) {
            echo "<script>alert('New record created successfully');</script>";
        } else {
            throw new Exception("Error executing SQL statement: " . $stmt->error);
        }

        $stmt->close();
    }
    
    $conn->close();
} catch (Exception $e) {
    echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
}
?>
