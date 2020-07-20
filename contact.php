<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>
<?php include_once "admin/functions.php"; ?>

<!-- Navigation -->

<?php include "includes/navigation.php"; ?>

<?php


if(isset($_POST['submit'])){
    $to = "cmssupport@azivanovic.com";
    $subject = wordwrap($_POST['subject'], 70);
    $body = $_POST['body'];
    $header = "From: " .$_POST['email'];

    if(mail($to, $subject, $body, $header)){
        echo "<h2 class='text-success text-center'>" . _EMAIL_SUCCESS . "</h2>";
    } else {
        echo "<h2 class='text-danger text-center'>" . _EMAIL_ERROR . "</h2>";
    }
}

?>


<!-- Page Content -->
<div class="container">

    <section id="login">
        <div class="container">
            <div class="row">
                <div class="col-xs-6 col-xs-offset-3">
                    <div class="form-wrap">
                        <h1><?php echo _CONTACT; ?></h1>
                        <form role="form" action="contact.php" method="post" id="contact-form" autocomplete="off">
                            <div class="form-group">
                                <label for="username" class="sr-only">Subject</label>
                                <input type="text" name="subject" id="subject" class="form-control" placeholder="<?php echo _SUBJECT; ?>">
                            </div>
                            <div class="form-group">
                                <label for="email" class="sr-only">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="<?php echo _ENT_EMAIL; ?>">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="body" id="body" cols="30" rows="10"></textarea>
                            </div>

                            <input type="submit" name="submit" id="btn-login" class="btn btn-custom btn-lg btn-block" value="<?php echo _SUBMIT; ?>">
                        </form>

                    </div>
                </div> <!-- /.col-xs-12 -->
            </div> <!-- /.row -->
        </div> <!-- /.container -->
    </section>


    <hr>



    <?php include "includes/footer.php";?>
