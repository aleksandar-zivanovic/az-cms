<?php include_once "includes/admin_header.php"; ?>

<?php

if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];

    $query = "SELECT * FROM users WHERE username = '{$username}';";

    $select_user_profile = mysqli_query($connection, $query);
    confirm_query($select_user_profile);

    while($row = mysqli_fetch_array($select_user_profile)){
        $user_id = $row['user_id'];
        $username = $row['username'];
        $user_password = $row['user_password'];
        $user_firstname = $row['user_firstname'];
        $user_lastname = $row['user_lastname'];
        $user_email = $row['user_email'];
        $user_image = $row['user_image'];
    }
}

if(isset($_POST['edit_user'])){
    $user_firstname = $_POST['user_firstname'];
    $user_lastname = $_POST['user_lastname'];
    $username = $_POST['username'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];

    $user_firstname = escape($user_firstname);
    $user_lastname = escape($user_lastname);
    $username = escape($username);
    $user_email = escape($user_email);
    $user_password = escape($user_password);

    if(!empty($user_password)){
        $user_password = password_hash($user_password, PASSWORD_BCRYPT, ['cost' => 12]);

        $query = "UPDATE users SET ";
        $query .= "user_firstname = '{$user_firstname}', ";
        $query .= "user_lastname = '{$user_lastname}', ";
        $query .= "username = '{$username}', ";
        $query .= "user_email = '{$user_email}', ";
        $query .= "user_password = '{$user_password}' ";
        $query .= "WHERE username = '{$_SESSION['username']}' ";
        $_SESSION['username'] = $username;

        $edit_user_query = mysqli_query ($connection, $query);
        confirm_query($edit_user_query);
        header("location:profile.php?updated");
    }else{
        header("location:profile.php?no_pass_msg");
    }
}

?>


<div id="wrapper">

    <!-- Navigation -->
    <?php include_once "includes/admin_navigation.php"; ?>


    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        <?php echo _WLC_MSG_ADMIN_PANEL; ?>
                        <small><?php echo $_SESSION['username']; ?></small>
                    </h1>

                    <?php
                    if(isset($_GET['no_pass_msg'])){
                        echo "<p class='bg-danger'>" . _PROFILE_ERR_PSW_NEEDED . "</p>";
                    }

                    if(isset($_GET['updated'])){
                        echo "<p class='bg-success'>" . _PROFILE_IS_UPDATED . "</p>";
                    }
                    ?>

                    <form action="" method="post" enctype="multipart/form-data">

                        <div class="form-group">
                            <label for="author"><?php echo _FIRSTNAME; ?></label>
                            <input type="text" value="<?php echo $user_firstname; ?>" class="form-control" name="user_firstname">
                        </div>

                        <div class="form-group">
                            <label for="post_status"><?php echo _LASTNAME; ?></label>
                            <input type="text" value="<?php echo $user_lastname; ?>" class="form-control" name="user_lastname">
                        </div>

                        <!--
<div class="form-group">
<label for="file">Post Image</label>
<input type="file" name="image">
</div>
-->

                        <div class="form-group">
                            <label for="title"><?php echo _USERNAME; ?></label>
                            <input type="text" value="<?php echo $username; ?>" class="form-control" name="username">
                        </div>

                        <div class="form-group">
                            <label for="post_tags"><?php echo _EMAIL; ?></label>
                            <input type="email" value="<?php echo $user_email; ?>" class="form-control" name="user_email">
                        </div>

                        <div class="form-group">
                            <label for="post_tags"><?php echo _PASSWORD; ?></label>
                            <input autocomplete="off" type="password" class="form-control" name="user_password">
                        </div>

                        <div class="form-group">
                            <input class="btn btn-primary" type="submit" name="edit_user" value="<?php echo _UPDATE_PROFILE; ?>">
                        </div>

                    </form>

                </div>
            </div>
            <!-- /.row -->

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->



    <?php include_once "includes/admin_footer.php"; ?>