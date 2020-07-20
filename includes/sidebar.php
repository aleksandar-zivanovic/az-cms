<div class="col-md-4">



    <!-- Blog Search Well -->
    <div class="well">
        <h4><?php echo _BLOG_SEARCH; ?></h4>
        <form action="/cms/search.php" method="post">
            <div class="input-group">
                <input type="text" name="search" class="form-control">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit" name="submit">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </span>
            </div>
        </form> <!-- Search form end  -->
        <!-- /.input-group -->
    </div>



    <!-- Login -->
    <div class="well">


        <?php
        if(!isset($_SESSION['user_role'])){
        ?>

        <h4><?php echo _LOGIN; ?></h4>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="form-group">
                <input type="text" name="username" class="form-control" placeholder="<?php echo _ENT_USRNM; ?>">

            </div>
            <div class="input-group">
                <input type="password" name="password" class="form-control" placeholder="<?php echo _ENT_PSW; ?>">
                <span class="input-group-btn">
                    <button class="btn btn-primary" name="login" type="submit"><?php echo _SUBMIT; ?></button>
                </span>
            </div>
        </form>  <!-- Login form end  -->
        <br>
        <div class="form-group">
            <a href="forgot.php?forgot=<?php echo uniqid(); ?>" class=""><?php echo _FORGOT_PSW; ?></a>
        </div>
        <div>
            <?php
            // salje podatke funkciji za login
            if(isset($_POST['login'])){
                login_user($_POST['username'], $_POST['password']);
            }

            // dobija error poruku iz funkcije za login ako su user ili password neispravni
            if(isset($_GET['login_error_msg'])){
                echo "<h4 class='text-danger text-center'>" . _LOG_IN_ERR_MSG . "</h4>";
            }
            ?>
        </div>

        <?php
        }else{
        ?>
        <form action="includes/logout.php" method="post">
            <div class="input-group">
                <h4><?php echo _LOGGED_AS . $_SESSION['username']; ?></h4>
                <a href="includes/logout.php" class="btn btn-primary"><?php echo _LOGOUT; ?></a>
            </div>
        </form>

        <?php
        } 
        ?>
        <!-- LogOut form end  -->


    </div>

    <!-- Blog Categories Well -->
    <div class="well">

        <?php
        $query = "SELECT * FROM categories";
        $select_categories_sidebar = mysqli_query($connection,$query);
        ?>

        <h4><?php echo _BLOG_CATEGORIES; ?></h4>
        <div class="row">
            <div class="col-lg-12">
                <ul class="list-unstyled">

                    <?php
                    while($row = mysqli_fetch_assoc($select_categories_sidebar)){
                        $cat_title = $row['cat_title'];
                        $cat_id = $row['cat_id'];

                        $query = "SELECT * FROM posts WHERE post_category_id = '$cat_id';";
                        $all_posts_by_cat_id = mysqli_query($connection,$query)
                            or die("QUERY FAILED " . mysqli_error($connection));
                        $post_count = mysqli_num_rows($all_posts_by_cat_id);

                        $cat_title_lc = strtolower($cat_title);
                        $cat_title_plus = preg_replace('/(\+)/', '-plus', $cat_title_lc);
                        $cat_title_clean = preg_replace('/\W+/', '-', $cat_title_plus);

                        echo "<li><a href='/cms/category/{$cat_id}-{$cat_title_clean}'>{$cat_title} ({$post_count})</a></li>";
                    }
                    ?>

                </ul>
            </div>
            <!-- /.col-lg-6 -->
        </div>
        <!-- /.row -->
    </div>

    <!-- Side Widget Well -->
    <?php include_once "widget.php"; ?>

</div>