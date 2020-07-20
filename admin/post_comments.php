<?php include_once "includes/admin_header.php"; ?>

<?php
if(isset($_POST['checkBoxArray'])){
    foreach($_POST['checkBoxArray'] as $commentValueId){
        $bulk_options = ($_POST['bulk_options']);
        
        $commentValueId = escape($commentValueId);
        $bulk_options = escape($bulk_options);
        
        switch($bulk_options){
            case 'approved':
                $query = "UPDATE comments SET comment_status = '{$bulk_options}' WHERE comment_id = '{$commentValueId}';";
                $approve_comment = mysqli_query($connection, $query);
                confirm_query($approve_comment);
                break;

            case 'unapproved':
                $query = "UPDATE comments SET comment_status = 'unapproved' WHERE comment_id = '{$commentValueId}';";
                $unapprove_comment = mysqli_query($connection, $query);
                confirm_query($unapprove_comment);
                break;

            case 'delete':
                $query = "DELETE FROM comments WHERE comment_id = '{$commentValueId}';";
                $delete_comment = mysqli_query($connection, $query);
                confirm_query($delete_comment);
                break;

            case 'clone':
                $query = "SELECT * FROM comments WHERE comment_id = '{$commentValueId}';";
                $sellect_all_comments_query = mysqli_query($connection, $query)
                    or die("QUERY FAILED " . mysqli_error($connection));
                while($row = mysqli_fetch_assoc($sellect_all_comments_query)){
                    $comment_id = $row['comment_id'];
                    $comment_post_id = $row['comment_post_id'];
                    $comment_author = $row['comment_author'];
                    $comment_email = $row['comment_email'];
                    $comment_content = $row['comment_content'];
                }

                $query = "INSERT INTO comments (comment_post_id, comment_author, comment_date, comment_email, comment_content, comment_status)";
                $query .= "VALUES('{$comment_post_id}', '{$comment_author}', now(), '{$comment_email}', '{$comment_content}', 'unapproved') ";
                $clone_query = mysqli_query($connection, $query)
                    or die("QUERY FAILED " . mysqli_error($connection));
                break;

        }
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
                        Welcome to Comments
                        <small><?php echo $_SESSION['username']; ?></small>
                    </h1>


                    <form action="" method="POST">
                        <div id="bulkOptionsContainer" class="col-xs-4">
                            <select class="form-control" name="bulk_options" id="">
                                <option value="">Select Options</option>
                                <option value="approved">Approve</option>
                                <option value="unapproved">Unapprove</option>
                                <option value="delete">Delete</option>
                                <option value="clone">Clone</option>
                            </select>
                        </div>

                        <div class="col-xs-4">
                            <input type="submit" name="submit" class="btn btn-success" value="Applay">
                        </div>

                        <table class="table table-bordered table-hover table-hover">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="selectAllBoxes"></th>
                                    <th>ID</th>
                                    <th>Author</th>
                                    <th>Comment</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>In Response to</th>
                                    <th>Date</th>
                                    <th>Approve</th>
                                    <th>Unapprove</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $the_post_id = escape($_GET['id']);
                                $query = "SELECT * FROM comments WHERE comment_post_id = {$the_post_id} ORDER BY comment_id DESC;";
                                $select_comments = mysqli_query ($connection, $query);

                                while ($row = mysqli_fetch_assoc($select_comments)){
                                    $comment_id = $row['comment_id'];
                                    $comment_post_id = $row['comment_post_id'];
                                    $comment_author = $row['comment_author'];
                                    $comment_content = $row['comment_content'];
                                    $comment_email = $row['comment_email'];
                                    $comment_status = $row['comment_status'];
                                    $comment_date = $row['comment_date'];

                                    echo "<tr>";
                                ?>
                                <td><input type="checkbox" class="checkBoxes" name="checkBoxArray[]" value="<?php echo $comment_id; ?>"></td>
                                <?php
                                    echo "<td>$comment_id</td>";
                                    echo "<td>$comment_author</td>";
                                    echo "<td>$comment_content</td>";
                                    echo "<td>$comment_email</td>";
                                    echo "<td>$comment_status</td>";

                                    // upit radi dobijanja imena i id-ja posta na koji se komentar odnosi
                                    $query = "SELECT * FROM posts WHERE post_id = {$comment_post_id}";
                                    $select_post_id_query = mysqli_query($connection, $query)
                                        or die('POSTS QUERY FAILED ' . mysqli_error($connection));
                                    while($row = mysqli_fetch_assoc($select_post_id_query)){
                                        $post_title = $row['post_title'];
                                        $post_id = $row['post_id'];
                                        echo "<td><a href='../post.php?p_id={$post_id}' target='_blank'>{$post_title}</a></td>";
                                    }

                                    echo "<td>$comment_date</td>";
                                    echo "<td><a href='post_comments.php?approve=$comment_id&id=". $_GET['id'] ."'>Approve</a></td>";
                                    echo "<td><a href='post_comments.php?unapprove=$comment_id&id=". $_GET['id'] ."'>Unapprove</a></td>";
                                    //            echo "<td><a href='posts.php'>Edit</a></td>";
                                    echo "<td><a href='post_comments.php?delete_comment={$comment_id}&id=". $_GET['id'] ."'>Delete</a></td>";
                                    echo "</tr>";
                                }
                                ?>

                            </tbody>
                        </table>
                    </form>

                    <?php

                    if(isset($_GET['approve'])){
                        $approve_comment = escape($_GET['approve']);

                        $query = "UPDATE comments SET comment_status = 'approved' WHERE comment_id = $approve_comment;";
                        $approve_comment_query = mysqli_query($connection, $query);

                        confirm_query($approve_comment_query);

                        header("location:post_comments.php?id=".$_GET['id']."");
                    }



                    if(isset($_GET['unapprove'])){
                        $unapprove_comment = escape($_GET['unapprove']);

                        $query = "UPDATE comments SET comment_status = 'unapproved' WHERE comment_id = {$unapprove_comment};";
                        $unapprove_comment_query = mysqli_query($connection, $query);

                        confirm_query($unapprove_comment_query);

                        header("location:post_comments.php?id=".$_GET['id']."");
                    }



                    if(isset($_GET['delete_comment'])){
                        $delete_comment_id = escape($_GET['delete_comment']);

                        // upit za brisanje posta
                        $query = "DELETE FROM comments WHERE comment_id = {$delete_comment_id} ";
                        $delete_comment_query = mysqli_query($connection, $query);
                        confirm_query($delete_comment_query);

                        header("location:post_comments.php?id=".$_GET['id']."");
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