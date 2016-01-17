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
	

#Create a new table
$sql_create_table = "CREATE TABLE ".DB_TABLE_NAME."(
id MEDIUMINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
PersonName varchar(50),
totalMatches varchar(15),
LifeTimeLoss  varchar(15),
LifeTime_Wl varchar(15),
TotalMvps varchar(15),
LifeTimeMvps varchar(15),
TotalKills varchar(15),
TotalDeaths varchar(15),
LifetimeKd varchar(15),
LifeTimeAccuracy varchar(15),
LifeTime_hs_accuracy varchar(15),
LatestWins varchar(15),
LatestLoss varchar(15),
LatestWl varchar(15),
LatestMatchMvps varchar(15),
LatestMvps varchar(15),
LatestMatchKills varchar(15),
LatestMatchDeaths varchar(15),
LatestKd varchar(15)
)";	
if(!mysql_query($sql_create_table,$link)){
	die('Could not create a table : ' . mysql_error()); 
	}
	echo 'Created DB '. DB_TABLE_NAME .'!<br>';
?>
