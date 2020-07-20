<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php">CMS Admin</a>
    </div>
    <!-- Top Menu Items -->
    <ul class="nav navbar-right top-nav">


        <!-- STARI KOD za prikazivanje broja loginovanih korisnika u admin panelu (nije live) -->
        <!--        <li><a href="">Users online: <?php // echo users_online(); ?></a></li>-->

        <!--    Pozivanje funkcije za LIVE broj ljudi u admin panelu (javascript kod)    -->
        <script>
            setInterval(function(){
                loadUsersOnline();
            }, 5000);
        </script>

        <?php
        language_selection_admin();
        ?>


        <li><a href=""><?php echo _ADM_USR_ONLINE; ?><span class="usersonline"></span></a></li>

        <li><a href="../index.php" target="_blank"><?php echo _ADM_HOMEPAGE; ?></a></li>

        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i>
                <?php 
                if(isset($_SESSION['username'])){
                    echo $_SESSION['username'];
                }
                ?>
                <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li>
                    <a href="profile.php"><i class="fa fa-fw fa-user"></i> <?php echo _ADM_PROFILE; ?></a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="../includes/logout.php"><i class="fa fa-fw fa-power-off"></i> <?php echo _LOGOUT; ?></a>
                </li>
            </ul>
        </li>

        <!--    Odabir jezika    -->
        <li>
            <form method="POST" class="navbar-form" id="language-form" action="">
                <div class="form-group">
                    <select name="lang" class="form-control" onchange="changeLangauge()">
                        <option value="en" id="" <?php if(isset($_SESSION['lang']) && $_SESSION['lang'] == "en") { echo "selected"; }; ?> >USA</option>
                        <option value="sr" id="" <?php if(isset($_SESSION['lang']) && $_SESSION['lang'] == "sr") { echo "selected"; }; ?> >Srpski</option>
                    </select>
                </div>
            </form>
        </li>
        <!--    Odabir jezika END    -->

    </ul>
    <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav side-nav">
            <li>
                <a href="dashboard.php"><i class="fa fa-fw fa-dashboard"></i> <?php echo _ADM_MY_DSHB; ?></a>
            </li>

            <?php if($_SESSION['user_role'] == "admin"){ ?>
            <li>
                <a href="index.php"><i class="fa fa-fw fa-dashboard"></i> <?php echo _ADM_DSHB; ?></a>
            </li>
            <?php } ?>


            <li>
                <a href="javascript:;" data-toggle="collapse" data-target="#posts_dropdown"><i class="fa fa-fw fa-arrows-v"></i> <?php echo _ADM_POSTS; ?> <i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="posts_dropdown" class="collapse">
                    <li>
                        <?php if($_SESSION['user_role'] == 'admin'){ ?>
                        <a href="posts.php"><?php echo _ADM_VW_ALL_POSTS; ?></a>
                        <?php } ?>

                        <a href="myposts.php"><?php echo _ADM_VW_UR_POSTS; ?></a>

                    </li>
                    <li>
                        <a href="posts.php?source=add_post"><?php echo _ADM_ADD_POST; ?></a>
                    </li>
                </ul>
            </li>
            <!--      Categories      -->
            <?php if($_SESSION['user_role'] == 'admin'){ ?>
            <li>
                <a href="categories.php"><i class="fa fa-fw fa-wrench"></i> <?php echo _ADM_CATEGORIES; ?></a>
            </li>
            <?php } ?>

            <!--      Comments      -->


            <li>
                <?php if(is_admin($_SESSION['username'])){ ?>
                <a href="javascript:;" data-toggle="collapse" data-target="#comments"><i class="fa fa-fw fa-arrows-v"></i> <?php echo _ADM_COMMENTS; ?> <i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="comments" class="collapse">
                    <li>
                        <a href="mycomments.php"><i class="fa fa-fw fa-file"></i> <?php echo _ADM_MY_COMMENTS; ?></a>
                    </li>

                    <li>
                        <a href="comments.php"><i class="fa fa-fw fa-file"></i> <?php echo _ADM_COMMENTS; ?></a>
                    </li>
                </ul>
            </li>
            <?php } else { ?>
            <li>
                <a href="mycomments.php"><i class="fa fa-fw fa-file"></i> <?php echo _ADM_MY_COMMENTS; ?></a>
            </li>
            <?php } ?>

            <!--      Users button in right-side menu      -->
            <?php if($_SESSION['user_role'] == "admin"){ ?>
            <li>
                <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-arrows-v"></i> <?php echo _ADM_USERS; ?> <i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="demo" class="collapse">
                    <li>
                        <a href="users.php"><?php echo _ADM_ALL_USERS; ?></a>
                    </li>
                    <li>
                        <a href="users.php?source=add_user"><?php echo _ADM_ADD_USER; ?></a>
                    </li>
                </ul>
            </li>
            <?php } ?>

            <li>
                <a href="profile.php"><i class="fa fa-fw fa-dashboard"></i> <?php echo _ADM_PROFILE; ?></a>
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