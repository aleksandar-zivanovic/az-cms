<form action="" method="POST">
    <div class="form-group">
        <label for="cat_title"><?php echo _EDIT_CAT; ?></label>

        <?php 

        if(isset($_GET['edit'])){
            $cat_id = escape($_GET['edit']);

            $query = "SELECT * FROM categories WHERE cat_id = '{$cat_id}'";
            $select_categories_id = mysqli_query($connection,$query);

            while($row = mysqli_fetch_assoc($select_categories_id)){
                $cat_id = $row['cat_id'];
                $cat_title = $row['cat_title'];
        ?>

        <input value="<?php if(isset($cat_title)){ echo $cat_title; } ?>" type="text" class="form-control" name="cat_title">

        <?php
            }
        }
        ?>


        <?php

        // UPDATE QUERY
        if(isset($_POST['update_category'])){
            $the_cat_title = escape($_POST['cat_title']);
            
            $stmt = mysqli_prepare($connection, "UPDATE categories SET cat_title = ? WHERE cat_id = ?");
            mysqli_stmt_bind_param($stmt, 'si', $the_cat_title, $cat_id);
            mysqli_stmt_execute($stmt);
            confirm_query($stmt);
            
            mysqli_stmt_close($stmt);
            
            
//            $query = "UPDATE categories SET cat_title = '$the_cat_title' WHERE cat_id = $cat_id";
//            $update_query = mysqli_query($connection, $query);
            
            
            header("location: categories.php");
        }



        ?>


    </div>
    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="update_category" value="<?php echo _UPDATE_CAT; ?>">
    </div>

</form>