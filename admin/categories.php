<?php include_once "includes/admin_header.php"; ?>

<?php
if(!is_admin($_SESSION['username'])){
    redirect('dashboard.php');
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


                    <div class="col-xs-6">

                        <?php 

                        if(isset($_GET['del_cat']) && $_GET['del_cat'] != ''){
                            echo "<h3 class='text-success'>" . _CAT_DELETE_MSG . "</h3>";
                        }


                        insert_categories();
                        ?>


                        <!--Add Category Form-->
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="cat_title"><?php echo _ADD_CAT; ?></label>
                                <input type="text" class="form-control" name="cat_title">
                            </div>
                            <div class="form-group">
                                <input class="btn btn-primary" type="submit" name="submit" value="<?php echo _ADD_CAT; ?>">
                            </div>

                        </form>


                        <!--Edit Category Form-->
                        <?php 

                        if(isset($_GET['edit'])){
                            $cat_id = $_GET['edit'];
                            include_once "includes/update_categories.php";
                        }?>

                    </div> 

                    <div class="col-xs-6">

                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th><?php echo _CAT_TITLE; ?></th>
                                </tr>
                            </thead>
                            <tbody>


                                <?php findAllCategories(); ?>

                                <?php deleteCategories(); ?>


                            </tbody>
                        </table>

                    </div>




                </div>
            </div>
            <!-- /.row -->

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->



    <?php include_once "includes/admin_footer.php"; ?>