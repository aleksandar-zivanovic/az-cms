<?php include_once "includes/admin_header.php"; ?>


<div id="wrapper">

    <!-- Navigation -->
    <?php include_once "includes/admin_navigation.php"; ?>


    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        <?php echo _WLC_MSG_USER_DASHBOARD; ?>
                        <small><?php echo $_SESSION['username']; ?></small>
                    </h1>

                    <?php

                    if(isset($_GET['source'])){
                        $source = $_GET['source'];
                    } else {
                        $source = '';
                    }

                    switch($source){
                        case 'add_post':
                            include "includes/add_post.php";
                            break;

                        case 'edit_post':
                            include "includes/edit_post.php";
                            break;

                        default:
                            include "includes/view_my_posts.php";
                            break;

                    }


                    ?>

                </div>
            </div>
            <!-- /.row -->

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->



    <?php include_once "includes/admin_footer.php"; ?>