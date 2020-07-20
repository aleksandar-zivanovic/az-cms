<?php include_once "includes/admin_header.php";?>


<div id="wrapper">

    <!-- Navigation -->
    <?php include_once "includes/admin_navigation.php"; ?>

    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        <?php echo _WLC_MSG_USER_DASHBOARD; ?>
                        <small><?php echo $_SESSION['username']; ?></small>
                    </h1>

                </div>
            </div>
            <!-- /.row -->


            <?php
            // user's post count
            $theUserPostsCount = checkStatus("posts", "post_author", $_SESSION['username']);
            $theUserActivePostsCount = checkStatusForTheUser("posts", "post_author ", "post_status", "published");
            $theUserDraftPostsCount = checkStatusForTheUser("posts", "post_author ", "post_status", "draft");
            // user's comment count
            $theUserCommentsCount = checkStatus("comments", "comment_author", $_SESSION['username']);
            $theUserApprovedCommentsCount = checkStatusForTheUser("comments", "comment_author ", "comment_status", "approved");
            $theUserUnapprovedCommentsCount = checkStatusForTheUser("comments", "comment_author ", "comment_status", "unapproved");

            // counting number of DIFFERENT categories the user posted in
            $rezultat = query("SELECT DISTINCT post_category_id FROM posts WHERE post_author = '".$_SESSION['username']."'");
            $theUserCategoriesCount = mysqli_num_rows($rezultat);

            ?>

            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-file-text fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class='huge'><?php echo $theUserPostsCount; ?></div>
                                    <div><?php echo _ADM_POSTS; ?></div>
                                </div>
                            </div>
                        </div>
                        <a href="myposts.php">
                            <div class="panel-footer">
                                <span class="pull-left"><?php echo _ADM_VW_DETAILS; ?></span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-comments fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class='huge'><?php echo $theUserCommentsCount; ?></div>
                                    <div><?php echo _ADM_COMMENTS; ?></div>
                                </div>
                            </div>
                        </div>
                        <a href="mycomments.php">
                            <div class="panel-footer">
                                <span class="pull-left"><?php echo _ADM_VW_DETAILS; ?></span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-list fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class='huge'><?php echo $theUserCategoriesCount; ?></div>
                                    <div><?php echo _ADM_CATEGORIES; ?></div>
                                </div>
                            </div>
                        </div>
                            <div class="panel-footer">
                                <span class="pull-left"><?php echo _ADM_NUM_OF_CAT_POSTED; ?></span>
                                <div class="clearfix"></div>
                            </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->



            <div class="row">
                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                <script type="text/javascript">
                    google.charts.load('current', {'packages':['bar']});
                    google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {
                        var data = google.visualization.arrayToDataTable([
                            ['<?php echo _ADM_GOOGLE_DRWCHRT_DATA; ?>', '<?php echo _ADM_GOOGLE_DRWCHRT_COUNT; ?>'],

                            <?php
                            $element_text = [_ADM_ALL_POSTS, _ADM_ACT_POSTS, _ADM_DRAFT_POSTS, _ADM_COMMENTS, _ADM_APPROVED_COMENTS, _ADM_PENDING_COMENTS, _ADM_CATEGORIES];
                            $element_count = [$theUserPostsCount, $theUserActivePostsCount, $theUserDraftPostsCount, $theUserCommentsCount, $theUserApprovedCommentsCount, $theUserUnapprovedCommentsCount, $theUserCategoriesCount];

                            for($i = 0; $i < 7; $i++){
                                echo "['{$element_text[$i]}',{$element_count[$i]}],";
                            }
                            ?>

                        ]);

                        var options = {
                            chart: {
                                title: '',
                                subtitle: '',
                            }
                        };

                        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

                        chart.draw(data, google.charts.Bar.convertOptions(options));
                    }
                </script>

                <div id="columnchart_material" style="width: 'auto'; height: 500px;"></div>

            </div>



        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->


    <?php include_once "includes/admin_footer.php"; ?>