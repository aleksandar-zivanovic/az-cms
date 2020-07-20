<?php

if(isset($_GET['p_id'])){
    $p_id = escape($_GET['p_id']);
}


$query = "SELECT * FROM posts WHERE post_id = {$p_id}";
$select_posts_by_id = mysqli_query ($connection, $query);

while ($row = mysqli_fetch_assoc($select_posts_by_id)){
    $post_id = $row['post_id'];
    $post_author = $row['post_author'];
    $post_title = $row['post_title'];
    $post_category_id = $row['post_category_id'];
    $post_status = $row['post_status'];
    $post_image = $row['post_image'];
    //    $post_comment_count = $row['post_comment_count'];
    $post_tags = $row['post_tags'];
    $post_date = $row['post_date'];
    $post_content = $row['post_content'];
}


if(isset($_POST['update_post'])){
    $post_title = escape($_POST['post_title']);
    $post_author = escape($_POST['post_author']);
    $post_category_id = escape($_POST['post_category']);
    $post_status = escape($_POST['post_status']);
    $post_image = $_FILES['image']['name'];
    $post_image_temp = $_FILES['image']['tmp_name'];
    $post_tags = escape($_POST['post_tags']);
    $post_content = escape($_POST['post_content']);

    move_uploaded_file($post_image_temp, "../images/$post_image");

    if(empty($post_image)){
        $query = "SELECT * FROM posts WHERE post_id = {$p_id} ";
        $select_image = mysqli_query($connection, $query)
            or die("QUERY FAILED!!! " . mysqli_error($connection));

        while($row = mysqli_fetch_assoc($select_image)){
            $post_image = $row['post_image'];
        }
    }


    $query = "UPDATE posts SET ";
    $query .= "post_title = '{$post_title}', ";
    $query .= "post_category_id = {$post_category_id}, ";
    $query .= "post_author = '{$post_author}', ";
    $query .= "post_date = now(), ";
    $query .= "post_status = '{$post_status}', ";
    $query .= "post_image = '{$post_image}', ";
    $query .= "post_tags = '{$post_tags}', ";
    $query .= "post_content = '{$post_content}' ";
    $query .= "WHERE post_id = $p_id";

    $update_query = mysqli_query ($connection, $query);
    confirm_query($update_query);

    echo "<p class='bg-success'>Post updated. <a href='../post.php?p_id=$p_id' target= '_blank'>View Post</a> or <a href='post.php' target= '_blank'>Edit More Posts</a></p>";
}


?>

<form action="" method="post" enctype="multipart/form-data">

    <div class="form-group">
        <label for="title">Post Title</label>
        <input type="text" class="form-control" name="post_title" value="<?php echo $post_title; ?>">
    </div>

    <div class="form-group">
        <label for="post_category">Post Category</label><br>

        <select name="post_category">

            <?php

            $query = "SELECT * FROM categories;";
            $select_categories = mysqli_query($connection,$query);

            confirm_query($select_categories);

            while($row = mysqli_fetch_assoc($select_categories)){
                $cat_id = $row['cat_id'];
                $cat_title = $row['cat_title'];
                echo "<option value='{$cat_id}'>{$cat_title}</option>";
            }

            ?>

        </select>
    </div>

    <div class="form-group">
        <label for="author">Post Author</label>
        <input type="text" class="form-control" name="post_author" value="<?php echo $post_author; ?>">
    </div>

    <div class="form-group">
        <label for="post_status">Post Status</label><br>
        <select name="post_status" id="">
           <?php if(is_admin($_SESSION['user_role'] == 'admin')){ ?>
            <option value="<?php echo $post_status ?>"><?php echo $post_status ?></option>
            <?php
    
        if($post_status == 'published'){
            echo "<option value='draft'>Draft</option>";
        } else {
            echo "<option value='published'>Published</option>";
        }

    } else {
        echo "<option value='draft'>Draft</option>";
    }
            ?>


        </select>
    </div>

    <!--
<div class="form-group">
<label for="post_status">Post Status</label>
<input type="text" class="form-control" name="post_status"  value="<?php // echo $post_status; ?>">
</div>
-->

    <div class="form-group">
        <label for="file">Post Image</label><br>
        <img width="100" src="../images/<?php echo $post_image; ?>" alt="slika">
        <input type="file" name="image">
    </div>

    <div class="form-group">
        <label for="post_tags">Post Tags</label>
        <input type="text" class="form-control" name="post_tags" value="<?php echo $post_tags; ?>">
    </div>

    <div class="form-group">
        <label for="post_content">Post Content</label>
        <textarea class="form-control" name="post_content" id="body" cols="30" rows="10"><?php echo $post_content; ?> </textarea>
    </div>

    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="update_post" value="Update Post">
    </div>


</form>