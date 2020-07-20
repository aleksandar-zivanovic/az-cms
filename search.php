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
            if (isset($_POST['submit'])){
                $search = $_POST['search'];
                $search = mysqli_real_escape_string($connection, trim($search));

                global $connection;
                $query = "SELECT * FROM posts WHERE post_tags LIKE '%$search%'";
                $search_query = mysqli_query($connection, $query);

                if(!$search_query){
                    die("QUERY FAILD " . mysqli_error($connection));
                }

                $count = mysqli_num_rows($search_query);

                if($count == 0){
                    echo "<h1 class='text-center'>" . _NO_RESULT . "</h1>";
                } else {
            ?>
            <h3 class="page-header">
                <?php echo _TOTAL_RESULTS . "\"{$search}\" = " . $count; ?>
            </h3>

            <?php
                    while($row = mysqli_fetch_assoc($search_query)){
                        $post_title = $row['post_title'];
                        $post_author = $row['post_author'];
                        $post_date = $row['post_date'];
                        $post_image = $row['post_image'];
                        $post_content = $row['post_content'];
                        $post_id = $row['post_id'];

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
                <a href="<?php echo $post_id . '-' . $post_title_clean; ?>"><?php echo $post_title; ?></a>
            </h2>
            <p class="lead">
                by <a href="/cms/author-<?php echo $post_author_clean; ?>"><?php echo $post_author; ?></a>
            </p>
            <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date; ?></p>
            <hr>
            <img class="img-responsive" src="images/<?php echo $post_image; ?>" alt="">
            <hr>
            <p><?php echo $post_content; ?></p>
            <a class="btn btn-primary" href="<?php echo $post_id . '-' . $post_title_clean; ?>" > <?php echo _READ_MORE; ?> <span class="glyphicon glyphicon-chevron-right"></span></a>

            <hr>
            <?php
                    }

                }
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
