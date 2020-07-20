<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">


        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand" href="/cms">Home</a>
        </div>


        <?php language_selection(); ?>


        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">

                <?php
                $query = "SELECT * FROM categories";
                $select_all_categories_query = mysqli_query($connection, $query);

                while($row = mysqli_fetch_assoc($select_all_categories_query)){
                    $cat_title = $row['cat_title'];
                    $cat_id = $row['cat_id'];

                    // dodavanje Bootstrap klase 'active' u li elemente navigacije hedera
                    $category_class = "";
                    $login_class = "";
                    $registration_class = "";
                    $contact_class = "";
                    $pageName = basename($_SERVER['PHP_SELF']);

                    if(isset($_GET['category']) && $_GET['category'] == $cat_id){
                        $category_class = 'active';
                    } elseif($pageName == 'registration.php') {
                        $registration_class = 'active';
                    } elseif($pageName == 'login.php') {
                        $login_class = 'active';
                    } elseif($pageName == 'contact.php') {
                        $contact_class = "active";
                    }
                    // END dodavanje Bootstrap klase 'active' u li elemente navigacije hedera


                    $cat_title_lc = strtolower($cat_title);
                    $cat_title_plus = preg_replace('/(\+)/', '-plus', $cat_title_lc);
                    $cat_title_clean = preg_replace('/\W+/', '-', $cat_title_plus);

                    echo "<li class='$category_class'><a href='/cms/category/{$cat_id}-{$cat_title_clean}'>{$cat_title}</a></li>";
                    
                } // END while


                //                // EDIT button za post
                //                if(isset($_SESSION['user_role'])){
                //                    if(isset($_GET['p_id'])){
                //                        $the_post_id = $_GET['p_id'];
                //                        
                //                        // povezivanje posta sa korisnikom
                //                        
                //                        $query = "SELECT * FROM posts WHERE post_id = {$the_post_id} AND post_author = '".$_SESSION['username']."'";
                //                        $result = mysqli_query($connection, $query);
                //                        if(mysqli_num_rows($result) >=1){
                //                            echo "<li class='btn btn-primary'><a href='/cms/admin/posts.php?source=edit_post&p_id={$the_post_id}'>Edit Post</a></li>";
                //                        }
                //                    }
                //                }

                if(isset($_SESSION['user_role'])){
                    echo "<li><a href='/cms/includes/logout.php'><strong>Log Out</strong></a></li>";
                    echo "<li><a href='/cms/admin'><strong>Admin</strong></a></li>";
                } else {
                    echo "<li class='$login_class'><a href='/cms/login'><strong>"._LOGIN."</strong></a></li>";
                    echo "<li class='$registration_class'><a href='/cms/registration'><strong>". _REGISTRATION ."</strong></a></li>";
                }

                echo "<li class='$contact_class'><a href='/cms/contact'><strong>". _CONTACT . "</strong></a></li>";

                ?>


                <!--    Odabir jezika    -->
                <li>
                    <form method="POST" class="navbar-form navbar-right" id="language-form" action="">
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

        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->


    <!--    slanje submit iz forme za jezike    -->
    <script>
        function changeLangauge(){
            document.getElementById('language-form').submit();
        }
    </script>


</nav>