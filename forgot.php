<?php  include "includes/db.php"; ?>
<?php  include "includes/header.php"; ?>
<?php  include "admin/functions.php"; ?>

<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';
?>


<?php  include "includes/navigation.php"; ?>


<?php
if(!isset($_GET['forgot'])){
    redirect('index');
}


if(isset($_POST['recover-submit'])){
    $email = escape($_POST['email']);
    $length = 50;
    $token = bin2hex(random_bytes($length));

    if(email_exists($email)){
        $stmt = mysqli_prepare($connection, "UPDATE users SET token = '{$token}' WHERE user_email = ?")
            or die("EMAIL forget password QUERY FAILED " . mysqli_error($connection));
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);


        /******************************
        *
        *   PHPMailer configuration:
        *
        ******************************/

        $mail = new PHPMailer();

        /**** Server settings ****/
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                    // Enable verbose debug output
        $mail->isSMTP();                                          // Send using SMTP
        $mail->Host       = Config::SMTP_HOST;                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                 // Enable SMTP authentication
        $mail->Username   = Config::SMTP_USER;                    // SMTP username
        $mail->Password   = Config::SMTP_PASSWORD;                // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;       // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = Config::SMTP_PORT;                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        $mail->CharSet = 'UTF-8';                                 // Omogucavan UTF-8 karaktere


        /**** Recipients ****/
        $mail->setFrom(Config::FROM_EMAIL, Config::FROM_NAME);
        $mail->addAddress($email);                                // Add a recipient


        /**** Content ****/
        $mail->isHTML(true);                                      // Set email format to HTML
        $mail->Subject = _PSW_RESET_EMAIL_TITLE;
        $mail->Body    = _PSW_RESET_EMAIL_BODY . '<br><a href="http://localhost/cms/reset.php?email='.$email.'&token='.$token.'">http://localhost/cms/reset.php?email='.$email.'&token='.$token.'</a></p>';

        if($mail->send()){
            $emailIsSent = true;
        }
    } else {
        $msg = _ENT_VALID_EMAIL;
    }
}

?>

<!-- Page Content -->
<div class="container">

    <div class="form-gap"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">


                            <h3><i class="fa fa-lock fa-4x"></i></h3>
                            <h2 class="text-center"><?php echo _FORGOT_PSW; ?></h2>
                            <p><?php echo _RESET_INFO_MSG; ?></p>
                            <div class="panel-body">




                                <form id="register-form" role="form" autocomplete="on" class="form" method="post">

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                                            <input id="email" name="email" placeholder="<?php echo _ENT_EMAIL; ?>" class="form-control" type="email">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input name="recover-submit" class="btn btn-lg btn-primary btn-block" value="<?php echo _RESET_PSW; ?>" type="submit">
                                    </div>

                                    <input type="hidden" class="hide" name="token" id="token" value="">
                                </form>

                            </div><!-- Body-->

                            <?php
                            if(isset($emailIsSent) && $emailIsSent === true){
                                echo "<h3 class='alert alert-success'>"._CHECK_EMAIL."</h3>";
                            }

                            if(isset($msg) && $msg !=''){
                                echo "<h3 class='alert alert-danger'> $msg </h3>";
                            }

                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <hr>

    <?php include "includes/footer.php";?>

</div> <!-- /.container -->

