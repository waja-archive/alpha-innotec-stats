<?php
/*
Collect temperatures and store them in database (poller)
*/

//includes
include "includes/config.php";
include "includes/sql_cred.php";

//Variablen
$sBuff = 0;
$JavaWerte = 0; 

// collecting data

// connecten
$socket = socket_create(AF_INET, SOCK_STREAM,0);
$connect = socket_connect($socket, $IpWwc, $WwcJavaPort) || exit("socket_connect fehlgeschlagen"); 


if ($connect != 1)
	echo "ERROR: Nicht verbunden mit WWC Java Console $IpWwc Port: $WwcJavaPort\n";
else
//	echo "PASS: Verbunden mit WWC Java Console $IpWwc Port: $WwcJavaPort\n";


//---------------------------------------------------------------------------	

// Daten holen
$msg = pack('N*',3004);
//printf('msg:%s <br>',$msg);
$send=socket_write($socket, $msg, 4); //3004 senden
//printf('Bytes send:%d <br>',$send);

$msg = pack('N*',0);
//printf('msg:%s <br>',ord($msg));
$send=socket_write($socket, $msg, 4); //0 senden 
//printf('Bytes send:%d <br>',$send);


socket_recv($socket,$Test,4,MSG_WAITALL);  // Lesen, sollte 3004 zurückkommen
//$Test = unpack('N*',$Test);
//printf('read:%s <br>',implode($Test));

socket_recv($socket,$Test,4,MSG_WAITALL); // Status
// $Test = unpack('N*',$Test);
// printf('Status:%s <br>',implode($Test));

socket_recv($socket,$Test,4,MSG_WAITALL); // Länge der nachfolgenden Werte
$Test = unpack('N*',$Test);
//printf('L&auml;nge der nachfolgenden Werte:%s <br>',implode($Test));

$JavaWerte = implode($Test);
for ($i = 0; $i < $JavaWerte; ++$i)//vorwärts
{
socket_recv($socket,$InBuff[$i],4,MSG_WAITALL);  // Lesen, sollte 3004 zurückkommen
//$daten_raw[$i] = implode(unpack('N*',$InBuff[$i]));
$daten_raw[$i] = implode(unpack('l',strrev($InBuff[$i])));
//printf('InBuff(%d): %d <br>',$i,$daten_raw[$i]);
}
//socket wieder schliessen 
socket_close($socket);

// writing data

// MySQL

// Connect to the database. (host,username,password,database)
$mysqli = mysqli_connect($myhost, $myuser, $mypasswd, $mydatabase);
// Check for errors connecting to database.
if (mysqli_connect_errno()) {
	die('Unable to connect to database. '.$mysqli -> connect_error);
}
// temperature data
try {
	// All queries and commands go here.
	$query = "INSERT into $mytemptable VALUES ('$daten_raw[134]', '$daten_raw[10]', '$daten_raw[11]', '$daten_raw[12]', '$daten_raw[13]', '$daten_raw[14]', '$daten_raw[15]', '$daten_raw[16]', '$daten_raw[17]', '$daten_raw[18]', '$daten_raw[19]', '$daten_raw[20]', '$daten_raw[21]', '$daten_raw[22]', '$daten_raw[23]', '$daten_raw[24]', '$daten_raw[25]', '$daten_raw[26]', '$daten_raw[27]', '$daten_raw[28]');";
	if(!$mysqli -> query($query)) {
		throw new Exception($mysqli -> error);
	}
}
catch (Exception $e) {
	echo $e -> getMessage();
}
// timing data
try {
        // All queries and commands go here.
	$query = "INSERT into $mytimetable VALUES ('$daten_raw[134]', '$daten_raw[67]', '$daten_raw[68]', '$daten_raw[70]', '$daten_raw[71]', '$daten_raw[72]', '$daten_raw[73]', '$daten_raw[74]', '$daten_raw[75]', '$daten_raw[77]', '$daten_raw[141]');";
        if(!$mysqli -> query($query)) {
                throw new Exception($mysqli -> error);
        }
}
catch (Exception $e) {
        echo $e -> getMessage();
}
// operation data
try {
        // All queries and commands go here.
        $query = "INSERT into $myoptable VALUES ('$daten_raw[134]', '$daten_raw[56]', '$daten_raw[57]', '$daten_raw[60]', '$daten_raw[63]', '$daten_raw[64]', '$daten_raw[65]');";
        if(!$mysqli -> query($query)) {
                throw new Exception($mysqli -> error);
        }
}
catch (Exception $e) {
        echo $e -> getMessage();
}

// Close $db connection
$mysqli->close();

?>
