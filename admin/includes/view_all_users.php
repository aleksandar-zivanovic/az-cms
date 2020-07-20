<table class="table table-bordered table-hover table-hover">
    <thead>
        <tr>
            <th><?php echo _USER_ID; ?></th>
            <th><?php echo _USERNAME; ?></th>
            <th><?php echo _FIRSTNAME; ?></th>
            <th><?php echo _LASTNAME; ?></th>
            <th><?php echo _EMAIL; ?></th>
            <th><?php echo _USER_ROLE; ?></th>
            <th class="text-center" colspan="4"><?php echo _ADMINISTRATING_USER; ?></th>
        </tr>
    </thead>
    <tbody>
        <?php

        $query = "SELECT * FROM users";
        $select_users = mysqli_query ($connection, $query);

        while ($row = mysqli_fetch_assoc($select_users)){
            $user_id = $row['user_id'];
            $username = $row['username'];
            $user_password = $row['user_password'];
            $user_firstname = $row['user_firstname'];
            $user_lastname = $row['user_lastname'];
            $user_email = $row['user_email'];
            $user_image = $row['user_image'];
            $user_role = $row['user_role'];

            echo "<tr>";
            echo "<td>$user_id</td>";
            echo "<td>$username</td>";
            echo "<td>$user_firstname</td>";


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

            echo "<td>$user_lastname</td>";
            echo "<td>$user_email</td>";

            if($user_role == "admin"){
                echo "<td>"._ADMIN."</td>";
            }
            
            if($user_role == "subscriber"){
                echo "<td>"._SUBSCRIBER."</td>";
            }
            
            

            //            $query = "SELECT * FROM posts WHERE post_id = {$comment_post_id}";
            //            $select_post_id_query = mysqli_query($connection, $query)
            //                or die('POSTS QUERY FAILED ' . mysqli_error($connection));
            //            while($row = mysqli_fetch_assoc($select_post_id_query)){
            //                $post_title = $row['post_title'];
            //                $post_id = $row['post_id'];
            //                $post_comment_count = $row['post_comment_count'];
            //                echo "<td><a href='../post.php?p_id={$post_id}' target='_blank'>{$post_title}</a></td>";
            //            }

            echo "<td class='text-center'><a href='users.php?change_to_admin={$user_id}'>" . _ADMIN . "</a></td>";
            echo "<td class='text-center'><a href='users.php?change_to_subscriber={$user_id}'>" . _SUBSCRIBER . "</a></td>";
            //            echo "<td><a href='posts.php'>Edit</a></td>";
            echo "<td class='text-center'><a class='btn btn-info' href='users.php?source=edit_user&edit_user={$user_id}'>Edit</a></td>";
            echo "<td class='text-center'><a class='btn btn-danger' href='users.php?delete_user={$user_id}'>Delete</a></td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<?php

if(isset($_GET['change_to_admin'])){
    $the_user_id = escape($_GET['change_to_admin']);

    $query = "UPDATE users SET user_role = 'admin' WHERE user_id = $the_user_id ";
    $change_to_admin_query = mysqli_query($connection, $query);

    confirm_query($change_to_admin_query);

    header("location:users.php");
}



if(isset($_GET['change_to_subscriber'])){
    $the_user_id = escape($_GET['change_to_subscriber']);

    $query = "UPDATE users SET user_role = 'subscriber' WHERE user_id = $the_user_id ";
    $change_to_subscriber_query = mysqli_query($connection, $query);

    confirm_query($change_to_subscriber_query);

    header("location:users.php");
}




if(isset($_GET['delete_user'])){
    if(isset($_SESSION['user_role'])){
        if($_SESSION['user_role'] == 'admin'){
            $delete_user_id = escape($_GET['delete_user']);

            // upit za brisanje posta
            $query = "DELETE FROM users WHERE user_id = {$delete_user_id} ";
            $delete_user_query = mysqli_query($connection, $query);
            confirm_query($delete_user_query);

            header("location:users.php");
        }
    }
}
?>