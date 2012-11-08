<?php
/*
Small script displaying outside temperatures
*/

//includes
include "includes/config.php";
include "includes/java_daten.php";

//Variablen
$sBuff = 0;
//$time1 = time();
$JavaWerte = 0; 
$refreshtime = 10; //sekunden

echo '<meta http-equiv="refresh" content="'.$refreshtime.'" >';

// connecten
$socket = socket_create(AF_INET, SOCK_STREAM,0);
$connect = socket_connect($socket, $IpWwc, $WwcJavaPort) || exit("socket_connect fehlgeschlagen"); 


if ($connect = 1)
	echo "<!-- PASS: Verbunden mit WWC<br> -->";
else 
	echo "ERROR: Nicht verbunden mit WWC<br>";


$datum = date("d.m.Y",$_SERVER['REQUEST_TIME']);
$uhrzeit = date("H:i:s",$_SERVER['REQUEST_TIME']);
printf('========================== <br>');
printf('Ausleszeit: %s - %s Uhr <br>',$datum,$uhrzeit);
printf('<!-- Refresh alle '.$refreshtime.'s !<br> -->');
printf('========================== <br>');
//---------------------------------------------------------------------------	

// Daten holen
$msg = pack('N*',3004);
$send=socket_write($socket, $msg, 4); //3004 senden


$msg = pack('N*',0);
$send=socket_write($socket, $msg, 4); //0 senden 


socket_recv($socket,$Test,4,MSG_WAITALL);  // Lesen, sollte 3004 zurückkommen
$Test = unpack('N*',$Test);

socket_recv($socket,$Test,4,MSG_WAITALL); // Status
$Test = unpack('N*',$Test);
//printf('Status:%s <br>',implode($Test));

socket_recv($socket,$Test,4,MSG_WAITALL); // Länge der nachfolgenden Werte
$Test = unpack('N*',$Test);
//printf('L&auml;nge der nachfolgenden Werte:%s <br>',implode($Test));

$JavaWerte = implode($Test);

for ($i = 0; $i < $JavaWerte; ++$i)//vorwärts
{
socket_recv($socket,$InBuff[$i],4,MSG_WAITALL);  // Lesen, sollte 3004 zurückkommen
$daten_raw[$i] = implode(unpack('N*',$InBuff[$i]));
}
//socket wieder schliessen 
socket_close($socket);

// Werte anzeigen
printf('Temperatur: %.1f &#176C<br>',$daten_raw[15]*0.1);
printf('Mitteltemperatur: %.1f &#176C<br>',$daten_raw[16]*0.1);
//$time2 = time();
//print( "Auslesedauer: " . ($time2 - $time1) . " secs\n");

?>
