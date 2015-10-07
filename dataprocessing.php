<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<?php
session_start();
?>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Data Processing</title>

        <!-- Javascript -->
        <script src="js/d3.js"></script>
        <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
        <script src="js/crossfilter.js"></script>
        <script src="js/dc.js"></script>
        <!-- <script src="js/leaflet.js"></script>
         <script src="js/leaflet.markercluster.js"></script>
         <script src="js/dc.leaflet.js"></script>-->
        <script src="js/jquery-2.1.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.gridster.js" type="text/javascript" charset="utf-8"></script>




        <!--File Upload-->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="js/fileinput.js" type="text/javascript"></script>
        <script src="js/fileinput_locale_fr.js" type="text/javascript"></script>
        <script src="js/fileinput_locale_es.js" type="text/javascript"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js" type="text/javascript"></script>

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>


        <!-- CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet"/>
        <!--<link href="css/leaflet.css" rel="stylesheet"/>
        <link href="css/leaflet.markercluster.css" rel="stylesheet"/>-->
        <link href="css/dc.css" rel="stylesheet"/>
        <link href="css/styles.css" rel="stylesheet">
        <link href="css/jquery.gridster.css" rel="stylesheet" />

        <link rel="stylesheet" type="text/css" href="css/MultistepForm.css">

        <!-- Custom CSS -->
        <style>
            body {
                padding-top: 20px;
                padding-left: 10px;
                background: #808080;
                //background: url('http://thecodeplayer.com/uploads/media/gs.png');
            }

            #bs-example-navbar-collapse-1 ul li a:hover {
                border-bottom:2px #FFF solid;
            }
        </style>
        <!-- style for Heat-Box -->
        <style>
            .heat-box {
                stroke: #E6E6E6;
                stroke-width: 2px;
            }
        </style>
        <!-- END style for Heat-Box -->
        <!-- style for Map -->
        <style type="text/css">
            svg text {
                fill: black;
            }

            #map{
                width: 550px;
                height: 400px;
                margin: 0;
                padding: 0;
            }
        </style>
        <!-- END style for Map -->
        <!-- style for Stratification Number Display -->        
        <style>
            #tableNumDisplay1 td{
                border: 5px solid #98bf21;
            }
            #numboxHP{
                background: #1f77b4;
                width: 80px;
                font-size: 18px;
                text-align: center;
                padding-bottom: 20px;
                height: 25px;
            }
            #numboxUP{
                background: #2ca02c;
                width: 80px;
                font-size: 18px;
                text-align: center;
                padding-bottom: 20px;
                height: 30px;
            }
            #numboxHN{
                background: #ff7f0e;
                width: 80px;
                font-size: 18px;
                text-align: center;
                padding-bottom: 20px;
                height: 40px;
            }
            #numboxUN{
                background: #d62728;
                width: 80px;
                font-size: 18px;
                text-align: center;
                padding-bottom: 20px;
                height: 86px;
            }
            <!-- END style for Stratification Number Display -->
        </style>
    </head>
    <body>
        <!-- Navigation -->
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
                    <!-- <a class='navbar-brand' href='#'><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>-->
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li>
                            <a href="home.html"><span class="glyphicon glyphicon-th-large" style="padding:0px" aria-hidden="true"></span> Screening Result</a>
                        </li>
                        <li>
                            <a href="classificationTry1.html"><span class="glyphicon glyphicon-heart" aria-hidden="true"></span> Health Classification</a>
                        </li>
                        <li>
                            <a href="#"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Patient Journey</a>
                        </li>
                        <li>
                            <a href="analysis.html"><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span> Repeat Analysis</a>
                        </li>
                        <li>
                            <a href="geospatial.html"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span> Geospatial Intelligence</a>
                        </li>
                        <li>                        
                            <a href="dataprocessing.php" style="background-color:#1AACBF;color:#FFF;border-bottom:2px #1AACBF solid"><span class="glyphicon glyphicon-upload" style="padding:0px" aria-hidden="true"></span>Data Processing</a>
                        </li>
                        <li>
                            <!-- <a href="#">Logout</a>-->
                        </li>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container -->
        </nav>

        <br/>
        <br/>
        <br/>
        <br/>

        <div class="container">

            <?php
            if (empty($_SESSION['FileUpload'])) {
                ?>

                <div id="msform">
                    <ul id="progressbar">
                        <li class="active">File Upload</li>
                        <li>File Validation</li>
                        <li>Transformation</li>
                        <li>Geo Coding</li>
                        <li>Complete</li>
                    </ul>
                    <fieldset>
                        <h2 class="fs-title">File Upload</h2>
                        <h3 class="fs-subtitle">Please upload excel file.</h3>

                        <br/>
                        <form id="uploadForm" action="fileUpload.php" method="post" enctype="multipart/form-data">
                            <input id="inputFile" type="file" class="file"  name="file" data-show-preview="false">
                            <input type="hidden" name="StartFileUpload" value="1" />
                        </form>
                    </fieldset>
                </div>

                <?php
            } else {
                ?>

                <div id="msform">

                    <ul id="progressbar">
                        <li class="active">File Upload</li>
                        <li class="active">File Validation</li>
                        <li>Transformation</li>
                        <li>Geo Coding</li>
                        <li>Complete</li>
                    </ul>

                    <fieldset>
                        <?php
                        $FileValidation = $_SESSION['FileValidation'];
                        if ($FileValidation != 'File Validation Successful!') {
                            ?>
                            <h2 class="fs-title">File Validation</h2>
                            <div class="alert alert-danger" role="alert"><h3>Warning:<?php echo $FileValidation ?></h3></div>
                            <?php unset($_SESSION['response']); ?>
                            <input type="button" class="next action-button" value="Previous" id="FreshPage" />
                            <?php
                        } else {
                            ?>
                            <h2 class="fs-title">File Validation</h2>
                            <div class="alert alert-success" role="alert"><h3>You have uploaded an excel file!</h3></div>
                            <input type="button" class="next action-button" value="Previous" id="FreshPage" />
                            <input type="button" name="next" class="next action-button" value="Next" id="Next" />
                            <?php
                        }
                        ?>
                    </fieldset>

                    <fieldset>
                        <h2 class="fs-title">File Validation</h2>
                        <div class="alert alert-success" role="alert">
                            <div align="left">
                                <h3>
                                    You have uploaded the following 3 worksheets:<br/>
                                    Demographics : 11 Columns;<br/>
                                    Screening Records : 25 Columns;<br/>
                                    SGPostal :  8 Columns;
                                </h3>
                            </div>
                        </div>
                        <input type="button" name="previous" class="previous action-button" value="Previous" />
                        <input type="button" name="next" class="next action-button" value="Next" id="FileValidationNext" />
                    </fieldset>
                    <fieldset>
                        <?php
                        if (!empty($_SESSION['Transformation'])) {
                            $Transformation = $_SESSION['Transformation'];
                            ?>
                            <h3 class="fs-title">Transformation</h3>
                            <div class="alert alert-danger" role="alert"><h3>Warning:<?php echo $Transformation ?></h3></div>
                            <?php unset($_SESSION['Transformation']); ?>
                            <input type="button" class="next action-button" value="Previous" id="FreshPage" />
                            <?php unset($_SESSION['Transformation']); ?>
                            <?php
                        } else {
                            ?>
                            <h2 class="fs-title">Transformation</h2>
                            <div class="alert alert-success" role="alert"><h3>Your data has been successfully transformed!</h3></div>
                            <input type="button" name="previous" class="previous action-button" value="Previous" />
                            <input type="button" name="next" class="next action-button" value="Next" id="TransformationNext" />
                            <?php
                        }
                        ?>
                    </fieldset>
                    <fieldset>
                        <h2 class="fs-title">Geo Coding</h2>
                        <?php
                        if (!empty($_SESSION['GeoErrorReport'])) {
                            $GeoErrorReport = $_SESSION["GeoErrorReport"];
                            ?>
                            <div class="alert alert-danger" role="alert">

                                <div align="left">
                                    <h4>Warning:Fail to find the following location:</h4>
                                    <?php
                                    foreach ($GeoErrorReport as $Address) {
                                        ?>
                                        <h5>
                                            <?php
                                            echo $Address;
                                            ?>
                                        </h5>
                                        <?php
                                    }
                                    ?>
                                    <h4>Click To Download Error Report:</h4>
                                </div>
                                <?php unset($_SESSION['GeoErrorReport']); ?>
                                <a href="GeoCodeError.txt" download>
                                    <img border="0" src="images/download.jpg" width="100" height="100">
                                </a>
                            </div>

                            <input type="button" name="previous" class="previous action-button" value="Previous" />
                            <input type="button" name="next" class="next action-button" value="Next" id="GeoCodeNext" />
                            <?php
                        } else {
                            ?>
                            <div class="alert alert-success" role="alert"><h3>Geo Code has been generated successfully!</h3></div>
                            <input type="button" name="previous" class="previous action-button" value="Previous" />
                            <input type="button" name="next" class="next action-button" value="Next" id="GeoCodeNext" />
                            <?php
                        }
                        ?>
                    </fieldset>
                    <fieldset>
                        <h2 class="fs-title">Complete</h2>
                        <div class="alert alert-success" role="alert">
                            <div align="left">
                                <h3>
                                    Your data has been successfully loaded to database:<br/>
                                    Demographics : <?php echo $_SESSION['Demographic']; ?> Records;<br/>
                                    Screening Records : <?php echo $_SESSION['Screening']; ?> Records;<br/>
                                    SGPostal :  <?php echo $_SESSION['Postal']; ?> Records;
                                </h3>
                            </div>
                        </div>

                        <input type="button" name="previous" class="previous action-button" value="Previous" />
                        <input type="submit" name="submit" class="submit action-button" value="Complete" />
                    </fieldset>
                </div>


                <?php
                unset($_SESSION['FileUpload']);
            }
            ?>



            <!-- jQuery -->
            <script src="http://thecodeplayer.com/uploads/js/jquery-1.9.1.min.js" type="text/javascript"></script>
            <!-- jQuery easing plugin -->
            <script src="http://thecodeplayer.com/uploads/js/jquery.easing.min.js" type="text/javascript"></script>

            <script>
                $("#inputFile").fileinput({
                    showUpload: true,
                    layoutTemplates: {
                        main1: "{preview}\n" +
                                "<div class=\'input-group {class}\'>\n" +
                                "   <div class=\'input-group-btn\'>\n" +
                                "       {browse}\n" +
                                "       {upload}\n" +
                                "       {remove}\n" +
                                "   </div>\n" +
                                "   {caption}\n" +
                                "</div>"
                    },
                    mainClass: "input-group-lg"
                });
            </script>

            <script>

                //jQuery time
                var current_fs, next_fs, previous_fs; //fieldsets
                var left, opacity, scale; //fieldset properties which we will animate
                var animating; //flag to prevent quick multi-click glitches



                $("#Next").click(function () {
                    if (animating)
                        return false;
                    animating = true;

                    current_fs = $(this).parent();
                    next_fs = $(this).parent().next();


                    //activate next step on progressbar using the index of next_fs
                    $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

                    //show the next fieldset
                    next_fs.show();
                    //hide the current fieldset with style
                    current_fs.animate({opacity: 0}, {
                        step: function (now, mx) {
                            //as the opacity of current_fs reduces to 0 - stored in "now"
                            //1. scale current_fs down to 80%
                            scale = 1 - (1 - now) * 0.2;
                            //2. bring next_fs from the right(50%)
                            left = (now * 50) + "%";
                            //3. increase opacity of next_fs to 1 as it moves in
                            opacity = 1 - now;
                            current_fs.css({'transform': 'scale(' + scale + ')'});
                            next_fs.css({'left': left, 'opacity': opacity});
                        },
                        duration: 800,
                        complete: function () {
                            current_fs.hide();
                            animating = false;
                        },
                        //this comes from the custom easing plugin
                        easing: 'easeInOutBack'
                    });
                });

                $("#FileValidationNext").click(function () {
                    if (animating)
                        return false;
                    animating = true;

                    current_fs = $(this).parent();
                    next_fs = $(this).parent().next();


                    //activate next step on progressbar using the index of next_fs
                    $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

                    //show the next fieldset
                    next_fs.show();
                    //hide the current fieldset with style
                    current_fs.animate({opacity: 0}, {
                        step: function (now, mx) {
                            //as the opacity of current_fs reduces to 0 - stored in "now"
                            //1. scale current_fs down to 80%
                            scale = 1 - (1 - now) * 0.2;
                            //2. bring next_fs from the right(50%)
                            left = (now * 50) + "%";
                            //3. increase opacity of next_fs to 1 as it moves in
                            opacity = 1 - now;
                            current_fs.css({'transform': 'scale(' + scale + ')'});
                            next_fs.css({'left': left, 'opacity': opacity});
                        },
                        duration: 800,
                        complete: function () {
                            current_fs.hide();
                            animating = false;
                        },
                        //this comes from the custom easing plugin
                        easing: 'easeInOutBack'
                    });
                });





                $("#TransformationNext").click(function () {
                    if (animating)
                        return false;
                    animating = true;

                    current_fs = $(this).parent();
                    next_fs = $(this).parent().next();


                    //activate next step on progressbar using the index of next_fs
                    $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

                    //show the next fieldset
                    next_fs.show();
                    //hide the current fieldset with style
                    current_fs.animate({opacity: 0}, {
                        step: function (now, mx) {
                            //as the opacity of current_fs reduces to 0 - stored in "now"
                            //1. scale current_fs down to 80%
                            scale = 1 - (1 - now) * 0.2;
                            //2. bring next_fs from the right(50%)
                            left = (now * 50) + "%";
                            //3. increase opacity of next_fs to 1 as it moves in
                            opacity = 1 - now;
                            current_fs.css({'transform': 'scale(' + scale + ')'});
                            next_fs.css({'left': left, 'opacity': opacity});
                        },
                        duration: 800,
                        complete: function () {
                            current_fs.hide();
                            animating = false;
                        },
                        //this comes from the custom easing plugin
                        easing: 'easeInOutBack'
                    });
                });


                $("#GeoCodeNext").click(function () {
                    if (animating)
                        return false;
                    animating = true;

                    current_fs = $(this).parent();
                    next_fs = $(this).parent().next();

                    var GeoCode =
                            $.ajax({
                                type: "POST",
                                url: "fileUploadUI.php",
                                success: function (msg) {
                                    $('#resultip').html(msg);
                                }
                            });


                    //activate next step on progressbar using the index of next_fs
                    $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

                    //show the next fieldset
                    next_fs.show();
                    //hide the current fieldset with style
                    current_fs.animate({opacity: 0}, {
                        step: function (now, mx) {
                            //as the opacity of current_fs reduces to 0 - stored in "now"
                            //1. scale current_fs down to 80%
                            scale = 1 - (1 - now) * 0.2;
                            //2. bring next_fs from the right(50%)
                            left = (now * 50) + "%";
                            //3. increase opacity of next_fs to 1 as it moves in
                            opacity = 1 - now;
                            current_fs.css({'transform': 'scale(' + scale + ')'});
                            next_fs.css({'left': left, 'opacity': opacity});
                        },
                        duration: 800,
                        complete: function () {
                            current_fs.hide();
                            animating = false;
                        },
                        //this comes from the custom easing plugin
                        easing: 'easeInOutBack'
                    });
                });



                $(".previous").click(function () {
                    if (animating)
                        return false;
                    animating = true;

                    current_fs = $(this).parent();
                    previous_fs = $(this).parent().prev();

                    //de-activate current step on progressbar
                    $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

                    //show the previous fieldset
                    previous_fs.show();
                    //hide the current fieldset with style
                    current_fs.animate({opacity: 0}, {
                        step: function (now, mx) {
                            //as the opacity of current_fs reduces to 0 - stored in "now"
                            //1. scale previous_fs from 80% to 100%
                            scale = 0.8 + (1 - now) * 0.2;
                            //2. take current_fs to the right(50%) - from 0%
                            left = ((1 - now) * 50) + "%";
                            //3. increase opacity of previous_fs to 1 as it moves in
                            opacity = 1 - now;
                            current_fs.css({'left': left});
                            previous_fs.css({'transform': 'scale(' + scale + ')', 'opacity': opacity});
                        },
                        duration: 800,
                        complete: function () {
                            current_fs.hide();
                            animating = false;
                        },
                        //this comes from the custom easing plugin
                        easing: 'easeInOutBack'
                    });
                });

                $('#FreshPage').click(function () {
                    location.reload();
                });

                $(".submit").click(function () {
                    window.location.href = 'home.html';
                })



            </script>
        </div>





    </body>
</html>