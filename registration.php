<?php
include "includes/db.php";
include "includes/header.php";
include "admin/functions.php";
?>

<!-- Navigation -->

<?php  include "includes/navigation.php"; ?>


<?php


// ispis gresaka pri registraciji
if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $username = escape($username);
    $email = escape($email);
    $password = escape($password);

    $username = htmlspecialchars($username);
    $email = htmlspecialchars($email);
    $password = htmlspecialchars($password);


    $error = [
        'username' => '',
        'email' => '',
        'password' => ''
    ];

    if(strlen($username) < 4){
        $error['username'] = _REG_ERR_UN_MSG1;
    }

    if($username == ''){
        $error['username'] = _REG_ERR_UN_MSG2;
    }

    if(username_exists($username)){
        $error['username'] = _REG_ERR_UN_MSG3;
    }

    if($email == ''){
        $error['email'] = _REG_ERR_EM_MSG1;
    }

    if(email_exists($email)){
        $error['email'] = _REG_ERR_EM_MSG2;
    }

    if($password == ''){
        $error['password'] = _REG_ERR_PSW_MSG1;
    }


    foreach($error as $key => $value){
        if(empty($value)){
            unset($error[$key]);
        }
    } // foreach end


    if(empty($error)){
        register_user($username, $email, $password);
        echo "<h2 class='alert alert-success text-center'>" . _REG_SUCCESS_MSG . "{$username}</h2>";
    }
} // ispis gresaka pri registraciji - END




?>


<!-- Page Content -->
<div class="container">


<section id="login">
    <div class="container">
        <div class="row">
            <div class="col-xs-6 col-xs-offset-3">
                <div class="form-wrap">
                    <h1><?php echo _REGISTRATION; ?></h1>
                    <form role="form" action="registration.php" method="post" id="login-form" autocomplete="off">
                        <div class="form-group">
                            <label for="username" class="sr-only">username</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="<?php echo _ENT_USERNAME; ?>" autocomplete="on" 
                                   value="<?php echo isset($username) ? $username : ''; ?>"
                                   >
                            <p class="text-danger"><?php echo isset($error['username']) ? $error['username'] : ''; ?></p>
                        </div>
                        <div class="form-group">
                            <label for="email" class="sr-only">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="<?php echo _ENT_EMAIL; ?>" autocomplete="on"
                                   value="<?php echo isset($email) ? $email : ''; ?>"
                                   >
                            <p class="text-danger"><?php echo isset($error['email']) ? $error['email'] : ''; ?></p>
                        </div>
                        <div class="form-group">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" name="password" id="key" class="form-control" placeholder="<?php echo _PASSWORD; ?>">
                            <p class="text-danger"><?php echo isset($error['password']) ? $error['password'] : ''; ?></p>
                        </div>

                        <input type="submit" name="submit" id="btn-login" class="btn btn-custom btn-lg btn-block" value="<?php echo _REGISTRATION; ?>">
                    </form>

                </div>
            </div> <!-- /.col-xs-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</section>


<hr>



<?php include "includes/footer.php";?>
