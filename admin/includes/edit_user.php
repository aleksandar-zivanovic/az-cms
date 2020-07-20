<?php

if(isset($_GET['edit_user'])){
    $the_user_id = escape($_GET['edit_user']);

    $query = "SELECT * FROM users WHERE user_id = $the_user_id ";
    $select_users_query = mysqli_query ($connection, $query)
        or die('QUERY FAILD ' . mysqli_error($connection));

    while ($row = mysqli_fetch_assoc($select_users_query)){
        $user_id = $row['user_id'];
        $username = $row['username'];
        $user_password = $row['user_password'];
        $user_firstname = $row['user_firstname'];
        $user_lastname = $row['user_lastname'];
        $user_email = $row['user_email'];
        $user_image = $row['user_image'];
        $user_role = $row['user_role'];
    }



    if(isset($_POST['edit_user'])){

        $user_firstname = escape($_POST['user_firstname']);
        $user_lastname = escape($_POST['user_lastname']);
        $user_role = escape($_POST['user_role']);
        // *** ostavio sam zbog mogucnosti da kasnije dodam avatar sliku za korisnike ***
        //    $post_image = $_FILES['image']['name'];
        //    $post_image_temp = $_FILES['image']['tmp_name'];
        $username = escape($_POST['username']);
        $user_email = escape($_POST['user_email']);
        $user_password = escape($_POST['user_password']);

        $user_password = password_hash($user_password, PASSWORD_BCRYPT, ['cost' => 12]);

        //    move_uploaded_file($post_image_temp, "../images/$post_image");


        // STARI KOD za $password koriscenjenjem crypt() funkcije, koji je zamenjen novim
        // 
        //    $query = "SELECT randSalt FROM users";
        //    $select_randsalt_query = mysqli_query($connection, $query)
        //        or die("QUERY FAILED " . mysqli_error($connection));
        //    $row = mysqli_fetch_array($select_randsalt_query);
        //    $salt = $row['randSalt'];
        //    $hashed_password = crypt($user_password, $salt);



        $query = "UPDATE users SET ";
        $query .= "user_firstname = '{$user_firstname}', ";
        $query .= "user_lastname = '{$user_lastname}', ";
        $query .= "user_role = '{$user_role}', ";
        $query .= "username = '{$username}', ";
        $query .= "user_email = '{$user_email}', ";
        $query .= "user_password = '{$user_password}' ";
        $query .= "WHERE user_id = $the_user_id";

        $edit_user_query = mysqli_query ($connection, $query);
        confirm_query($edit_user_query);

        echo "<h3>USER UPDATED! <a href='users.php'>View All Users</h3></a><br>";
    }
} else {
    header("location: index.php");
}
?>

<form action="" method="post" enctype="multipart/form-data">

    <div class="form-group">
        <label for="author"><?php echo _FIRSTNAME; ?></label>
        <input type="text" value="<?php echo $user_firstname; ?>" class="form-control" name="user_firstname">
    </div>

    <div class="form-group">
        <label for="post_status"><?php echo _LASTNAME; ?></label>
        <input type="text" value="<?php echo $user_lastname; ?>" class="form-control" name="user_lastname">
    </div>

    <!-- User role drop down select button -->
    <div class="form-group">
        <label for="user_role"><?php echo _USER_ROLE; ?></label><br>
        <select name="user_role" id="user_role">
            <!--            <option value="<?php echo $user_role; ?>" ><?php echo $user_role; ?></option>-->

            <?php
            //            if($user_role == 'admin'){
            //                echo "<option value='subscriber'>subscriber</option>";
            //            } else {
            //                echo "<option value='admin'>admin</option>";
            //            }
            ?>

            <!-- Umesto koda iz 83-90 linje (zakomentarisan kod iznad), moze se koristiti kod ispod.
Rezultat je indetican, ali je manje koda i preglednije, kao i lakse dodavanje novih korisnickih rola -->
            <option <?php if($user_role == "subscriber") echo 'selected' ; ?>  value='subscriber'><?php echo _SUBSCRIBER; ?></option>
            <option <?php if($user_role == "admin") echo 'selected' ?>  value='admin'><?php echo _ADMIN; ?></option>

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
        <input type="text" value="<?php echo $username; ?>" class="form-control" name="username">
    </div>

    <div class="form-group">
        <label for="post_tags"><?php echo _EMAIL; ?></label>
        <input type="email" value="<?php echo $user_email; ?>" class="form-control" name="user_email">
    </div>

    <div class="form-group">
        <label for="post_tags"><?php echo _PASSWORD; ?></label>
        <input autocomplete="off" type="password" class="form-control" name="user_password">
    </div>

    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="edit_user" value="<?php echo _EDIT_USER; ?>">
    </div>

</form>