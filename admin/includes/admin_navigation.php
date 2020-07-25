<?php include_once 'admin_navigation_top.php'; ?>
    <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav side-nav">
           
            <?php if($_SESSION['user_role'] == "admin"){ ?>
            <li>
                <a href="index.php"><i class="fa fa-fw fa-dashboard"></i> <?php echo _ADM_DSHB; ?></a>
            </li>
            <?php } ?>
            
            <li>
                <a href="dashboard.php"><i class="fa fa-fw fa-dashboard"></i> <?php echo _ADM_MY_DSHB; ?></a>
            </li>


            <li>
                <a href="javascript:;" data-toggle="collapse" data-target="#posts_dropdown"><i class="fa fa-fw fa-arrows-v"></i> <?php echo _ADM_POSTS; ?> <i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="posts_dropdown" class="collapse">
                    <li>
                        <?php if($_SESSION['user_role'] == 'admin'){ ?>
                        <a href="posts.php"><i class="fa fa-fw fa-newspaper-o"></i> <?php echo _ADM_VW_ALL_POSTS; ?></a>
                        <?php } ?>

                        <a href="myposts.php"><i class="fa fa-fw fa-newspaper-o"></i> <?php echo _ADM_VW_UR_POSTS; ?></a>

                    </li>
                    <li>
                        <a href="posts.php?source=add_post"><i class="fa fa-fw fa-plus-circle"></i> <?php echo _ADM_ADD_POST; ?></a>
                    </li>
                </ul>
            </li>
            <!--      Categories      -->
            <?php if($_SESSION['user_role'] == 'admin'){ ?>
            <li>
                <a href="categories.php"><i class="fa fa-fw fa-th-large"></i> <?php echo _ADM_CATEGORIES; ?></a>
            </li>
            <?php } ?>

            <!--      Comments      -->


            <li>
                <?php if(is_admin($_SESSION['username'])){ ?>
                <a href="javascript:;" data-toggle="collapse" data-target="#comments"><i class="fa fa-fw fa-arrows-v"></i> <?php echo _ADM_COMMENTS; ?> <i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="comments" class="collapse">
                    <li>
                        <a href="mycomments.php"><i class="fa fa-fw fa-comment"></i> <?php echo _ADM_MY_COMMENTS; ?></a>
                    </li>

                    <li>
                        <a href="comments.php"><i class="fa fa-fw fa-comments"></i> <?php echo _ADM_COMMENTS; ?></a>
                    </li>
                </ul>
            </li>
            <?php } else { ?>
            <li>
                <a href="mycomments.php"><i class="fa fa-fw fa-comment"></i> <?php echo _ADM_MY_COMMENTS; ?></a>
            </li>
            <?php } ?>

            <!--      Users button in right-side menu      -->
            <?php if($_SESSION['user_role'] == "admin"){ ?>
            <li>
                <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-arrows-v"></i> <?php echo _ADM_USERS; ?> <i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="demo" class="collapse">
                    <li>
                        <a href="users.php"><i class="fa fa-fw fa-users"></i> <?php echo _ADM_ALL_USERS; ?></a>
                    </li>
                    <li>
                        <a href="users.php?source=add_user"><i class="fa fa-fw fa-user-md"></i> <?php echo _ADM_ADD_USER; ?></a>
                    </li>
                </ul>
            </li>
            <?php } ?>

            <li>
                <a href="profile.php"><i class="fa fa-fw fa-wrench"></i> <?php echo _ADM_PROFILE; ?></a>
            </li>
        </ul>
    </div>
    <!-- /.navbar-collapse -->

    <!--    slanje submit iz forme za jezike    -->
    <script>
        function changeLangauge(){
            document.getElementById('language-form').submit();
        }
    </script>

</nav>