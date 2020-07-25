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