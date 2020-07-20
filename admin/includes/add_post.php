<?php
if(isset($_POST['create_post'])){

    $post_title = escape($_POST['title']);
    $post_author = escape($_SESSION['username']);
    $post_category_id = escape($_POST['post_category']);
    $post_status = escape($_POST['post_status']);

    $post_image = $_FILES['image']['name'];
    $post_image_temp = $_FILES['image']['tmp_name'];

    $post_tags = escape($_POST['post_tags']);
    $post_content = escape($_POST['post_content']);
    $post_date = date('d-m-y');

    move_uploaded_file($post_image_temp, "../images/$post_image");

    $query = "INSERT INTO posts (post_category_id, post_title, post_author, post_date, post_image, post_content, post_tags, post_status )";
    $query .= "VALUES({$post_category_id}, '{$post_title}', '{$post_author}', now(), '{$post_image}', '{$post_content}', '{$post_tags}', '{$post_status}') ";

    $create_post_query = mysqli_query($connection, $query);
    confirm_query($create_post_query);

    if($query_post_id = mysqli_insert_id($connection)){
        echo "<p class='bg-success'>" . _POST_CREATED_MSG1 . " <a href='../post.php?p_id=$query_post_id' target= '_blank'>" . _POST_CREATED_MSG2 . "</a> or <a href='add_post.php'>" . _ADD_NEW_POST . "</a></p>";
    }
}
?>

<form action="" method="post" enctype="multipart/form-data">

    <div class="form-group">
        <label for="title"><?php echo _POST_TITLE; ?></label>
        <input type="text" class="form-control" name="title">
    </div>

    <!-- Category drop down select button -->
    <div class="form-group">
        <label for="post_category"><?php echo _CATEGORY; ?></label><br>
        <select name="post_category">

            <?php

            $query = "SELECT * FROM categories";
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
    <!-- STARI KOD - pokazuje polje u koji upisujemo ID kategorije
<div class="form-group">
<label for="post_category">Post Category ID</label>
<input type="text" class="form-control" name="post_category_id">
</div>
-->


    <!-- STARI KOD - obrisan zbog dodavanja drop-down menija
<div class="form-group">
<label for="author">Post Author</label>
<input type="text" class="form-control" name="author">
</div>
-->

    <!--
<div class="form-group">
<label for="users">Users</label><br>
<select name="users">
<?php
//            $query = "SELECT * FROM users";
//            $select_user = mysqli_query($connection,$query);
//            confirm_query($select_user);
//
//            while($row = mysqli_fetch_assoc($select_user)){
//                $user_id = $row['user_id'];
//                $username = $row['username'];
//                echo "<option value='{$user_id}'>{$username}</option>";
//            }
?>

</select>
</div>
-->

    <div class="form-group">
        <label for="post_status"><?php echo _POST_STATUS; ?></label><br>
        <select name="post_status" id="">
            <?php if(is_admin($_SESSION['username'])){ ?>
            <option value="draft"><?php echo _SELECT_OPTIONS; ?></option>
            <option value="published"><?php echo _PUBLISHED; ?></option>
            <?php } ?>
            <option value="draft"><?php echo _DRAFT; ?></option>
        </select>
    </div>

    <div class="form-group">
        <label for="file"><?php echo _IMAGE; ?></label>
        <input type="file" name="image">
    </div>

    <div class="form-group">
        <label for="post_tags"><?php echo _TAGS; ?></label>
        <input type="text" class="form-control" name="post_tags">
    </div>

    <div class="form-group">
        <label for="post_content"><?php echo _POST_CONTENT; ?></label>
        <textarea class="form-control" name="post_content" id="body" cols="30" rows="10"></textarea>
    </div>

    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="create_post" value="<?php echo _PUBLISH_POST; ?>">
    </div>


</form>