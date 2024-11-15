<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="description" content="Web application development" />
        <meta name="keywords" content="PHP" />
        <meta name="author" content="Luong Quang Vinh" />
        <title>TITLE</title>
    </head>

    <body>
        <h1>Web Programming - Lab08</h1>
        <hr>
        <?php
            require_once ("settings.php");

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $errors = [];
                $email_pattern = "/^[a-zA-Z0-9._]+@[a-zA-Z0-9.]+\.[a-zA-Z]{2,4}$/";

                if (empty($_POST['username'])) {
                    $errors[] = "Username is empty.";
                }

                if (empty($_POST['password'])) {
                    $errors[] = "Password is empty.";
                }

                if (empty($_POST['email'])) {
                    $errors[] = "Email is empty.";
                }

                if (empty($_POST['dob'])) {
                    $errors[] = "Date of birth is empty.";
                }

                if (empty($_POST['address'])) {
                    $errors[] = "Address is empty.";
                }

                if (!preg_match($email_pattern, $_POST['email'])) {
                    $errors[] = "Email format is invalid.";
                }

                if (empty($errors)) {
                    $conn = @mysqli_connect($host, $user, $pswd) or die("Failed to connect to server" . mysqli_connect_error());
                    @mysqli_select_db($conn, $dbnm) or die("Database not available" . mysqli_error($conn));

                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    $email = $_POST['email'];
                    $dob = $_POST['dob'];
                    $address = $_POST['address'];
                
                    $insertSql = "INSERT INTO cos20031_user (user_name, user_password, user_email, dob, user_address) VALUES ('$username', '$password', '$email', '$dob', '$address')";

                    if (mysqli_query($conn, $insertSql)) {
                        echo "<p>New member added successfully!</p>";
                        echo "<a href='../signin.html'>Back to login</a>";
                    } else {
                        echo "<p>Error:" . mysqli_error($conn) . "</p>";
                    }

                    mysqli_close($conn);
                } else {
                    foreach ($errors as $error) {
                        echo "<p>$error</p>";
                    }
                }
            } else {
                header("location: website.html");
            }
        ?>
    </body>
</html>