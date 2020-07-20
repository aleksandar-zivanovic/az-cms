<?php
if(isset($_POST['create_user'])){

    $user_firstname = escape($_POST['user_firstname']);
    $user_lastname = escape($_POST['user_lastname']);
    $user_role = escape($_POST['user_role']);

//    $post_image = $_FILES['image']['name'];
//    $post_image_temp = $_FILES['image']['tmp_name'];


    $username = escape($_POST['username']);
    $user_email = escape($_POST['user_email']);
    $user_password = escape($_POST['user_password']);
    
    $user_password = password_hash($user_password, PASSWORD_BCRYPT, ['cost' => 12]);

//    move_uploaded_file($post_image_temp, "../images/$post_image");

    $query = "INSERT INTO users (user_firstname, user_lastname, user_role, username, user_email, user_password) ";
    $query .= "VALUES('{$user_firstname}', '{$user_lastname}', '{$user_role}', '{$username}', '{$user_email}', '{$user_password}')";
    $create_user_query = mysqli_query($connection, $query);
    confirm_query($create_user_query);
    
    if($create_user_query){
        echo "<h2 class='alert alert-success'>" . _USER_SUCCESS_CRE . " {$username}</h2>";
    }

}
?>

<form action="" method="post" enctype="multipart/form-data">

    <div class="form-group">
        <label for="author"><?php echo _FIRSTNAME; ?></label>
        <input type="text" class="form-control" name="user_firstname">
    </div>

    <div class="form-group">
        <label for="post_status"><?php echo _LASTNAME; ?></label>
        <input type="text" class="form-control" name="user_lastname">
    </div>

    <!-- User role drop down select button -->
    <div class="form-group">
        <label for="user_role"><?php echo _USER_ROLE; ?></label><br>
        <select name="user_role" id="user_role">
            <option value="subscriber"><?php echo _SELECT_OPTIONS; ?></option>
            <option value="admin"><?php echo _ADMIN; ?></option>
            <option value="subscriber"><?php echo _SUBSCRIBER; ?></option>
        </select>
    </div>
    <!-- STARI KOD - pokazuje polje u koji upisujemo ID kategorije
<div class="form-group">
<label for="post_category">Post Category ID</label>
<input type="text" class="form-control" name="post_category_id">
</div>
-->




    <!--
<div class="form-group">
<label for="file">Post Image</label>
<input type="file" name="image">
</div>
-->

    <div class="form-group">
        <label for="title"><?php echo _USERNAME; ?></label>
        <input type="text" class="form-control" name="username">
    </div>

    <div class="form-group">
        <label for="post_tags"><?php echo _EMAIL; ?></label>
        <input type="email" class="form-control" name="user_email">
    </div>

    <div class="form-group">
        <label for="post_tags"><?php echo _PASSWORD; ?></label>
        <input type="password" class="form-control" name="user_password">
    </div>

    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="create_user" value="<?php echo _ADM_ADD_USER; ?>">
    </div>


</form>