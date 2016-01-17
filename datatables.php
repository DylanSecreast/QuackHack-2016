<?php
 
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
 
// DB table to use
$table = 'steam';
 
// Table's primary key
$primaryKey = 'PersonName';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'PersonName', 'dt' => 0 ),
    array( 'db' => 'totalMatches', 'dt' => 1 ),
    array( 'db' => 'LifeTimeLoss', 'dt' => 2 ),
    array( 'db' => 'LifeTime_Wl', 'dt' => 3 ),
	array( 'db' => 'TotalMvps', 'dt' => 4 ),
/**	array( 'db' => 'LifeTimeMvps', 'dt' => 5 ),
	array( 'db' => 'TotalKills', 'dt' => 6 ),
	array( 'db' => 'TotalDeaths', 'dt' => 7 ),
	array( 'db' => 'LifetimeKd', 'dt' => 8 ),
	array( 'db' => 'LifeTimeAccuracy', 'dt' => 9 ),
	array( 'db' => 'LifeTime_hs_accuracy', 'dt' => 10 ),
	array( 'db' => 'LatestWins', 'dt' => 11 ),
	array( 'db' => 'LatestLoss', 'dt' => 12 ),
	array( 'db' => 'LatestWl', 'dt' => 13 ),
	array( 'db' => 'LatestMatchMvps', 'dt' => 14 ),
	array( 'db' => 'LatestMvps', 'dt' => 15 ),
	array( 'db' => 'LatestMatchKills', 'dt' => 16 ),
	array( 'db' => 'LatestMatchDeaths', 'dt' => 17 ),
	array( 'db' => 'LatestKd', 'dt' => 18 )
	
 **/   
);
 
// SQL server connection information
$sql_details = array(
    'user' => 'root',
    'pass' => '',
    'db'   => 'quackhack',
    'host' => 'localhost'
);
 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
 
require( 'ssp.php' );
 $sql = "SELECT PersonName, totalMatches, LifeTimeLoss FROM steam";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["PersonName"]. $row["totalMatches"]. " " . $row["LifeTimeLoss"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?> 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);
?>