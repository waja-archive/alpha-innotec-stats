<?php
/*
Display the collected (and stored) data by the poller via ycharts
*/

//includes
include "includes/config.php";
// database config
include "includes/sql_cred.php";
// database query
switch ($_GET["context"]) {
	case timing:
		$query = "SELECT `timestamp`, `67`, `68`, `70`, `71`, `72`, `73`, `74`, `75`, `77`, `141` FROM `$mytimetable`WHERE timestamp > ((SELECT UNIX_TIMESTAMP()) - $timeframe)";
		break;
	case operation:
	case impulse:
		$query = "SELECT `timestamp`, `56`, `57`, `60`, `63`, `64`, `65` FROM `$myoptable`WHERE timestamp > ((SELECT UNIX_TIMESTAMP()) - $timeframe)";
		break;
	default:
		$query = "SELECT `timestamp`, `10`, `11`, `12`, `13`, `14`, `15`, `16`, `17`, `18`, `19`, `20`, `21`, `22`, `23`, `24`, `25`, `26`, `27`, `28` FROM `$mytemptable`WHERE timestamp > ((SELECT UNIX_TIMESTAMP()) - $timeframe)";
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head><title>Statistics Report</title>
<meta http-equiv="refresh" content="<?php echo $refreshtime; ?>" >
<link rel="stylesheet" href="wwc.css">
<script src="<?php echo $YCURL; ?>/yui/yui-min.js"></script>
</head>
<body>
<div class="heating">
<div id="mychart">
<style scoped>
#mychart {
    margin:10px 10px 10px 10px;
    width:90%;
    max-width: 1000px;
    height:400px;
}
</style>
</div>
<script type="text/javascript">

(function() {
    YUI().use('charts-legend', function (Y) 
    { 
        var myDataValues = [ 

<?php
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
		// get exeption
                throw new Exception($mysqli -> error);
        } else {
		do {
			// get results
			if ($result = $mysqli->use_result()) {
				while ($row = $result->fetch_array(MYSQLI_ASSOC)) { // fetch_row()) {
					switch ($_GET["context"]) {
						case timing;
							printf("\t\t{date:\"%s\", Waermepumpe:\"%s\", ZweiteWaermequelle:\"%s\", Netzeinschaltverzoegerung:\"%s\", SchaltspielsperreAus:\"%s\", SchaltspielsperreEin:\"%s\", VerdichterSteht:\"%s\", HeizungsReglerMehr:\"%s\", HeizungsReglerWeniger:\"%s\", Brauchwassersperre:\"%s\", Abtauen:\"%s\"},\n", ($row['timestamp']*1000), ($row["67"]/60), ($row["68"]/60), ($row["70"]/60), ($row["71"]/60), ($row["72"]/60), ($row["73"]/60), ($row["74"]/60), ($row["75"]/60), ($row["77"]/60), ($row["141"]/60));
							break;
						case impulse;
							printf("\t\t{date:\"%s\", ImpulseVerdichter:\"%s\"},\n", ($row['timestamp']*1000), ($row["57"]));
							break;
						case operation;
							printf("\t\t{date:\"%s\", BetriebszeitVerdichter:\"%s\", BetriebszeitZweiterWaermeerzeuger:\"%s\", BetriebszeitWaermepumpe:\"%s\", BetriebszeitHeizung:\"%s\", BetriebszeitBrauchwasser:\"%s\"},\n", ($row['timestamp']*1000), ($row["56"]/3600), ($row["60"]/3600), ($row["63"]/3600), ($row["64"]/3600), ($row["65"]/3600));
							break;
						case temperature:
							printf("\t\t{date:\"%s\", Aussentemperatur:\"%s\", Mittelwerttemperatur:\"%s\", Brauchwasser:\"%s\", Vorlauf:\"%s\", Ruecklauf:\"%s\", RuecklaufSoll:\"%s\", RuecklaufExtern:\"%s\", Heissgas:\"%s\", BrauchwasserSoll:\"%s\"},\n", ($row['timestamp']*1000), ($row["15"]*0.1), ($row["16"]*0.1), ($row["17"]*0.1), ($row["10"]*0.1), ($row["11"]*0.1), ($row["12"]*0.1), ($row["13"]*0.1), ($row["14"]*0.1), ($row["18"]*0.1));
							break;
						default:
							printf("\t\t{date:\"%s\", Aussentemperatur:\"%s\", Mittelwerttemperatur:\"%s\"},\n", ($row['timestamp']*1000), ($row["15"]*0.1), ($row["16"]*0.1));
					}
				}
				$result->close();
			}
		} while ($mysqli->next_result());		
	}
}
// display error message if is any
catch (Exception $e) {
        echo $e -> getMessage();
}

// Close $db connection
$mysqli->close();
?>

        ];
    //style the series
    var myStyleDef = {
        series: {
<?php
switch ($_GET["context"]) {
        case timing;
		$series = array("Waermepumpe", "ZweiteWaermequelle", "Netzeinschaltverzoegerung", "SchaltspielsperreAus","SchaltspielsperreEin", "VerdichterSteht", "HeizungsReglerMehr","HeizungsReglerWeniger", "Brauchwassersperre", "Abtauen");
                break;
	case impulse;
		$series = array("ImpulseVerdichter");
		break;
        case operation;
		$series = array("BetriebszeitVerdichter", "BetriebszeitZweiterWaermeerzeuger", "BetriebszeitWaermepumpe", "BetriebszeitHeizung", "BetriebszeitBrauchwasser");
                break;
        case temperature:
		$series = array("Aussentemperatur", "Mittelwerttemperatur", "Brauchwasser", "Vorlauf", "Ruecklauf", "RuecklaufSoll", "RuecklaufExtern", "Heissgas", "BrauchwasserSoll");
                break;
        default:
		$series = array("Aussentemperatur", "Mittelwerttemperatur");
}
for($i=0;$i<count($series);$i++){ 
	printf("\t    %s: {line: {weight: 2,},},\n", $series[$i]);
}
?>
        },
    };
    // axes
    var myAxesDef = {
	dateRange:{
	    keys:["date"],
	    position:"bottom",
	    type:"time",
	    labelFormat: "%d.%m %k:%M:%S",
	    styles: {
            	majorTicks:{
            	    display: "none",
            	},
                label: {
                    rotation: -45,
		    margin:{top:5},
                },
	    },
	},
	values:{
<?php
printf("\t    keys:[");
for($i=0;$i<count($series);$i++){ 
        printf("\"%s\", ", $series[$i]);
}
printf("],");
?>	    
	    type:"numeric",
	},
    };
    var mychart = new Y.Chart({
	axes: myAxesDef,
	styles: myStyleDef,
	categoryKey:"date", 
	categoryType:"time",
	categoryaxisname:"Zeit",
	dataProvider:myDataValues,
	horizontalGridlines:true,
	showMarkers: false,
	alwaysShowZero: false, 
	interactionType:"planar",
        legend: {
            position: "right",
            width: 300,
            height: 300,
            styles: {
                hAlign: "center",
                hSpacing: 4
            },
        },
	render:"#mychart",
	});
	mychart.set("styles.line.weight", 1);
    });
})();
</script>

</div>
<?php 
echo '</body></html>';
?>
