<?php

/* provera upita - mysqli_error */
function confirm_query($result){
    global $connection;
    if(!$result){
        die("QUERY FAILED! " . mysqli_error($connection));
    }
}


function escape($string){
    global $connection;
    return mysqli_real_escape_string($connection, trim($string));
}


function redirect($url){
    header('location:' . $url);
    exit;
}


// funkcija za slanje querija - proceduralno
function query($query){
    global $connection;
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    return $result;
}

function isLoggedIn(){
    if(isset($_SESSION['user_role'])){
        return true;
    } else {
        return false;
    }
}


function loggedInUserId(){
    if(isLoggedIn()){
        $result = query("SELECT * FROM users WHERE username ='".$_SESSION['username']."'");
        confirm_query($result);
        $user = mysqli_fetch_array($result);
        return mysqli_num_rows($result) >= 1 ? $user['user_id'] : false;
    } else {
        return false;
    }
}


// f-ja proverava da li je loginovani korisnik lajkovao post
function userLikedThisPost($post_id=''){
    $result = query("SELECT * FROM likes WHERE user_id = ".loggedInUserId()." AND post_id = {$post_id}");
    return mysqli_num_rows($result) >= 1 ? true : false;
}


// broj lajkova za odredjeni post
function getPostLikes($post_id){
    $result = query("SELECT * FROM likes WHERE post_id = {$post_id}");
    return mysqli_num_rows($result);
}




// broj loginovanih korisnika koji se nalaze u Admin panelu u zadnjih $time_out_in_second sekundi
//function users_online(){
//    global $connection;
//    $session = session_id();
//    $time = time();
//    $time_out_in_second = 20;
//    $time_out = $time - $time_out_in_second;
//
//    $query = "SELECT * FROM users_online WHERE session ='{$session}';";
//    $send_query = mysqli_query($connection, $query);
//    confirm_query($send_query);
//    $count = mysqli_num_rows($send_query);
//
//    if($count == NULL){
//        $query = "INSERT INTO users_online (session, time) VALUES ('{$session}', '{$time}');";
//        $if_count_null_query = mysqli_query($connection, $query);
//        confirm_query($if_count_null_query);
//    } else {
//        $query = "UPDATE users_online SET time = '{$time}' WHERE session ='{$session}';";
//        $if_count_exists_query = mysqli_query($connection, $query);
//        confirm_query($if_count_exists_query);
//    }
//    $users_online_query = mysqli_query($connection, "SELECT * FROM users_online WHERE time > {$time_out};");
//    // ova dva query-ja ispod mogu isto da se koristi, ali nema potrebe za gornjom granicom u ovom primeru
//    //    $users_online_query = mysqli_query($connection, "SELECT * FROM users_online WHERE time >= {$time_out} AND time <= {$time};");
//    //    $users_online_query = mysqli_query($connection, "SELECT * FROM users_online WHERE time BETWEEN {$time_out} AND {$time};");
//    confirm_query($users_online_query);
//    return $count_user = mysqli_num_rows($users_online_query);
//}



// default placeholder slika za post, ako korisnik nije dodao u post nijednu sliku
function imagePlaceholder($image){
    if(!$image){
        return 'placeholder-900x300.png';
    } else {
        return $image;
    }
}



// broj loginovanih korisnika koji se nalaze u Admin panelu LIVE - koristi AJAX iz admin/js/script.js
function users_online_live(){
    if(isset($_GET['onlineusers'])){
        global $connection;

        if(!$connection){
            session_start();
            include("../includes/db.php");

            $session = session_id();
            $time = time();
            $time_out_in_second = 20;
            $time_out = $time - $time_out_in_second;

            $query = "SELECT * FROM users_online WHERE session ='{$session}';";
            $send_query = mysqli_query($connection, $query);
            confirm_query($send_query);
            $count = mysqli_num_rows($send_query);

            if($count == NULL){
                $query = "INSERT INTO users_online (session, time) VALUES ('{$session}', '{$time}');";
                $if_count_null_query = mysqli_query($connection, $query);
                confirm_query($if_count_null_query);
            } else {
                $query = "UPDATE users_online SET time = '{$time}' WHERE session ='{$session}';";
                $if_count_exists_query = mysqli_query($connection, $query);
                confirm_query($if_count_exists_query);
            }

            $users_online_query = mysqli_query($connection, "SELECT * FROM users_online WHERE time > {$time_out};");
            confirm_query($users_online_query);
            echo $count_user = mysqli_num_rows($users_online_query);
        }
    }
}
users_online_live();



function insert_categories(){
    global $connection;
    if(isset($_POST['submit'])){
        $cat_title = $_POST['cat_title'];
        $cat_title= mysqli_real_escape_string($connection, trim(strip_tags($cat_title)));

        if($cat_title == '' || empty($cat_title)){
            echo "This field should not be empty!";
        } else {
            $stmt = mysqli_prepare($connection, "INSERT INTO categories (cat_title) VALUES (?)");
            mysqli_stmt_bind_param($stmt, 's', $cat_title);
            mysqli_stmt_execute($stmt);

            if(!$stmt){
                die("QUERY FIELD " . mysqli_error($connection));
            }
        } // else end
        mysqli_stmt_close($stmt);
    } // if end
}


function findAllCategories() {
    global $connection;
    $query = "SELECT * FROM categories";
    $select_categories = mysqli_query($connection,$query);

    while($row = mysqli_fetch_assoc($select_categories)){
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];

        echo "<tr>";
        echo "<td>{$cat_id}</td>";
        echo "<td>{$cat_title}</td>";
        echo "<td><a href='categories.php?delete={$cat_id}'>" . _DELETE_V . "</a></td>";
        echo "<td><a href='categories.php?edit={$cat_id}'>" . _EDIT_V . "</a></td>";
        echo "</tr>";
    }
}


function deleteCategories(){
    global $connection;
    if(isset($_GET['delete'])){
        $the_cat_id = mysqli_real_escape_string($connection, trim($_GET['delete']));
        $query = "DELETE FROM categories WHERE cat_id = {$the_cat_id}";
        $delete_query = mysqli_query($connection, $query);
        header("location: categories.php?del_cat=successful");
    }
}



// mysqli_num_rows upit za admin/index.php
// koristim za izracunavanje ukupno postova, komentara, korisnika i kategorija na index strani
function recordCount($table){
    global $connection;
    $saf_query = "SELECT * FROM $table;";
    $sending_query = mysqli_query($connection, $saf_query);
    confirm_query($sending_query);
    $result = mysqli_num_rows($sending_query);
    return $result;
}



function checkStatus($table, $column, $status){
    global $connection;
    $query = "SELECT * FROM $table WHERE $column = '$status';";
    $send_query = mysqli_query($connection, $query);
    confirm_query($send_query);
    return mysqli_num_rows($send_query);
}


function checkStatusForTheUser($table, $column1, $column2, $status){
    global $connection;
    $query = "SELECT * FROM $table WHERE $column1 = '".$_SESSION['username']."' AND $column2 = '$status';";
    $send_query = mysqli_query($connection, $query);
    confirm_query($send_query);
    return mysqli_num_rows($send_query);
}



function is_admin($username){
    global $connection;
    $query = "SELECT user_role FROM users WHERE username = '$username';";
    $send_query = mysqli_query($connection, $query);
    confirm_query($send_query);
    $row = mysqli_fetch_array($send_query);

    if($row['user_role'] == 'admin'){
        return true;
    } else {
        return false;
    }

}


// provera duplih username-a pri registraciji
function username_exists($username){
    global $connection;
    $query = "SELECT username FROM users WHERE username = '$username';";
    $result = mysqli_query($connection, $query);
    confirm_query($result);

    if(mysqli_num_rows($result) > 0){
        return true;
    } else {
        return false;
    }
}


// provera duplih email adresa pri registraciji
function email_exists($email){
    global $connection;
    $query = "SELECT user_email FROM users WHERE user_email = '$email';";
    $result = mysqli_query($connection, $query);
    confirm_query($result);

    if(mysqli_num_rows($result) > 0){
        return true;
    } else {
        return false;
    }
}



function register_user($username, $email, $password){
    global $connection;

    $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);


    $query = "INSERT INTO users (username, user_email, user_password, user_role) ";
    $query .= "VALUES('{$username}', '{$email}', '{$password}', 'subscriber');";
    $register_user_query = mysqli_query($connection, $query)
        or die("QUERY FAILED ". mysqli_error($connection));

    //    if($register_user_query){
    //        echo "<h2 class='text-success text-center'>Successfuly created user: {$username}</h2>";
    //    }
}



function login_user($username, $password){
    global $connection;

    $username = escape($username);
    $password = escape($password);

    $query = "SELECT * FROM users WHERE username = '$username';";
    $select_user_query = mysqli_query($connection, $query)
        or die('login query failed!!! ' . mysqli_error($connection));


    while($row = mysqli_fetch_assoc($select_user_query)){
        $db_user_id = $row['user_id'];
        $db_username = $row['username'];
        $db_user_password = $row['user_password'];
        $db_user_firstname = $row['user_firstname'];
        $db_user_lastname = $row['user_lastname'];
        $db_user_role = $row['user_role'];
    }


    if($username === $db_username && password_verify($password, $db_user_password)){
        $_SESSION['username'] = $db_username;
        $_SESSION['firstname'] = $db_user_firstname;
        $_SESSION['lastname'] = $db_user_lastname;
        $_SESSION['user_role'] = $db_user_role;
        header("location:admin");
    } else {
        header("location:".$_SERVER['PHP_SELF']."?login_error_msg=error");
    }
}


function language_selection(){
    if(isset($_POST['lang']) && !empty($_POST['lang'])){
        $_SESSION['lang'] = $_POST['lang'];

        if(isset($_SESSION['lang']) && $_SESSION['lang'] != $_POST['lang']){
            echo "<script>location.reload();</script>";
        }
    }

    if(isset($_SESSION['lang'])){
        include "includes/languages/".$_SESSION['lang'].".php";
    } else {
        include "includes/languages/en.php";
    }
}


// u admin panelu zbog urla
function language_selection_admin(){
    if(isset($_POST['lang']) && !empty($_POST['lang'])){
        $_SESSION['lang'] = $_POST['lang'];

        if(isset($_SESSION['lang']) && $_SESSION['lang'] != $_POST['lang']){
            echo "<script>location.reload();</script>";
        }
    }

    if(isset($_SESSION['lang'])){
        include "../includes/languages/".$_SESSION['lang'].".php";
    } else {
        include "../includes/languages/en.php";
    }
}


// status komentara u comment.php
function comment_status($status){
    if($status == 'approved'){
        echo "<td class='text-success text-center'>" . _APPROVED . "</td>";
    }
    
    if($status == 'unapproved'){
        echo "<td class='text-warning text-center'>" . _UNAPPROVED . "</td>";
    }
    
        if($status == 'deleted'){
        echo "<td class='text-danger text-center'>" . _DELETED . "</td>";
    }
}


?>