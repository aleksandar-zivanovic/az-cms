<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>
<?php include "admin/functions.php"; ?>

<!-- Navigation -->
<?php include "includes/navigation.php"; ?>

<?php

if(!isset($_GET['token']) || !isset($_GET['email'])){
    redirect(index);
} else {
    $token = escape($_GET['token']);
    $token = htmlspecialchars($token);

    $email = escape($_GET['email']);
    $email = htmlspecialchars($email);
}


if($stmt = mysqli_prepare($connection, "SELECT username, user_email, token FROM users WHERE token = ?")){
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $username, $user_email, $db_token);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if($token == $db_token){

        if(isset($_POST['password']) && isset($_POST['confirmPassword'])){
            if($_POST['password'] == $_POST['confirmPassword']){

                $newPassword = escape($_POST['confirmPassword']);
                $newPassword = htmlspecialchars($newPassword);
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);

                $query = "UPDATE users SET user_password = ?, token = '' WHERE user_email = ?";
                if($stmt = mysqli_prepare($connection, $query)){
                    mysqli_stmt_bind_param($stmt, "ss", $hashedPassword, $email);
                    mysqli_stmt_execute($stmt);
                    if(mysqli_stmt_affected_rows($stmt)>=1){
                        $ch_psw_msg = "<h3 class='text-success text-center'>Password is updated</h3>";
                    } else {
                        $ch_psw_msg = "<h3 class='text-danger text-center'>Password is <b>NOT</b> updated</h3>";
                    }
                }
            } else {
                $ch_psw_msg = "<h3 class='text-danger' text-center>Passwords didn't match</h3>";
            }
        }

    } else {
        redirect(index);
    }
}


?>

<div class="container">



    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">


                            <h3><i class="fa fa-lock fa-4x"></i></h3>
                            <h2 class="text-center">Reset Password</h2>
                            <p>You can reset your password here.</p>
                            <div class="panel-body">


                                <form id="register-form" role="form" autocomplete="off" class="form" method="post">

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-user color-blue"></i></span>
                                            <input id="password" name="password" placeholder="Enter password" class="form-control"  type="password">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-ok color-blue"></i></span>
                                            <input id="confirmPassword" name="confirmPassword" placeholder="Confirm password" class="form-control"  type="password">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input name="resetPassword" class="btn btn-lg btn-primary btn-block" value="Reset Password" type="submit">
                                    </div>

                                    <input type="hidden" class="hide" name="token" id="token" value="">

                                    <?php 
                                    if(isset($ch_psw_msg)){
                                        echo $ch_psw_msg;
                                    }
                                    ?>

                                </form>

                            </div><!-- Body-->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <hr>

    <?php include "includes/footer.php";?>

</div> <!-- /.container -->
