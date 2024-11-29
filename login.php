<?php
// Start the session
session_start();

// Include database connection
require_once("settings.php"); // Include your database connection file

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if both fields are filled
    if (!empty($email) && !empty($password)) {
        // Connect to the database
        $conn = @mysqli_connect($host, $user, $pswd, $dbnm) or die("Failed to connect to server: " . mysqli_connect_error());

        // Sanitize inputs to prevent SQL injection
        $email = mysqli_real_escape_string($conn, $email);

        // Prepare the SQL statement
        $sql = "SELECT user_id, user_name, user_password, user_email, is_admin FROM cos20031_user WHERE user_email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a user with this email exists
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Compare the entered password with the stored password
            if ($password === $user['user_password']) {
                // Password is correct, set session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['user_name'];
                $_SESSION['email'] = $user['user_email'];
                $_SESSION['loggedin'] = true;

                if ($user['is_admin'] == 1) {
                    // Admin user
                    header("Location: admin.php");
                } else {
                    // Normal user
                    header("Location: website.php");
                }
                exit();
            } else {
                // Password is incorrect
                echo "<p>Incorrect password. Please try again.</p>";
            }
        } else {
            // Email not found in the database
            echo "<p>Email not found. Please check your email.</p>";
        }

        // Close the database connection
        $stmt->close();
        $conn->close();
    } else {
        echo "<p>Please enter both email and password.</p>";
    }
}
?>