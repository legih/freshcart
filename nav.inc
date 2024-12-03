<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $current_page = basename($_SERVER['PHP_SELF']);
?>

    <div class="topnav">
        <?php 
            if ($current_page === 'register.php' || $current_page === 'signin.php') {
                echo "<a href='website.php'>Home</a>
                <a href='products.php'>Products</a>";
            } elseif ($current_page === 'website.php') {
                echo "<a class='active' href='website.php'>Home</a>
                <a href='products.php'>Products</a>";
            } else {
                echo "<a href='website.php'>Home</a>
                <a class='active' href='products.php'>Products</a>";
            }
        ?>
        <a href="#news">News</a>
        <a href="#about">About</a>
    </div>

    <header>
        <div class="navbar">
            <div class="logo">
                <h1><a href="website.php">Freshcart</a></h1>
            </div>

            <?php 
                if ($current_page !== 'register.php' && $current_page !== 'signin.php') {
                    echo "<div class='search-bar'>
                        <form action='search.php' method='get'>
                            <input type='text' name='search' placeholder='Search for products...'>
                            <button type='submit' class='search-btn'>🔍</button>
                        </form>
                    </div>";
                }
            ?>


            <div class="nav-icons">
                <!-- Conditionally display Sign In or Log Out --> 
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- If logged in, show Log Out button -->
                    <a href="logout.php" id="logout-btn">Log Out</a>
                <?php else: ?>
                    <!-- If not logged in, show Sign In button -->
                    <a href="signin.php" id="signin-btn">Sign In</a>
                <?php endif; ?>
                
                <!-- This section now only shows the Sign In button or Log Out button based on session -->
                <a href="shopping_list.php">Shopping List</a>
            </div>
        </div>
    </header>