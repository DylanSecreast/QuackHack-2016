<?php
#Definitions
define('DB_NAME', 'QuackHack');
define('DB_USER', 'root');
define('DB_PASSWORD','');
define('DB_HOST','localhost');
define('DB_TABLE_NAME','steam');

# Database Connection
$link = mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);
if(!$link){
	die('Could not connect:' . mysql_error());
	}

#Select the desired Database from DB Server	
$db_select = mysql_select_db(DB_NAME,$link);
if(!$db_select){
	die('Could not connect to'. DB_NAME .':'. mysql_error());
	}
//	echo 'Connected to DB!<br>';



$apiKey = "B7D3A451EBBB52BC5097A09A362F10C1";    // Steam Web API developer key

$steamId = array("76561197982036918","76561197961610974","76561197984560929","76561197988539104","76561197973730803",
    "76561197963914156","76561198116523276","76561198019196091","76561198106907258","76561198001822267","76561198006466707",
    "76561198012944495","76561197989744167","76561198024905796","76561197978241352","76561197960710573","76561198000782895");
$counterStrikeAppId = "730";


foreach($steamId as $steamIdTemp){
    $userStatsContent = file_get_contents("http://api.steampowered.com/ISteamUserStats/GetUserStatsForGame/v0002/?appid=$counterStrikeAppId&key=$apiKey&steamid=$steamIdTemp");
    $userSummaryContent = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$apiKey&steamids=$steamIdTemp");
//    print_r($userSummaryContent);
    $userStatsJsonDecoded = json_decode($userStatsContent, true);    // true returns array
    $userSummaryJsonDecoded = json_decode($userSummaryContent, true);

    // Get stats variables
    if (!function_exists('getUserStatsJsonDecoded')) {
        function getUserStatsJsonDecoded($index) {
            global $userStatsJsonDecoded;
        return $userStatsJsonDecoded['playerstats']['stats'][$index]['value'];
        }
    }

    // Get summary variables
    if (!function_exists('getUserSummaryJsonDecoded')) {
        function getUserSummaryJsonDecoded($index) {
            global $userSummaryJsonDecoded;
            return $userSummaryJsonDecoded['playerstats']['stats'][$index]['value'];
        }
    }

    // Round floats to 2 decimal places - returns as string
    if (!function_exists('roundFloat')) {
        function roundFloat($element) {
            return number_format((float)$element, 2, '.', '');
        }
    }


    // Set summary variables
    $personaname = $userSummaryJsonDecoded['response']['players'][0]['personaname'];
    $avatarmedium = $userSummaryJsonDecoded['response']['players'][0]['avatarmedium'];


    // Set stats variables
    for ($x = 0; $x < count($userStatsJsonDecoded['playerstats']['stats']); $x++) {

        if ($userStatsJsonDecoded['playerstats']['stats'][$x]['name'] === 'total_kills') {
            $total_kills = getUserStatsJsonDecoded($x);
        }
		if ($userStatsJsonDecoded['playerstats']['stats'][$x]['name'] === 'total_deaths') {
            $total_deaths = getUserStatsJsonDecoded($x);
        }
		if ($userStatsJsonDecoded['playerstats']['stats'][$x]['name'] === 'total_matches_won') {
            $total_matches_won = getUserStatsJsonDecoded($x);
        }
		if ($userStatsJsonDecoded['playerstats']['stats'][$x]['name'] === 'total_matches_played') {
            $total_matches_played = getUserStatsJsonDecoded($x);
        }
		if ($userStatsJsonDecoded['playerstats']['stats'][$x]['name'] === 'total_shots_fired') {
            $total_shots_fired = getUserStatsJsonDecoded($x);
        }
		if ($userStatsJsonDecoded['playerstats']['stats'][$x]['name'] === 'total_shots_hit') {
            $total_shots_hit = getUserStatsJsonDecoded($x);
        }
		if ($userStatsJsonDecoded['playerstats']['stats'][$x]['name'] === 'total_kills_headshot') {
            $total_kills_headshot = getUserStatsJsonDecoded($x);
        }
		if ($userStatsJsonDecoded['playerstats']['stats'][$x]['name'] === 'total_mvps') {
            $total_mvps = getUserStatsJsonDecoded($x);
        }
		if ($userStatsJsonDecoded['playerstats']['stats'][$x]['name'] === 'total_planted_bombs') {
            $total_planted_bombs = getUserStatsJsonDecoded($x);
        }
		if ($userStatsJsonDecoded['playerstats']['stats'][$x]['name'] === 'total_defused_bombs') {
            $total_defused_bombs = getUserStatsJsonDecoded($x);
        }
        if ($userStatsJsonDecoded['playerstats']['stats'][$x]['name'] === 'last_match_t_wins') {
            $last_match_t_wins = getUserStatsJsonDecoded($x);
        }
        if ($userStatsJsonDecoded['playerstats']['stats'][$x]['name'] === 'last_match_ct_wins') {
            $last_match_ct_wins = getUserStatsJsonDecoded($x);
        }
        if ($userStatsJsonDecoded['playerstats']['stats'][$x]['name'] === 'last_match_mvps') {
            $last_match_mvps = getUserStatsJsonDecoded($x);
        }
        if ($userStatsJsonDecoded['playerstats']['stats'][$x]['name'] === 'last_match_kills') {
            $last_match_kills = getUserStatsJsonDecoded($x);
        }
        if ($userStatsJsonDecoded['playerstats']['stats'][$x]['name'] === 'last_match_deaths') {
            $last_match_deaths = getUserStatsJsonDecoded($x);
        }

    }


    // Lifetime Stat Calculations
    $lifetime_kd = $total_kills / $total_deaths;
    $lifetime_loss = $total_matches_played - $total_matches_won;
    $lifetime_wl = $total_matches_won / $lifetime_loss;
    $lifetime_accuracy = ($total_shots_hit / $total_shots_fired) * 100;
    $lifetime_hs_accuracy = ($total_kills_headshot / $total_kills) * 100;
    $lifetime_mvps = ($total_mvps / ($total_matches_played * 30)) * 100;    // % odds of being round mvp


//    $lifetime_bombs = $total_planted_bombs / $total_defused_bombs;

//    $lifetime_maps = $total_wins_map_cs_assault + $total_wins_map_cs_office + $total_wins_map_de_aztec
//   + $total_wins_map_de_cbble + $total_wins_map_de_dust2 + $total_wins_map_de_dust +
//   $total_wins_map_de_inferno + $total_wins_map_de_nuke + $total_wins_map_de_train;
//    $lifetime_map_assault = ($total_wins_map_cs_assault / $lifetime_maps) * 100;
//    $lifetime_map_office = ($total_wins_map_cs_office / $lifetime_maps) * 100;
//    $lifetime_map_aztec = ($total_wins_map_de_aztec / $lifetime_maps) * 100;
//    $lifetime_map_cbble = ($total_wins_map_de_cbble / $lifetime_maps) * 100;
//    $lifetime_map_dust2 = ($total_wins_map_de_dust2 / $lifetime_maps) * 100;
//    $lifetime_map_dust = ($total_wins_map_de_dust / $lifetime_maps) * 100;
//    $lifetime_map_inferno = ($total_wins_map_de_inferno / $lifetime_maps) * 100;
//    $lifetime_map_nuke = ($total_wins_map_de_nuke / $lifetime_maps) * 100;
//    $lifetime_map_train = ($total_wins_map_de_train / $lifetime_maps) * 100;

    // Latest Stat Calculations
    $latest_wins = $last_match_t_wins + $last_match_ct_wins;
    if ((30 - $latest_wins) < 0 ) {     // latest_loss
        $latest_loss = 0;
    } else {
        $latest_loss = 30 - $latest_wins;
    }
    if ($latest_loss == 0) {            // latest_wl
        $latest_wl = $latest_wins / 1;
    } else {
        $latest_wl = $latest_wins / $latest_loss;
    }
    $latest_kd = $last_match_kills / $last_match_deaths;
    $latest_mvps = ($last_match_mvps / 30) * 100;

    $preventDuplicatesCounter = '0';

	$sql = "INSERT INTO ".DB_TABLE_NAME."(PersonName,totalMatches,LifeTimeLoss,LifeTime_Wl,TotalMvps,LifeTimeMvps,TotalKills,TotalDeaths,LifetimeKd,LifeTimeAccuracy,LifeTime_hs_accuracy,LatestWins,LatestLoss,LatestWl,LatestMatchMvps,LatestMvps,LatestMatchKills,LatestMatchDeaths,LatestKd)
	VALUES ('$personaname','$total_matches_won','$lifetime_loss','$lifetime_wl','$total_mvps','$lifetime_mvps','$total_kills','$total_deaths', '$lifetime_kd', '$lifetime_accuracy', '$lifetime_hs_accuracy', '$latest_wins', '$latest_loss', '$latest_wl', '$last_match_mvps','$latest_mvps', '$last_match_kills', '$last_match_deaths', '$latest_kd')";

	if(!mysql_query($sql)){
		die('ERROR:'.mysql_error());		
	}
		
}   // end foreach()

// Get sql - lifetime w/l decending
$query = "SELECT * FROM `steam` ORDER BY `LifeTime_Wl` DESC";
$result = mysql_query($query) or die(mysql_error());

// Get top 5 stats
$rankingCounter = 1;
while($row = mysql_fetch_assoc($result)){
    ${"rank_name_{$rankingCounter}"} = $row['PersonName'];
    ${"rank_wl_{$rankingCounter}"} = $row['LifeTime_Wl'];

    ${"rank_kd_{$rankingCounter}"} = $row['LifetimeKd'];

    ${"rank_mvp_{$rankingCounter}"} = $row['LifeTimeMvps'];

    ${"rank_accuracy_{$rankingCounter}"} = $row['LifeTimeAccuracy'];

    $rankingCounter = $rankingCounter+1;
}


mysql_close();
?>







<!DOCTYPE html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>CS:GO Forecast</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">

    <!--[if lte IE 9]>
    <link rel="stylesheet" type="text/css" href="css/ie9-and-down.css" />
    <![endif]-->

    <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    <script src="js/vendor/video.js"></script>

</head>

<body>


<!-- //// BEGIN HOME PAGE //// -->
<div class="main">
    <section class="home full-color">
        <div class="container">

            <div class="row">
                <div class="home-copy col-xs-3">
                    <p></p>
                </div>

                <div class="home-copy col-xs-3 col-xs-offset-6">
                    <p></p>
                </div>
            </div>


            <div class="home-copy col-md-10 col-md-offset-1">
                <center>
                    <div class="hidden-xs">
                        <h2 class="home-1-title slide-titles">Counter Strike : Global Offensive Forecast</h2>
                    </div>
                    <h3>Live eSports Statistical Analysis</h3>
                    <br><br>
                </center>
            </div>


            <br>


            <div class="container-fluid">
                <div class="row">
                    <div id="demo-12-col" class="col-xs-12 animated fadeInUp delay-1">
                        <div class="col-xs-2 col-xs-15 animated fadeInUp delay-1">
                            <!-- front page player #1 -->
                            <p class="text-center">
                                <img src="img/trophies/firstplace.png"><br>
                                <b><?php echo $rank_name_1; ?></b><br><br>
                                Win/Loss:<br><?php echo roundFloat($rank_wl_10); ?><br><br>
                                Kills/Deaths:<br><?php echo roundFloat($rank_kd_1); ?><br><br>
                                Round MVP:<br><?php echo roundFloat($rank_mvp_1); ?>%<br><br>
                                Accuracy:<br><?php echo roundFloat($rank_accuracy_1); ?>%
                            </p>
                        </div>

                        <div class="col-xs-2 col-xs-15 animated fadeInUp delay-2">
                            <!-- front page player #2 -->
                            <p class="text-center">
                                <img src="img/trophies/secondplace.png"><br>
                                <b><?php echo $rank_name_2; ?></b><br><br>
                                Win/Loss:<br><?php echo roundFloat($rank_wl_2); ?><br><br>
                                Kills/Deaths:<br><?php echo roundFloat($rank_kd_2); ?><br><br>
                                Round MVP:<br><?php echo roundFloat($rank_mvp_2); ?>%<br><br>
                                Accuracy:<br><?php echo roundFloat($rank_accuracy_2); ?>%
                            </p>
                        </div>

                        <div class="col-xs-2 col-xs-15 animated fadeInUp delay-3">
                            <!-- front page player #3 -->
                            <p class="text-center">
                                <img src="img/trophies/thirdplace.png"><br>
                                <b><?php echo $rank_name_3; ?></b><br><br>
                                Win/Loss:<br><?php echo roundFloat($rank_wl_3); ?><br><br>
                                Kills/Deaths:<br><?php echo roundFloat($rank_kd_3); ?><br><br>
                                Round MVP:<br><?php echo roundFloat($rank_mvp_3); ?>%<br><br>
                                Accuracy:<br><?php echo roundFloat($rank_accuracy_3); ?>%
                            </p>
                        </div>

                        <div class="col-xs-2 col-xs-15 animated fadeInUp delay-4">
                            <!-- front page player #4 -->
                            <p class="text-center">
                                <img src="img/trophies/forthplace.png"><br>
                                <b><?php echo $rank_name_4; ?></b><br><br>
                                Win/Loss:<br><?php echo roundFloat($rank_wl_4); ?><br><br>
                                Kills/Deaths:<br><?php echo roundFloat($rank_kd_4); ?><br><br>
                                Round MVP:<br><?php echo roundFloat($rank_mvp_4); ?>%<br><br>
                                Accuracy:<br><?php echo roundFloat($rank_accuracy_4); ?>%
                            </p>
                        </div>

                        <div class="col-xs-2 col-xs-15 animated fadeInUp delay-5">
                            <!-- front page player #5 -->
                            <p class="text-center">
                                <img src="img/trophies/fifthplace.png"><br>
                                <b><?php echo $rank_name_5; ?></b><br><br>
                                Win/Loss:<br><?php echo roundFloat($rank_wl_5); ?><br><br>
                                Kills/Deaths:<br><?php echo roundFloat($rank_kd_5); ?><br><br>
                                Round MVP:<br><?php echo roundFloat($rank_mvp_5); ?>%<br><br>
                                Accuracy:<br><?php echo roundFloat($rank_accuracy_1); ?>%
                            </p>
                        </div>

                    </div> <!-- end row -->
                </div>

                <!-- bottom arrow -->
                <div class="bottomCenter animated">
                    <div class="bounce">
                        <span class="take-a-look"><i class="fa fa-hand-o-down fa-3x"></i></span>
                    </div>
                </div>

            </div> <!-- end container -->
    </section>
    <!-- //// END HOME PAGE //// -->


    <!-- //// BEGIN SLIDE 1 //// -->
    <section class="slide-1 slide full-color">
        <div class="container">

            <div class="col-xs-12">
                <center>
                    <br>
                    <h2 class="home-1-title slide-titles">Example of Raw Data Processed</h2>
                </center>
            </div>

            <div class="row">
                <div class="col-xs-6 "><br><br><br>
                    <p>
                        <?php
                            echo "<b>Lifetime Stats - $personaname</b><br><br>";
                            echo "Matches Won: $total_matches_won<br>";
                            echo "Matches Lost: $lifetime_loss<br>";
                            echo "Match Win/Loss Ratio: $lifetime_wl<br><br>";

                            echo "MVP's: $total_mvps<br>";
                            echo "Odds of being round MVP: $lifetime_mvps<br><br>";

                            echo "Kills: $total_kills<br>";
                            echo "Deaths: $total_deaths<br>";
                            echo "Kill/Death Ratio: $lifetime_kd<br><br>";

                            echo "Overall Accuracy: $lifetime_accuracy<br>";
                            echo "Headshot Accuracy: $lifetime_hs_accuracy<br><br>";

                        ;?>


                        <?php
                            echo "<b>Latest Match Stats - $personaname</b><br><br>";
                            echo "Rounds Won: $latest_wins<br>";
                            echo "Rounds Lost: $latest_loss<br>";
                            echo "Round Win/Loss Ratio: $latest_wl<br><br>";

                            echo "MVP's: $last_match_mvps<br>";
                            echo "Odds of being round MVP: $latest_mvps<br><br>";

                            echo "Kills: $last_match_kills<br>";
                            echo "Deaths: $last_match_deaths<br>";
                            echo "Kill/Death Ratio: $latest_kd<br><br>";
                        ;?>
                    </p>
                </div>

                <div class="col-xs-6 "><br><br><br>
                      <p><b>Additional Stats Tracked</b></p>
                        <ul><font color="white">
                            <li>Headshot Accuracy</li>
                            <li>Bombs Planted/Defused</li>
                            <li>Weapons Gifted</li>
                            <li>Windows Broken</li>
                            <li>Knife Kills</li>
                            <li>Pistol Kills</li>
                                <li>Money Earned/Spent</li>
                                <li>Wins on cs_assault</li>
                            <li>Wins on cs_office</li>
                            <li>Wins on de_aztec</li>
                            <li>Wins on de_cobble</li>
                            <li>Wins on de_dust2</li>
                            <li>Wins on de_dust</li>
                            <li>Wins on de_inferno</li>
                            <li>Wins on de_nuke</li>
                            <li>Wins on de_train</li>
                        </ul></font>
                </div>
            </div>

            <!-- bottom arrow -->
            <div id="agreeArrow" class="bottomCenter hide">
                <div class="bounce">
                    <span class="take-a-look"><i class="fa fa-hand-o-down fa-3x"></i></span>
                </div>
            </div>

        </div> <!-- end container -->

    </section>
    <!-- //// END SLIDE 1 //// -->






    <!-- //// BEGIN SLIDE 3 //// -->
    <section class="slide-3 slide full-color">

        <!-- wait! container -->
        <div class="container">
            <div class="col-md-12">
                <br><br>
                <h2 class="slide-3-title slide-titles">So, what exactly is this?</h2>
                <br><br>
            </div>

            <div class="col-md-4">
                <p>
                    <b>CS:GO Forecast</b> is a web platform dedicated to evaluating the performance of eSports professionals and
                    their careers playing Valve's blockbuster PC game, Counter Strike : Global Offensive.
                </p>
            </div>

            <div class="col-md-4">
                <p>
                    Historical and live statistics are pulled directly from the source of top professionals as they play.
                    This data is aggregated, analyzed, and published as a roster of a 5-person fantasy draft.
                </p>
            </div>

            <div class="col-md-4">
                <p>
                    It's our goal with Forecast to present unbiased ironclad data to give fans all over the world a fighting
                    chance to capitalize on a $465 million thriving market. <i>All content on CS:GO Forecast is for educational
                    purposes and there is no affiliation with Valve.</i>
                </p>
            </div>

            <div class="col-md-12 text-center">
                <br><br><br><br>
                <h2 class="slide-3-title slide-titles">CS:GO Forecast is made possible by:</h2>
                <br>
                <img src="img/techicons/apache.png">
                <img src="img/techicons/html.png">
                <img src="img/techicons/javascript.png">
                <img src="img/techicons/json.png">
                <img src="img/techicons/mysql.png">
                <img src="img/techicons/php.png">
                <img src="img/quackhacklogo.png">
                <br><br>
                <h4>
                    <b>A product of QuackHack 2016 - University of Oregon</b>
                    <br><br>
                    Created by: Dylan Secreast & Raj Nannapaneni
                </h4>
            </div>

        </div> <!-- end container -->

    </section>
    <!-- //// END SLIDE 3 //// -->


    <!-- //// BEGIN SLIDE 4 //// -->
    <section class="contact full-color">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3">
                <h2>Keep in touch</h2>
                <p> We're always keen to here what you think, send us a message from all the usual places.</p>

                <div class="newsletter clearfix">
                    <div id="mc_embed_signup">

                        <!--
                        form action must be set as url for mailchimp account. Ensure that it uses post-json and that you have a c=? on the end as below.

                        example  action="//testusername.us3.list-manage.com/subscribe/post-json?u=75f4e1b40d3762253171e9d55&amp;id=c74a91dc25&amp;c=?"
                        -->

                        <form action="//sambillingham.us3.list-manage.com/subscribe/post-json?u=75f4e1b90d3762255171e9d55&amp;id=c75a95dc25&amp;c=?" method="get" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                            <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="Your Email" required>
                            <button type="submit" class="btn submit btn-signup" value="" name="subscribe" id="mc-embedded-subscribe" class="button"><i class="fa fa-paper-plane"></i></button>
                        </form>
                    </div>
                    <div class="sign-up-message hidden"></div>
                </div>


                <div class="social clearfix">
                    <a href="#" class="social-item facebook"><i class="fa fa-facebook"></i></a>
                    <a href="#" class="social-item twitter"><i class="fa fa-twitter"></i></a>
                </div>
            </div>
        </div>
    </section>
    <!-- //// END SLIDE 4 //// -->

</div> <!-- end showHide -->

</div> <!-- /main -->



<script src="js/plugins.js"></script>
<script src="js/main.js"></script>
</body>
</html>
