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

            if(isset($_GET['author'])){
                $author = $_GET['author'];
                
                // linija koda ispod je dodata da cisti znak - iz imena autora, koji dobija preko get metode
                // je ga dodajemo prilikom rewrite url adrese autora npr. autor je Miroslav Lajcek Junior,
                // a mi smo napravili url: http://localhost/cms/author-miroslav-lajcek-junior
                // umesto prvobitne adrese http://azivanovic.com/cms/author_posts.php?author=Miroslav Lajcek Junior
                $author = str_replace("-", ' ', $author);
                
                $author = mysqli_real_escape_string($connection, trim($author));
            }

            $query = "SELECT * FROM posts WHERE post_author = '{$author}';";
            $rows_query = mysqli_query($connection, $query);
            $count_posts = mysqli_num_rows($rows_query);

            echo "<h2 class='text-center'>" . $author ._OBJAVIO_MADE .$count_posts ._POSTS . ".</h2><br>";

            $query = "SELECT * FROM posts WHERE post_author = '{$author}' ORDER BY post_id DESC;";
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

                if($post_status == "published"){

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
            <a href="post.php?p_id=<?php echo $post_id; ?>">
                <img class="img-responsive" src="images/<?php echo imagePlaceholder($post_image); ?>" alt="alternative blog image">
            </a>
            <hr>
            <p><?php echo $post_content; ?></p>
            <a class="btn btn-primary" href="<?php echo $post_id . '-' . $post_title_clean; ?>"><?php echo _READ_MORE; ?> <span class="glyphicon glyphicon-chevron-right"></span></a>

            <hr>

            <?php } } ?>
        </div>

        <!-- Blog Sidebar Widgets Column -->
        <?php include_once "includes/sidebar.php"; ?>

    </div>
    <!-- /.row -->

    <hr>

    <?php
    include_once "includes/footer.php";
    ?>
