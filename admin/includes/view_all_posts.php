<?php
include "delete_modal.php";

if(isset($_POST['checkBoxArray'])){
    foreach($_POST['checkBoxArray'] as $postValueId){
        $bulk_options = ($_POST['bulk_options']);
        $postValueId = escape($postValueId);
        $bulk_options = escape($bulk_options);

        switch($bulk_options){
            case 'published':
                $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = '{$postValueId}';";
                $update_to_published_status = mysqli_query($connection, $query);
                confirm_query($update_to_published_status);
                break;

            case 'draft':
                $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = '{$postValueId}';";
                $update_to_draft_status = mysqli_query($connection, $query);
                confirm_query($update_to_draft_status);
                break;

            case 'delete':
                $query = "DELETE FROM posts WHERE post_id = '{$postValueId}';";
                $update_to_delete_status = mysqli_query($connection, $query);
                confirm_query($update_to_delete_status);
                break;

            case 'clone':
                $query = "SELECT * FROM posts WHERE post_id = '{$postValueId}';";
                $sellect_all_posts_query = mysqli_query($connection, $query)
                    or die("QUERY FAILED " . mysqli_error($connection));
                while($row = mysqli_fetch_assoc($sellect_all_posts_query)){
                    $post_id = $row['post_id'];
                    $post_author = $row['post_author'];
                    $post_title = $row['post_title'];
                    $post_category_id = $row['post_category_id'];
                    $post_status = $row['post_status'];
                    $post_image = $row['post_image'];
                    //                    $post_comment_count = $row['post_comment_count'];
                    $post_tags = $row['post_tags'];
                    $post_date = $row['post_date'];
                    $post_content = $row['post_content'];
                }

                if(empty($post_tags)){
                    $post_tags = "no tags";
                }

                $query = "INSERT INTO posts (post_category_id, post_title, post_author, post_date, post_image, post_content, post_tags, post_status )";
                $query .= "VALUES({$post_category_id}, '{$post_title}', '{$post_author}', now(), '{$post_image}', '{$post_content}', '{$post_tags}', '{$post_status}') ";
                $clone_query = mysqli_query($connection, $query)
                    or die("QUERY FAILED " . mysqli_error($connection));
                break;


            case 'reset_views':
                $query = "UPDATE posts SET post_views_count = 0 WHERE post_id = {$postValueId}";
                $reset_views_query = mysqli_query($connection, $query);
                confirm_query($reset_views_query);
                break;

        }
    }
}
?>

<form action="" method="POST">
    <table class="table table-bordered table-hover table-hover">

        <div id="bulkOptionsContainer" class="col-xs-4">
            <select class="form-control" name="bulk_options" id="">
                <option value="">Select Options</option>
                <option value="published">Publish</option>
                <option value="draft">Draft</option>
                <option value="delete">Delete</option>
                <option value="clone">Clone</option>
                <option value="reset_views">Reset Post Views</option>
            </select>
        </div>

        <div class="col-xs-4">
            <input type="submit" name="submit" class="btn btn-success" value="<?php echo _APPLAY; ?>">
            <a href="posts.php?source=add_post" class="btn btn-primary"><?php echo _ADD_NEW; ?></a>
        </div>

        <thead>
            <tr>
                <th><input id="selectAllBoxes" type="checkbox"></th>
                <th>ID</th>
                <th><?php echo _AUTHOR; ?></th>
                <th><?php echo _TITLE; ?></th>
                <th><?php echo _CATEGORY; ?></th>
                <th><?php echo _STATUS; ?></th>
                <th><?php echo _IMAGE; ?></th>
                <th><?php echo _TAGS; ?></th>
                <th><?php echo _COMMENTS; ?></th>
                <th><?php echo _VIEWS; ?></th>
                <th><?php echo _DATE; ?></th>
                <th><?php echo _EDIT_N; ?></th>
                <th><?php echo _DELETE_N; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php

            $query = "SELECT * FROM posts LEFT JOIN categories ";
            $query .= "ON posts.post_category_id = categories.cat_id ";
            $query .= "ORDER BY posts.post_id DESC;";
            $select_posts = mysqli_query ($connection, $query);

            while ($row = mysqli_fetch_assoc($select_posts)){
                $post_id = $row['post_id'];
                $post_author = $row['post_author'];
                $post_title = $row['post_title'];
                $post_category_id = $row['post_category_id'];
                $post_status = $row['post_status'];
                $post_image = $row['post_image'];
                //--------------- deo koda starog sistema za broj komentara ------------
                //                $post_comment_count = $row['post_comment_count'];
                //----------------------------------------------------------------------
                $post_tags = $row['post_tags'];
                $post_date = $row['post_date'];
                $post_views_count = $row['post_views_count'];
                $cat_id = $row['cat_id'];
                $cat_title = $row['cat_title'];

                echo "<tr>";
            ?>

            <td><input type="checkbox" class="checkBoxes" name="checkBoxArray[]" value="<?php echo $post_id; ?>"></td>

            <?php
                echo "<td>$post_id</td>";
                echo "<td>$post_author</td>";
                echo "<td><a href='../post.php?p_id=$post_id' target='_blank'>$post_title</a></td>";
                echo "<td>$cat_title</td>";
                if($post_status == "draft"){
                    echo "<td class='text-warning'>" . _DRAFT . "</td>";
                }
                if($post_status == "published"){
                    echo "<td class='text-success'>" . _PUBLISHED . "</td>";
                }
                echo "<td class='text-center'><img width='100' src='../images/$post_image' alt='image'></td>";
                echo "<td>$post_tags</td>";

                $query = "SELECT * FROM comments where comment_post_id = '{$post_id}';";
                $count_comments_query = mysqli_query($connection, $query);
                confirm_query($connection);
                $row = mysqli_fetch_array($count_comments_query);
                $comment_id = $row['comment_id'];
                $comments_count = mysqli_num_rows($count_comments_query);
                echo "<td><a href='post_comments.php?id={$post_id}' target='_blank'>{$comments_count}</a></td>";

                //---- Pozivanje starog sistema za broj komentara. Zamenjen je novim ----
                //
                //                echo "<td>$post_comment_count</td>";
                //
                //------------------------------------------------------------------------

                echo "<td>$post_views_count</td>";
                echo "<td>$post_date</td>";
                echo "<td class='text-center'><a class='btn btn-info' href='posts.php?source=edit_post&p_id={$post_id}'>" . _EDIT_V . "</a></td>";
                
                // added javascript code for delete confirmation
                //                echo "<td><a onClick=\" javascript: return confirm('Are you sure you want to delete?'); \"  href='posts.php?delete={$post_id}'>Delete</a></td>";

                //******* noviji metod potvrde brisanja - iskljucen zbog prelaska na slanje pdoataka POST metodom *******
                //                echo "<td><a rel='{$post_id}' data-posttitle='{$post_title}' href='javascript:void(0)' class='delete_link'>Delete</a></td>";
                //
                //********************************************************************************************************
                
                echo "<form method='POST'>";
                echo "<input type='hidden' name='post_id' value='{$post_id}'>";
                echo "<td  class='text-center'><input class='btn btn-danger' type='submit' name='delete' value='" . _DELETE_V . "'></td>";
                echo "</form>";

                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</form>
<?php
if(isset($_POST['delete'])){
    if(isset($_SESSION['user_role'])){
        if($_SESSION['user_role'] == 'admin'){
            $delete_post_id = $_POST['post_id'];
            $query = "DELETE FROM posts WHERE post_id = {$delete_post_id};";
            $delete_query = mysqli_query($connection, $query);
            confirm_query($delete_query);
            header("location:posts.php");
        }
    }
}

?>


<script>
// ***** Iskljucena potvrda brisanja zbog prelaska na POST metodu *****
//
//    $(document).ready(function(){
//        $(".delete_link").on('click', function(){
//            var id = $(this).attr("rel");
//            var delete_url = "posts.php?delete="+id;
//            $(".modal_delete_link").attr("href", delete_url);
//            $("#delete_modal").modal('show');
//
//            console.log(this);
//
//            //             added this code:
//            //            var get_text = $(this).attr("data-posttitle");  --- identican rezultat kao i linija koda ispod;
//            var get_text = $(this).data("posttitle");
//            $('#post_name_h4_insert').text(get_text);
//        });
//    });
//
//*************************************************************************************
</script>