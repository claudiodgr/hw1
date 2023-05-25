<?php
require_once "init.php";
if (isset($_SESSION["UID"])) {
    $escapedPassword = mysqli_escape_string($conn, $_SESSION['userid']);
    $escapedUser = mysqli_escape_string($conn, $_SESSION['username']);
    $query = "SELECT username from hw1_users where password = '{$escapedPassword}' and username = '{$escapedUser}'";
    $queryRes = mysqli_query($conn, $query);

    if ($queryRes) {
        if (mysqli_num_rows($queryRes) == 0) {
            mysqli_free_result($queryRes);
            mysqli_close($conn);
            header("Location: {$uri}/../login.php");
            exit();
        }
        mysqli_free_result($queryRes);
    } else {
        mysqli_close($conn);
        header("Location: {$uri}/../login.php");
        exit();
    }
} else {
    mysqli_close($conn);
    header("Location: {$uri}/../login.php");
    exit();
}

if (isset($_GET['follow'])) {
    $follow = mysqli_escape_string($conn, $_GET['follow']);
    $query = "SELECT followerId FROM hw1_follow WHERE followerId = {$_SESSION['UID']} AND followed = '{$follow}'";

    $queryRes = mysqli_query($conn, $query);
    if ($queryRes) {
        if (mysqli_num_rows($queryRes) > 0) {
            $query = "DELETE FROM hw1_follow WHERE followerId = {$_SESSION['UID']} AND followed = '{$follow}'";
            $deleteRes = mysqli_query($conn, $query);
            if (!$deleteRes) {
                http_response_code(404);
                mysqli_close($conn);
                exit();
            }

            echo 'unfollowed';
        } else {
            $query = "INSERT INTO hw1_follow (followerId, followed) VALUES ('{$_SESSION['UID']}', '{$follow}')";
            $addRes = mysqli_query($conn, $query);
            if (!$addRes) {
                http_response_code(404);
                mysqli_close($conn);
                exit();
            }

            echo 'followed';
        }
        mysqli_free_result($queryRes);
    } else {
        http_response_code(404);
    }

    exit();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts</title>
    <link rel="icon" href="favicon.png" sizes="64x64" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="common.css">
    <link rel="stylesheet" href="search_people.css">
    <script defer src="search_people.js"></script>
</head>

<body>
    <nav class="navbar">
        <div class="logo">
            tuneshare | <span class="user-nav"><?php echo $_SESSION['username'] ?></span>
        </div>
        <!-- Add the hamburger menu icon -->
        <div class="hamburger-menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>

        <!-- Add the navigation drawer container -->
        <div id="navigation-drawer" class="navigation-drawer">
            <ul class="drawer-menus">
                <li><a href="<?php echo "{$uri}/../home.php" ?>">Home</a></li>
                <li><a href="<?php echo "{$uri}/../search_content.php" ?>">Search</a></li>
                <li><a href="<?php echo "{$uri}/../search_people.php" ?>">Contacts</a></li>
                <li><a href="<?php echo "{$uri}/../my_library.php" ?>">My Library</a></li>
                <li><a href="<?php echo "{$uri}/../logout.php" ?>">Logout</a></li>
            </ul>
        </div>

        <!-- Add the backdrop container -->
        <div id="backdrop" class="backdrop"></div>

        <ul class="menus">
            <li><a href="<?php echo "{$uri}/../home.php" ?>">Home</a></li>
            <li><a href="<?php echo "{$uri}/../search_content.php" ?>">Search</a></li>
            <li><a href="<?php echo "{$uri}/../search_people.php" ?>">Contacts</a></li>
            <li><a href="<?php echo "{$uri}/../my_library.php" ?>">My Library</a></li>
            <li><a href="<?php echo "{$uri}/../logout.php" ?>">Logout</a></li>
        </ul>
    </nav>
    <div class="wrapper">
        <form class="search-form" action="javascript:void(0);">
            <input type="text" placeholder="Search Users..." name="search">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
        <div class="results-container">
        </div>
    </div>
</body>

</html>