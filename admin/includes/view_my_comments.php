<table class="table table-bordered table-hover table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th><?php echo _AUTHOR; ?></th>
            <th><?php echo _COMMENTS; ?></th>
            <th><?php echo _STATUS; ?></th>
            <th><?php echo _RESPONSE_TO; ?></th>
            <th><?php echo _DATE; ?></th>
            <th><?php echo _DELETE_N; ?></th>
        </tr>
    </thead>
    <tbody>
        <?php

        $query = "SELECT * FROM comments WHERE comment_author = '".$_SESSION['username']."' ORDER BY comment_id DESC";
        $select_comments = mysqli_query ($connection, $query);

        while ($row = mysqli_fetch_assoc($select_comments)){
            $comment_id = $row['comment_id'];
            $comment_post_id = $row['comment_post_id'];
            $comment_author = $row['comment_author'];
            $comment_content = $row['comment_content'];
            $comment_status = $row['comment_status'];
            $comment_date = $row['comment_date'];

            echo "<tr>";
            echo "<td>$comment_id</td>";
            echo "<td>$comment_author</td>";
            echo "<td>$comment_content</td>";
            
            // kolona za status komentara
            comment_status($comment_status);

            // kolona naziva posta na koji se komentar odnosi
            $query = "SELECT * FROM posts WHERE post_id = {$comment_post_id}";
            $select_post_id_query = mysqli_query($connection, $query)
                or die('POSTS QUERY FAILED ' . mysqli_error($connection));
            while($row = mysqli_fetch_assoc($select_post_id_query)){
                $post_title = $row['post_title'];
                $post_id = $row['post_id'];
                echo "<td><a href='../post.php?p_id={$post_id}' target='_blank'>{$post_title}</a></td>";
            }
            // END kolona naziva posta na koji se komentar odnosi

            echo "<td>$comment_date</td>";
            //            echo "<td><a href='posts.php'>Edit</a></td>";
            
            // kolona za brisanje komentara
            echo "<td class='text-center'><a class='btn btn-warning' href='mycomments.php?comm_del_by_user={$comment_id}'>" . _DELETE_V . "</a></td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<?php
// komentar deobija 'deleted' status od strane usera
if(isset($_GET['comm_del_by_user'])){
    $unapprove_comment = escape($_GET['comm_del_by_user']);

    $query = "UPDATE comments SET comment_status = 'deleted' WHERE comment_id = {$unapprove_comment};";
    $unapprove_comment_query = mysqli_query($connection, $query);

    confirm_query($unapprove_comment_query);

    header("location:mycomments.php");
}
?>