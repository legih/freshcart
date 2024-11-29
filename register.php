<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - My Website</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
    <style>

    </style>
</head>

<body>
    <!-- Header Section -->
    <?php include "nav.inc"; ?>
    
    <!-- Register Form -->
    <div class="container">
        <div class="register-container">    
            <h2>REGISTER AN ACCOUNT</h2>
            <form action="add.php" method="post">
                <div class="form-group">
                    <label for="fullname">Full name *</label>
                    <input type="text" id="fullname" name="username" placeholder="Full name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label for="dob">Date of Birth *</label>
                    <input type="date" id="dob" name="dob" required>
                </div>
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirm password *</label>
                    <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm password" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" placeholder="Address">
                </div>
                <p>Already registered? Sign in <a href="signin.php">here</a></p>
                <button type="submit" class="btn-submit">Register</button>
            </form>
        </div>
    </div>
</body>

</html>
