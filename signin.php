<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freshcart - Sign In</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
</head>

<body>
    <!-- Header Section -->
    <?php include "nav.inc"; ?>

    <div class="container">
        <div class="signin-container">
            <h2>SIGN IN</h2>

            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <p>Don't have an account? Register <a href="register.php">here</a></p>
                <button type="submit" class="btn-submit">Sign in</button>
            </form>
        </div>
    </div>
</body>
</html>