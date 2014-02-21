<?php
header("Content-type: text/json");

/*
Display the collected (and stored) data by the poller via ycharts
*/

//includes
include "includes/config.php";
// database config
include "includes/sql_cred.php";
// database query

//$timeframe = 3600;

switch ($_GET["context"]) {
	case timing:
		$query = "SELECT `timestamp`, `67`, `68`, `70`, `71`, `72`, `73`, `74`, `75`, `77`, `141` FROM `$mytimetable`WHERE timestamp > ((SELECT UNIX_TIMESTAMP()) - $timeframe)";
		break;
	case operation:
	case impulse:
		$query = "SELECT `timestamp`, `56`, `57`, `60`, `63`, `64`, `65` FROM `$myoptable`WHERE timestamp > ((SELECT UNIX_TIMESTAMP()) - $timeframe)";
		break;
	default:
		$query = "SELECT (`timestamp`*1000) as 'timestamp',  ((`15`)*0.1) as 'actualtemp',  ((`16`)*0.1) as 'avgtemp', ((`17`)*0.1) as 'boilertemp' FROM `$mytemptable`WHERE timestamp > ((SELECT UNIX_TIMESTAMP()) - $timeframe)";
	}

//Connect to the database. (host,username,password,database)
$mysqli = mysqli_connect($myhost, $myuser, $mypasswd, $mydatabase);
// Check for errors connecting to database.
if (mysqli_connect_errno()) {
        die('Unable to connect to database. '.$mysqli -> connect_error);
}
try {
        // All queries and commands go here.
	// multi query
        if(!$mysqli -> multi_query($query)) {
		// get exception
                throw new Exception($mysqli -> error);
        } else {
		do {
			// get results
			if ($result = $mysqli->use_result()) {
				while ($row = $result->fetch_array(MYSQLI_ASSOC)) { 
					switch ($_GET["context"]) {
					        case avgtemp:
							$data[] = "[$row[timestamp], $row[avgtemp]]";
							break;
						case actualtemp:
							$data[] = "[$row[timestamp], $row[actualtemp]]";
							break;
						case boilertemp:
							$data[] = "[$row[timestamp], $row[boilertemp]]";
							break;
						default:
							$data[] = "[$row[timestamp], $row[actualtemp], $row[avgtemp]]";
						}
			//		$data[] = "[$row[timestamp], $row[actualtemp], $row[avgtemp]]";
				}
                                $result->close();
                        }
                } while ($mysqli->next_result());


      $json = $_GET['callback'] . '('.json_encode($data).');';  //see note below regarding this line.                                   
      echo str_replace('"', '', $json);  //echos string with quotes removed.

	}
}
// display error message if is any
catch (Exception $e) {
        echo $e -> getMessage();
}

// Close $db connection
$mysqli->close();
?>
