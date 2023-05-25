<?php
require_once "init.php";
header("Content-Type: application/json; charset=UTF-8");

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

if (isset($_POST["search"]) && empty(trim( $_POST["search"]))) {
    echo "321";
    exit();
}


function DeezerSearch_proc($search_query) {
    $query = "https://api.deezer.com/search/playlist?q=" . urlencode($search_query);
    $curl_handle = curl_init($query);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($curl_handle);
    curl_close($curl_handle);

    echo $res;

}

$search_query = trim($_POST["search"]);
DeezerSearch_proc($search_query);
