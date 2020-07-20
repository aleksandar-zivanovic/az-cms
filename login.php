<?php ob_start(); ?>
<?php include_once "includes/db.php"; ?>
<?php include_once "includes/header.php"; ?>
<?php include_once "admin/functions.php"; ?>

<?php

if(isset($_SESSION['username'])){
    redirect('index.php');
}


if(isset($_POST['login'])){
    login_user($_POST['username'], $_POST['password']);
}

?>


<!-- Navigation -->

<?php include_once "includes/navigation.php"; ?>


<!-- Page Content -->
<div class="container">

    <div class="form-gap"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">


                            <h3><i class="fa fa-user fa-4x"></i></h3>
                            <h2 class="text-center"><?php echo _LOGIN; ?></h2>
                            <div class="panel-body">


                                <form id="login-form" role="form" autocomplete="off" class="form" method="post">

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-user color-blue"></i></span>

                                            <input name="username" type="text" class="form-control" placeholder="<?php echo _ENT_USERNAME; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock color-blue"></i></span>
                                            <input name="password" type="password" class="form-control" placeholder="<?php echo _ENT_PSW; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">

                                        <input name="login" class="btn btn-lg btn-primary btn-block" value="<?php echo _LOGIN; ?>" type="submit">
                                    </div>


                                </form>

                                <?php
                                if(isset($_GET['login_error_msg'])){
                                    echo "<h4 class='text-danger text-center'>" . _LOG_IN_ERR_MSG . "</h4>";
                                }
                                ?>

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
