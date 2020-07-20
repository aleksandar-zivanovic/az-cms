<?php include_once "includes/db.php"; ?>
<?php include_once "includes/header.php"; ?>
<?php include_once "admin/functions.php"; ?>

<!-- Navigation -->
<?php include_once "includes/navigation.php"; ?>


<?php
if(isset($_POST['ajax_liked'])){
    $postID = $_POST['ajax_post_id'];
    $userID = $_POST['ajax_user_id'];
    // query() je custom funkcija iz functions.php
    $postResult = query("SELECT * FROM posts WHERE post_id={$postID}");
    $posts = mysqli_fetch_array($postResult);
    $likes = $posts['post_likes'];

    // updating database posts table
    mysqli_query($connection, "UPDATE posts SET post_likes = {$likes}+1 WHERE post_id = {$postID}");

    // updating database likes table
    mysqli_query($connection, "INSERT INTO likes (user_id, post_id) VALUES ({$userID},{$postID})");
    exit();
}


if(isset($_POST['ajax_unliked'])){
    $postID = $_POST['ajax_post_id'];
    $userID = $_POST['ajax_user_id'];

    // query() je custom funkcija iz functions.php
    $postResult = query("SELECT * FROM posts WHERE post_id={$postID}");
    $posts = mysqli_fetch_array($postResult);
    $likes = $posts['post_likes'];

    // updating database posts table
    mysqli_query($connection, "UPDATE posts SET post_likes = {$likes}-1 WHERE post_id = {$postID}");

    // updating database likes table - brisanje celog reda iz likes tabele, koji se odnosi na ponisteni like
    mysqli_query($connection, "DELETE FROM likes WHERE post_id = {$postID} AND user_id = {$userID}");
    exit();
}

?>


<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">

            <?php
            if(isset($_GET['p_id'])){
                $the_post_id = $_GET['p_id'];
                $the_post_id = mysqli_real_escape_string($connection, trim($the_post_id));

                $query = "UPDATE posts SET post_views_count = post_views_count+1 WHERE post_id = {$the_post_id};";
                $count_views_query = mysqli_query($connection, $query)
                    or die("QUERY FAILED " . mysqli_error($connection));

                if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'){
                    $query = "SELECT * FROM posts where post_id = {$the_post_id};";
                } else {
                    $query = "SELECT * FROM posts where post_id = {$the_post_id} AND post_status = 'published';";
                }

                $sellect_all_posts_query = mysqli_query($connection, $query);

                if(mysqli_num_rows($sellect_all_posts_query) < 1){
                    echo "<h1 class='text-center'>No posts available</h1>";
                } else {

                    while($row = mysqli_fetch_assoc($sellect_all_posts_query)){
                        $post_id = $row['post_id'];
                        $post_title = $row['post_title'];
                        $post_author = $row['post_author'];
                        $post_date = $row['post_date'];
                        $post_image = $row['post_image'];
                        $post_content = $row['post_content'];


                        $post_author_lc = strtolower($post_author);
                        $post_author_clean = preg_replace('/\s/', '-', $post_author_lc);
            ?>

            <!-- First Blog Post -->
            <h2>
                <?php echo $post_title; 
                
                // EDIT button za post
                        echo "<span class='pull-right'>";
                if(isset($_SESSION['user_role'])){
                    if(isset($_GET['p_id'])){
                        $the_post_id = $_GET['p_id'];
                        
                        // povezivanje posta sa korisnikom
                        
                        $query = "SELECT * FROM posts WHERE post_id = {$the_post_id} AND post_author = '".$_SESSION['username']."'";
                        $result = mysqli_query($connection, $query);
                        if(mysqli_num_rows($result) >=1){
                            echo "<a class='btn btn-primary' href='/cms/admin/posts.php?source=edit_post&p_id={$the_post_id}'>Edit Post</a>";
                        }
                    }
                }
                        
                        echo "</span>";
                ?>
            </h2>
            <p class="lead">
                by <a href="/cms/author-<?php echo $post_author_clean; ?>"><?php echo $post_author; ?></a>
            </p>
            <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date; ?></p>
            <hr>
            <img class="img-responsive" src="/cms/images/<?php echo imagePlaceholder($post_image); ?>" alt="">
            <hr>
            <p><?php echo $post_content; ?></p>

            <hr>

            <?php
                        //                        mysqli_free_result($sellect_all_posts_query);
                        //                        mysqli_free_result($count_views_query);



            ?>


            <!--      ***      like & unlike fetaure      ***      -->

            <?php if(loggedInUserId()): ?>

            <!-- prikaz za LOGINOVANE korisnike -->
            <div>
                <p class="pull-right">
                    <a
                       class="<?php echo userLikedThisPost($the_post_id) ? 'unlike' : 'like'; ?>" 
                       href=""
                       data-toggle="tooltip"
                       data-placement="top"
                       title="<?php echo userLikedThisPost($the_post_id) ? 'You already liked it' : 'Want to like it?'; ?>">
                        <span class="glyphicon glyphicon-thumbs-<?php echo userLikedThisPost($the_post_id) ? 'down' : 'up'; ?>"></span> 
                        <?php echo userLikedThisPost($the_post_id) ? ' Unlike' : ' Like'; ?>
                    </a>
                    | Likes: <?php echo getPostLikes($the_post_id); ?>
                </p>
            </div>
            <div class="clearfix"></div>

            <?php else: ?>

            <!-- prikaz za neloginovane korisnike -->
            <div>
                <p class="pull-right">You need to <a href="/cms/login"
                                                     data-toggle="tooltip"
                                                     data-placement="top"
                                                     title="Login to like">login</a> to like. | Likes: <?php echo getPostLikes($the_post_id); ?>
                </p>
            </div>
            <div class="clearfix"></div>

            <?php endif; ?>

            <!--      ***      end of like & unlike fetaure      ***      -->


            <?php
                    }    // kraj WHILE petlje
            ?>


            <!-- Blog Comments -->

            <?php

                    //            DODAVANJE KOMENTARA
                    if(isset($_POST['create_comment'])){
                        $the_post_id = $_GET['p_id'];
                        $comment_author = $_POST['comment_author'];
                        $comment_email = $_POST['comment_email'];
                        $comment_content = $_POST['comment_content'];

                        $the_post_id = mysqli_real_escape_string($connection, trim($the_post_id));
                        $comment_author = mysqli_real_escape_string($connection, trim($comment_author));
                        $comment_email = mysqli_real_escape_string($connection, trim($comment_email));
                        $comment_content = mysqli_real_escape_string($connection, trim($comment_content));

                        if(!empty($comment_author) && !empty($comment_email) && !empty($comment_email)){

                            $query = "INSERT INTO comments (comment_post_id, comment_author, comment_email, comment_content, comment_status, comment_date) ";
                            $query .= "VALUES({$the_post_id}, '{$comment_author}', '{$comment_email}', '{$comment_content}', 'unapproved', now())";

                            $create_comment_query = mysqli_query($connection, $query)
                                or die("QUERY FAILED! " . mysqli_error($connection));

                            // brojcanik za broj komentara na odredjeno postu - zamenjen novim -----------
                            //
                            //                    $query = "UPDATE posts SET post_comment_count = post_comment_count + 1 ";
                            //                    $query .= "WHERE post_id =  {$the_post_id}";
                            //                    $update_comment_count = mysqli_query($connection, $query)
                            //                        or die("QUERY FAILED! " . mysqli_error($connection));
                            //------------------------------------------------------------------------------

                        } else {
                            echo "<script>alert('Fileds cannot be empty');</script>";
                        }

                    }

            ?>

            <!-- Comments Form -->
            <div class="well">
                <h4>Leave a Comment:</h4>
                <form role="form" method="POST" action="">

                    <div class="form-group">
                        <label for="Author">Author</label>
                        <input class="form-control" name="comment_author" type="text" name="comment_author">
                    </div>

                    <div class="form-group">
                        <label for="Email">Email</label>
                        <input class="form-control" name="comment_email" type="email" name="comment_email">
                    </div>

                    <div class="form-group">
                        <label for="comment">Your Comment</label>
                        <textarea class="form-control" name="comment_content" rows="3"></textarea>
                    </div>

                    <button type="submit" name="create_comment"class="btn btn-primary">Submit</button>

                </form>
            </div>

            <hr>


            <!-- Posted Comments -->

            <?php

                    $query = "SELECT * FROM comments WHERE comment_post_id = {$the_post_id} ";
                    $query .= "AND comment_status = 'approved' ";
                    $query .= "ORDER BY comment_id DESC";

                    $comment_query_post = mysqli_query($connection, $query)
                        or die("QUERY FAILED! " . mysqli_error($connection));

                    while($row = mysqli_fetch_assoc($comment_query_post)){
                        $comment_author = $row['comment_author'];
                        $comment_date = $row['comment_date'];
                        $comment_content = $row['comment_content'];

                        //                echo "<div class='media'>";
                        //                echo "<a class='pull-left' href='#'>";
                        //                echo "<img class='media-object' src='http://placehold.it/64x64' alt=''>";
                        //                    echo " </a>";
                        //                    echo "<div class='media-body'>";
                        //                    echo "<h4 class='media-heading'>{$comment_author} ";
                        //                    echo "<small>{$comment_date}</small>";
                        //                    echo "</h4>";
                        //                    echo "{$comment_content} </div></div>";
            ?>

            <!-- Comment -->
            <div class="media">
                <a class="pull-left" href="#">
                    <img class="media-object" src="http://placehold.it/64x64" alt="">
                </a>
                <div class="media-body">
                    <h4 class="media-heading"><?php echo $comment_author; ?>
                        <small> <?php echo $comment_date; ?></small>
                    </h4>
                    <?php echo $comment_content; ?>
                </div>
            </div>

            <?php

                    }
                }
            } else {
                header("location:/cms");
            }

            ?>

        </div>

        <!-- Blog Sidebar Widgets Column -->
        <?php include_once "includes/sidebar.php"; ?>

    </div>
    <!-- /.row -->

    <hr>

    <?php include_once "includes/footer.php"; ?>


    <script>

        var post_id = <?php echo $the_post_id; ?>;
        var user_id = <?php echo loggedInUserId(); ?>;

        $(document).ready(function(){

            // tooltip za linkove
            $('[data-toggle="tooltip"]').tooltip();

            // like post
            $('.like').click(function(){
                $.ajax({
                    url: "/cms/post.php?p_id="+post_id,
                    type: "post",
                    data: {
                        ajax_liked: 1,
                        ajax_post_id: post_id,
                        ajax_user_id: user_id
                    }
                });
            });

            // unlike post
            $('.unlike').click(function(){
                $.ajax({
                    url: "/cms/post.php?p_id="+post_id,
                    type: "post",
                    data: {
                        ajax_unliked: 1,
                        ajax_post_id: post_id,
                        ajax_user_id: user_id
                    }
                });
            });

        });
    </script>