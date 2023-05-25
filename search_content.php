<?php
require_once "init.php";
if (isset($_SESSION["UID"])) {
    $escapedPassword = mysqli_escape_string($conn, $_SESSION['userid']);
    $escapedUser = mysqli_escape_string($conn, $_SESSION['username']);
    $query = "SELECT username from hw1_users where password = '{$escapedPassword}' and username = '{$escapedUser}'";
    $queryRes = mysqli_query($conn, $query);

    if ($queryRes) {
        if (mysqli_num_rows($queryRes) == 0) {
            session_unset();
            session_destroy();
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

if (isset($_GET["playlistIdChk"]) && isset($_SESSION['username'])) {
    if (empty($_GET['playlistIdChk'])) {
        echo "321"; // error invalid parameters
        exit();
    }

    $conn = mys_con();
    $content_id = mysqli_escape_string($conn, trim($_GET["playlistIdChk"]));
    $user = mysqli_escape_string($conn, $_SESSION['username']);
    $checkUser = "SELECT ID FROM hw1_users where username = '{$user}'";
    $checkUserRes = mysqli_query($conn, $checkUser);
    if ($checkUserRes) {
        if (mysqli_num_rows($checkUserRes) == 0) {
            echo "545"; // error user not found
            exit();
        }
    } else {
        echo "999"; // error invalid query
        exit();
    }
    $userIdRes = mysqli_fetch_assoc($checkUserRes)['ID'];
    $queryPost = "SELECT * FROM hw1_playlists WHERE playlistDeezerId = '{$content_id}' AND playlistUser = {$userIdRes}";
    
    $queryPostRes = mysqli_query($conn, $queryPost);
    if (!$queryPostRes) {
        echo "notpresent";
        exit();
    }
    if (mysqli_num_rows($queryPostRes) > 0) {
        echo "delete";
        exit();
    }
    exit();

}

if (isset($_GET["playlistId"]) && isset($_SESSION['username'])) {
    if (empty($_GET['playlistId'])) {
        echo "321"; // error invalid parameters OR invalid session
        exit();
    }

    $conn = mys_con();
    $content_id = mysqli_escape_string($conn, trim($_GET["playlistId"]));


    $user = mysqli_escape_string($conn, $_SESSION['username']);
    $checkUser = "SELECT ID FROM hw1_users where username = '{$user}'";
    $checkUserRes = mysqli_query($conn, $checkUser);
    if ($checkUserRes) {
        if (mysqli_num_rows($checkUserRes) == 0) {
            echo "545"; // error user not found
            exit();
        }
    } else {
        echo "999"; // error invalid query
        exit();
    }
    $userIdRes = mysqli_fetch_assoc($checkUserRes)['ID'];
    $queryPost = "SELECT * FROM hw1_playlists WHERE playlistDeezerId = '{$content_id}' AND playlistUser = {$userIdRes}";
    $queryPostRes = mysqli_query($conn, $queryPost);
    if (mysqli_num_rows($queryPostRes) > 0) {
        $queryPost = "DELETE FROM hw1_playlists WHERE playlistDeezerId = '{$content_id}' AND playlistUser = {$userIdRes}";
        $queryPostRes = mysqli_query($conn, $queryPost);
        if (!$queryPostRes) die('878');
        echo "delete";
        exit();
    }
    $queryPost = "INSERT INTO hw1_playlists (playlistDeezerId, playlistUser, date) VALUES ('{$content_id}', {$userIdRes}, now())";
    
    $queryPostRes = mysqli_query($conn, $queryPost);
    if (!$queryPostRes) die('878');
    
    echo "success";
    exit();
}
?>


<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta content="Crea Post" name="description">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <title>Search</title>
    <link rel="icon" href="favicon.png" sizes="64x64" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;400&display=swap" rel="stylesheet">
    <script type="text/javascript">
        const uri = "<?php echo $uri ?>";
    </script>
    <link rel="stylesheet" href="common.css">
    <link rel="stylesheet" href="search_content.css">
    <script defer src="search_content.js"></script>
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
    <!-- The form -->
    <div class="body-container">
        <div class="wrapper">
            <form class="search-form" action="javascript:void(0);">
                <input type="text" placeholder="Search Deezer playlists..." name="search">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>
        
    </div>
    <div class="results-container"></div>
</body>

</html>