<table class="table table-bordered table-hover table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th><?php echo _AUTHOR; ?></th>
            <th><?php echo _COMMENTS; ?></th>
            <th><?php echo _EMAIL; ?></th>
            <th><?php echo _STATUS; ?></th>
            <th><?php echo _RESPONSE_TO; ?></th>
            <th><?php echo _DATE; ?></th>
            <th><?php echo _APPROVE_N; ?></th>
            <th><?php echo _UNAPPROVE_N; ?></th>
            <th><?php echo _DELETE_N; ?></th>
        </tr>
    </thead>
    <tbody>
        <?php

        $query = "SELECT * FROM comments ORDER BY comment_id DESC";
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
            echo "<td>$comment_id</td>";
            echo "<td>$comment_author</td>";
            echo "<td>$comment_content</td>";


            //            $query = "SELECT * FROM categories WHERE cat_id = '{$post_category_id}'";
            //            $select_categories_id = mysqli_query($connection,$query);
            //
            //            confirm_query($select_categories_id);
            //
            //            while($row = mysqli_fetch_assoc($select_categories_id)){
            //                $cat_id = $row['cat_id'];
            //                $cat_title = $row['cat_title'];
            //                echo "<td>$cat_title</td>";
            //            }

            echo "<td>$comment_email</td>";
            
            // kolona za status komentara
            comment_status($comment_status);

            $query = "SELECT * FROM posts WHERE post_id = {$comment_post_id}";
            $select_post_id_query = mysqli_query($connection, $query)
                or die('POSTS QUERY FAILED ' . mysqli_error($connection));
            while($row = mysqli_fetch_assoc($select_post_id_query)){
                $post_title = $row['post_title'];
                $post_id = $row['post_id'];
//                $post_comment_count = $row['post_comment_count'];
                echo "<td><a href='../post.php?p_id={$post_id}' target='_blank'>{$post_title}</a></td>";
            }

            echo "<td>$comment_date</td>";
            echo "<td class='text-center'><a class='btn btn-success' href='comments.php?approve=$comment_id'>" . _APPROVE_V . "</a></td>";
            echo "<td class='text-center'><a class='btn btn-warning' href='comments.php?unapprove=$comment_id'>" . _UNAPPROVE_V . "</a></td>";
            //            echo "<td><a href='posts.php'>Edit</a></td>";
            echo "<td class='text-center'><a class='btn btn-danger' href='comments.php?delete_comment={$comment_id}'>" . _DELETE_V . "</a></td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<?php

if(isset($_GET['approve'])){
    $approve_comment = escape($_GET['approve']);

    $query = "UPDATE comments SET comment_status = 'approved' WHERE comment_id = $approve_comment;";
    $approve_comment_query = mysqli_query($connection, $query);

    confirm_query($approve_comment_query);

    header("location:comments.php");
}



if(isset($_GET['unapprove'])){
    $unapprove_comment = escape($_GET['unapprove']);

    $query = "UPDATE comments SET comment_status = 'unapproved' WHERE comment_id = $unapprove_comment;";
    $unapprove_comment_query = mysqli_query($connection, $query);

    confirm_query($unapprove_comment_query);

    header("location:comments.php");
}




if(isset($_GET['delete_comment'])){
    if(isset($_SESSION['user_role'])){
        if($_SESSION['user_role'] == 'admin'){
            $delete_comment_id = escape($_GET['delete_comment']);

            // upit za odredjivanje id-ja posta ciji se komentar brise
            $query1 = "SELECT * FROM comments WHERE comment_id = {$delete_comment_id};";
            $select_certain_comment = mysqli_query($connection, $query1)
                or die('POSTS QUERY FAILED ' . mysqli_error($connection));
            while($row = mysqli_fetch_assoc($select_certain_comment)){
                $deleting_post_id = $row['comment_post_id'];
            }

            // upit za brisanje posta
            $query = "DELETE FROM comments WHERE comment_id = {$delete_comment_id};";
            $delete_comment_query = mysqli_query($connection, $query);
            confirm_query($delete_comment_query);

//            //upit za azuriranje broja postova
//            $query2 = "UPDATE posts SET post_comment_count = post_comment_count - 1 ";
//            $query2 .= "WHERE post_id = {$deleting_post_id} ";
//            $decrease_comment_count = mysqli_query($connection, $query2)
//                or die('POSTS QUERY FAILED ' . mysqli_error($connection));

            header("location:comments.php");
        }
    }
}
?>