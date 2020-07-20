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

            $post_query_count = "SELECT * FROM posts WHERE post_status = 'published';";
            $find_count = mysqli_query($connection, $post_query_count)
                or die("QUERY FAILED " . mysqli_error($connection));
            $count = mysqli_num_rows($find_count);
            if($count < 1){
                echo "<h1 class='text-center'>" . _NO_POSTS . "</h1>";
            } else {

                $per_page = 5;
                $count = ceil($count / $per_page);


                if(isset($_GET['page'])){
                    $page = $_GET['page'];  
                } else {
                    $page = "";
                }

                if($page == 1 || $page == ""){
                    $page_start = 0;
                } else {
                    $page_start = ($page * $per_page) - $per_page;
                }

                $page_start = mysqli_real_escape_string($connection, trim($page_start));
                $per_page = mysqli_real_escape_string($connection, trim($per_page));

                if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'){
                    $query = "SELECT * FROM posts ORDER BY post_id DESC LIMIT {$page_start}, $per_page;";
                } else {
                    $query = "SELECT * FROM posts WHERE post_status = 'published' ORDER BY post_id DESC LIMIT {$page_start}, $per_page;";
                }

                $sellect_all_posts_query = mysqli_query($connection, $query);
                while($row = mysqli_fetch_assoc($sellect_all_posts_query)){
                    $post_id = $row['post_id'];
                    $post_title = $row['post_title'];
                    $post_author = $row['post_author'];
                    $post_date = $row['post_date'];
                    $post_image = $row['post_image'];
                    $post_content = substr($row['post_content'], 0, 100);
                    $post_status = $row['post_status'];
                    
                    $post_title_lc = strtolower($post_title);
                    
                    /* ciscenje od specijalnih karaktera pomocu REGEX-a */
                    $post_title_clean = preg_replace('/\W+/', '-', $post_title_lc);
                    /* ciscenje od karaktera -, ako je jedan ili vise njih poslednji karakter u stringu pomocu REGEX-a */
                    $post_title_clean = preg_replace('/-+$/', '', $post_title_clean);
                    
                    $post_author_lc = strtolower($post_author);
                    $post_author_clean = preg_replace('/\s/', '-', $post_author_lc);

                    //                if($post_status == "published"){

            ?>
            
            <!-- First Blog Post -->
            <h2>
                <a href="<?php echo $post_id . '-' . $post_title_clean; ?>"><?php echo $post_title; ?></a>
            </h2>
            <p class="lead">
                <?php echo _BY; ?> <a href="/cms/author-<?php echo $post_author_clean; ?>"><?php echo $post_author; ?></a>
            </p>
            <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date; ?></p>
            <hr>
            <a href="<?php echo $post_id . "-" . $post_title_clean; ?>">
                <img class="img-responsive" src="/cms/images/<?php echo imagePlaceholder($post_image); ?>" alt="alternative blog image">
            </a>
            <hr>
            <p><?php echo $post_content; ?></p>
            <a class="btn btn-primary" href="<?php echo $post_id . "-" . $post_title_clean; ?>"><?php echo _READ_MORE; ?> <span class="glyphicon glyphicon-chevron-right"></span></a>

            <hr>

            <?php } } ?>
        </div>

        <!-- Blog Sidebar Widgets Column -->
        <?php include_once "includes/sidebar.php"; ?>

    </div>
    <!-- /.row -->

    <hr>

    <ul class="pager">
        <?php
        for($i = 1; $i <= $count; $i++){
            if($i == $page ){
                echo "<li class='disabled'><a class='active_link' href='index.php?page={$i}'>{$i}</a></li>";
            } else {
                echo "<li><a href='page-{$i}'>{$i}</a></li>";
            }
        }
        ?>
    </ul>



    <?php
    include_once "includes/footer.php";
    ?>
