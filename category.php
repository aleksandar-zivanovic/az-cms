<?php include_once "includes/db.php"; ?>
<?php include_once "includes/header.php"; ?>
<?php include_once "admin/functions.php"; ?>

<!-- Navigation -->
<?php include_once "includes/navigation.php"; ?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">

            <?php

            if(isset($_GET['category'])){
                $post_category_id = $_GET['category'];
                $post_category_id = mysqli_real_escape_string($connection, trim($post_category_id));

                if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'){
                    $stmt1 = mysqli_prepare($connection, "SELECT post_id, post_title, post_author, post_date, post_image, post_content FROM posts WHERE post_category_id = ? ORDER BY post_id DESC");
                } else {
                    $stmt2 = mysqli_prepare($connection, "SELECT post_id, post_title, post_author, post_date, post_image, post_content FROM posts WHERE post_category_id = ? AND post_status = ? ORDER BY post_id DESC");

                    $published = 'published';
                }

                if(isset($stmt1)){

                    mysqli_stmt_bind_param($stmt1, "i", $post_category_id);
                    mysqli_stmt_execute($stmt1);
                    mysqli_stmt_bind_result($stmt1, $post_id, $post_title, $post_author, $post_date, $post_image, $post_content);
                    $stmt = $stmt1;


                } else {

                    mysqli_stmt_bind_param($stmt2, "is", $post_category_id, $published);
                    mysqli_stmt_execute($stmt2);
                    mysqli_stmt_bind_result($stmt2, $post_id, $post_title, $post_author, $post_date, $post_image, $post_content);
                    $stmt = $stmt2;

                }

                mysqli_stmt_store_result($stmt);


                if(mysqli_stmt_num_rows($stmt) < 1){
                    echo "<h1 class='text-center'>" . _NO_POSTS . "</h1>";
                } else {
                    while(mysqli_stmt_fetch($stmt)){

                        $post_title_lc = strtolower($post_title);

                        /* ciscenje od specijalnih karaktera pomocu REGEX-a */
                        $post_title_clean = preg_replace('/\W+/', '-', $post_title_lc);
                        /* ciscenje od karaktera -, ako je jedan ili vise njih poslednji karakter u stringu pomocu REGEX-a */
                        $post_title_clean = preg_replace('/-+$/', '', $post_title_clean);

                        $post_author_lc = strtolower($post_author);
                        $post_author_clean = preg_replace('/\s/', '-', $post_author_lc);

            ?>

            <!-- Blog Post -->
            <h2>
                <a href="/cms/<?php echo $post_id . "-" . $post_title_clean; ?>"><?php echo $post_title; ?></a>
            </h2>
            <p class="lead">
                <?php echo _BY; ?> <a href="/cms/author-<?php echo $post_author_clean; ?>"><?php echo $post_author; ?></a>
            </p>
            <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date; ?></p>
            <hr>
            <a href="/cms/<?php echo $post_id . "-" . $post_title_clean; ?>">
                <img class="img-responsive" src="/cms/images/<?php echo imagePlaceholder($post_image); ?>" alt="alternative blog image">
            </a>
            <hr>
            <p><?php echo $post_content; ?></p>
            <a class="btn btn-primary" href="/cms/<?php echo $post_id . "-" . $post_title_clean; ?>"><?php echo _READ_MORE; ?> <span class="glyphicon glyphicon-chevron-right"></span></a>

            <hr>


            <?php
                    } // while loop end
                    mysqli_stmt_close($stmt);
                } // else end
            }
            ?>
        </div>



        <!-- Blog Sidebar Widgets Column -->
        <?php include_once "includes/sidebar.php"; ?>

    </div>
    <!-- /.row -->

    <hr>

    <?php
    include_once "includes/footer.php";
    ?>
