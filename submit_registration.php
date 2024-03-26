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
        $restaurant_name = $_POST['restaurant_name'];
        $owner_name = $_POST['owner_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $password = $_POST['password'];

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $check_email_sql = "SELECT COUNT(*) FROM restaurants WHERE email = ?";
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

        // Check if phone number already exists
        $check_phone_sql = "SELECT COUNT(*) FROM restaurants WHERE phone = ?";
        $check_phone_stmt = $conn->prepare($check_phone_sql);
        $check_phone_stmt->bind_param("s", $phone);
        $check_phone_stmt->execute();
        $check_phone_stmt->bind_result($phone_count);
        $check_phone_stmt->fetch();
        $check_phone_stmt->close();

        if ($phone_count > 0) {
            echo "<script>alert('Phone number $phone already exists. Please use a different phone number.');</script>";
            exit();
        }

        // Proceed with restaurant registration if all checks passed
        $sql = "INSERT INTO restaurants (restaurant_name, owner_name, email, phone, address, password)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Error preparing SQL statement: " . $conn->error);
        }

        $stmt->bind_param("ssssss", $restaurant_name, $owner_name, $email, $phone, $address, $hashed_password);

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
