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
        <h1>COS20031</h1>
        <hr>
        <h2>Update User</h2>
        <form action="display.php" method="get">
            <input type="hidden" name="action" value="update">
            <label for="user_id">User ID</label>
            <input type="text" name="user_id"><br>
            <label for="username">Username</label>
            <input type="text" name="username"><br>

            <input type="submit" value="Update">
            <input type="reset" value="Reset">
        </form>

        <h2>Delete User</h2>
        <form action="display.php" method="get">
            <input type="hidden" name="action" value="delete">
            <label for="user_id">User ID</label>
            <input type="text" name="user_id"><br>

            <input type="submit" value="Delete">
            <input type="reset" value="Reset">
        </form>

        <?php
            require_once ("settings.php");

            $conn = @mysqli_connect($host, $user, $pswd) or die("Failed to connect to server" . mysqli_connect_error());
            @mysqli_select_db($conn, $dbnm) or die("Database not available" . mysqli_error($conn));

            $query = "SELECT * FROM cos20031_user";
            $results = mysqli_query($conn, $query);

            echo "<table>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Date of Birth</th>
                        <th>Address</th>
                    </tr>";
            while ($row = mysqli_fetch_row($results)) {
                echo "<tr>
                        <td>{$row[0]}</td>
                        <td>{$row[1]}</td>
                        <td>{$row[3]}</td>
                        <td>{$row[4]}</td>
                        <td>{$row[5]}</td>
                    </tr>";
            }
            echo "</table>";

            mysqli_free_result($results);
            mysqli_close($conn);
        ?>
    </body>
</html>