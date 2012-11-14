<?php
/*
Some kind of replacement of the shipped Java Dashboard
*/

//includes
include "includes/config.php";
include "includes/java_daten.php";

//Variablen
$sBuff = 0;
//$time1 = time();
//$filename = "test.tst";
$JavaWerte = 0; 
$refreshtime = 60; //sekunden
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head><title>Statistics Report</title>
<meta http-equiv="refresh" content="<?php echo $refreshtime; ?>" >
<link rel="stylesheet" href="wwc.css">
</head>
<body>

<?php
// connecten
$socket = socket_create(AF_INET, SOCK_STREAM,0);
$connect = socket_connect($socket, $IpWwc, $WwcJavaPort) || exit("socket_connect fehlgeschlagen"); 


if ($connect = 1)
	echo "<!--PASS: Verbunden mit WWC Java Console $IpWwc Port: $WwcJavaPort <br> -->";
else 
	echo "ERROR: Nicht verbunden mit WWC Java Console $IpWwc Port: $WwcJavaPort <br>";


$datum = date("d.m.Y",$_SERVER['REQUEST_TIME']);
$uhrzeit = date("H:i:s",$_SERVER['REQUEST_TIME']);
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
//printf('============================================================== <br>');

for ($i = 0; $i < $JavaWerte; ++$i)//vorwärts
{
socket_recv($socket,$InBuff[$i],4,MSG_WAITALL);  // Lesen, sollte 3004 zurückkommen
$daten_raw[$i] = implode(unpack('l',strrev($InBuff[$i])));
//printf('InBuff(%d): %d <br>',$i,$daten_raw[$i]);
}
//socket wieder schliessen 
socket_close($socket);
?>
<div id="quotes">
<ul><li>
<?php
// Werte anzeigen
printf('<h2>Temperaturen</h2>');
printf('<span class="dropt">%s<span>Vorlauf</span></span>: %.1f &#176C<br>',$java_dataset[10],$daten_raw[10]*0.1);
printf('<span class="dropt">%s<span>Ruecklauf</span></span>: %.1f &#176C<br>',$java_dataset[11],$daten_raw[11]*0.1);
printf('<span class="dropt">%s<span>Ruecklauf-Soll</span></span>: %.1f &#176C<br>',$java_dataset[12],$daten_raw[12]*0.1);
printf('<span class="dropt">%s<span>Ruecklauf extern</span></span>: %.1f &#176C<br>',$java_dataset[13],$daten_raw[13]*0.1);
printf('<span class="dropt">%s<span>Heissgas</span></span>: %.1f &#176C<br>',$java_dataset[14],$daten_raw[14]*0.1);
printf('<span class="dropt">%s<span>Aussen</span></span>: %.1f &#176C<br>',$java_dataset[15],$daten_raw[15]*0.1);
printf('<span class="dropt">%s<span>Aussen, Mittel</span></span>: %.1f &#176C<br>',$java_dataset[16],$daten_raw[16]*0.1);
printf('<span class="dropt">%s<span>Brauchwasser-Ist</span></span>: %.1f &#176C<br>',$java_dataset[17],$daten_raw[17]*0.1);
printf('<span class="dropt">%s<span>Brauchwasser-Soll</span></span>: %.1f &#176C<br>',$java_dataset[18],$daten_raw[18]*0.1);
printf('<!--');
printf('<span class="dropt">%s<span>Waermequelle-Eintritt</span></span>: %.1f &#176C<br>',$java_dataset[19],$daten_raw[19]*0.1);
printf('<span class="dropt">%s<span>Waermequelle-Austritt</span></span>: %.1f &#176C<br>',$java_dataset[20],$daten_raw[20]*0.1);
printf('%s: %.1f &#176C<br>',$java_dataset[21],$daten_raw[21]*0.1);
printf('<span class="dropt">%s<span>Vorlauf-Mischkreis-Soll</span></span>: %.1f &#176C<br>',$java_dataset[22],$daten_raw[22]*0.1);
print ('-->');
?>
</li>
<li>
<?php
printf('<h2>Eingaenge</h2>');
$i=29;
$text="Durchflussschalter"; 
printf('<span class="dropt">%s<span>%s</span></span>: ',$java_dataset[$i],$text); {if ($daten_raw[$i] == 1) printf('EIN<br>'); else printf('AUS<br>');} 
$i=30; 
$text="Brauchwasserthermostat"; 
printf('<span class="dropt">%s<span>%s</span></span>: ',$java_dataset[$i],$text); {if ($daten_raw[$i] == 1) printf('EIN (Brauchwasseranforderung)<br>'); else printf('AUS<br>');} 
$i=31; 
$text="Sperrzeit vom EVU"; 
printf('<span class="dropt">%s<span>%s</span></span>: ',$java_dataset[$i],$text); {if ($daten_raw[$i] == 1) printf('<span style="color:#008000;">EIN</span><br>'); else printf('<span style="color:#ff0000;">AUS (Sperrzeit)</span><br>');} 
$i=32; 
$text="Hochdruckpressostat"; 
printf('<span class="dropt">%s<span>%s</span></span>: ',$java_dataset[$i],$text); {if ($daten_raw[$i] == 1) printf('<span style="color:#ff0000;">EIN</span><br>'); else printf('<span style="color:#008000;">AUS (Druck in Ordnung)</span><br>');} 
$i=33; 
$text="Motorschutz"; 
printf('<span class="dropt">%s<span>%s</span></span>: ',$java_dataset[$i],$text); {if ($daten_raw[$i] == 1) printf('<span style="color:#008000;">EIN</span><br>'); else printf('<span style="color:#ff0000;">AUS</span><br>');} 
$i=34; 
$text="Niederdruckpressostat"; 
printf('<span class="dropt">%s<span>%s</span></span>: ',$java_dataset[$i],$text); {if ($daten_raw[$i] == 1) printf('<span style="color:#008000;">EIN</span><br>'); else printf('<span style="color:#ff0000;">AUS</span><br>');} 
$i=35; 
$text="Fremdanode"; 
printf('<span class="dropt">%s<span>%s</span></span>: ',$java_dataset[$i],$text); {if ($daten_raw[$i] == 1) printf('EIN<br>'); else printf('AUS<br>');} 
?>
</li>
<li>
<?php
printf('<h2>Ausgaenge</h2>');
$i=37;
$text="AV-Abtauventil";
printf('<span class="dropt">%s<span>%s</span></span>: ',$java_dataset[$i],$text); {if ($daten_raw[$i] == 1) printf('EIN<br>'); else printf('AUS<br>');}
$i=38;
$text="Brauchwasserumwaeltpumpe";
printf('<span class="dropt">%s<span>%s</span></span>: ',$java_dataset[$i],$text); {if ($daten_raw[$i] == 1) printf('EIN<br>'); else printf('AUS<br>');}
$i=39;
$text="Heizungsumwaelzpumpe";
printf('<span class="dropt">%s<span>%s</span></span>: ',$java_dataset[$i],$text); {if ($daten_raw[$i] == 1) printf('EIN<br>'); else printf('AUS<br>');}
$i=42;
$text="Ventilation";
printf('<span class="dropt">%s<span>%s</span></span>: ',$java_dataset[$i],$text); {if ($daten_raw[$i] == 1) printf('EIN<br>'); else printf('AUS<br>');}
$i=43;
$text="Ventilation Brunnen- oder Soleumwaelzpumpe ";
printf('<span class="dropt">%s<span>%s</span></span>: ',$java_dataset[$i],$text); {if ($daten_raw[$i] == 1) printf('EIN<br>'); else printf('AUS<br>');}
$i=44;
$text="Verdichter 1";
printf('<span class="dropt">%s<span>%s</span></span>: ',$java_dataset[$i],$text); {if ($daten_raw[$i] == 1) printf('EIN<br>'); else printf('AUS<br>');}
$i=46;
$text="Zirkulationspumpe";
printf('<span class="dropt">%s<span>%s</span></span>: ',$java_dataset[$i],$text); {if ($daten_raw[$i] == 1) printf('EIN<br>'); else printf('AUS<br>');}
$i=47;
$text="Zusatzumwaelzpumpe";
printf('<span class="dropt">%s<span>%s</span></span>: ',$java_dataset[$i],$text); {if ($daten_raw[$i] == 1) printf('EIN<br>'); else printf('AUS<br>');}
$i=48;
$text="Zweiter Waermeerzeuger 1";
printf('<span class="dropt">%s<span>%s</span></span>: ',$java_dataset[$i],$text); {if ($daten_raw[$i] == 1) printf('EIN<br>'); else printf('AUS<br>');}
$i=49;
$text="Zweiter Waermeerzeuger 2 - Sammelstoerung";
printf('<span class="dropt">%s<span>%s</span></span>: ',$java_dataset[$i],$text); {if ($daten_raw[$i] == 1) printf('EIN<br>'); else printf('AUS<br>');}
?>
</li></ul>
</div>
<div id="quotes">
<ul><li>
<?php
printf('<h2>Ablaufzeiten</h2>');
printf('<span class="dropt">%s<span>Waermepumpe laeuft seit</span></span>: %s <br>',$java_dataset[67],date("H:i:s",$daten_raw[67]-3600));
printf('<span class="dropt">%s<span>Zweite Waermepumpe laeuft seit</span></span>: %s <br>',$java_dataset[68],date("H:i:s",$daten_raw[68]-3600));
printf('<span class="dropt">%s<span>Netzeinschaltverzoegerung seit</span></span>: %s <br>',$java_dataset[70],date("H:i:s",$daten_raw[70]-3600));
printf('<span class="dropt">%s<span>Schaltspielsperre</span></span>: %s <br>',$java_dataset[71],date("H:i:s",$daten_raw[71]-3600));
printf('<span class="dropt">%s<span>Schaltspielsperre</span></span>: %s <br>',$java_dataset[72],date("H:i:s",$daten_raw[72]-3600));
printf('<span class="dropt">%s<span>Verdichter steht seit</span></span>: %s <br>',$java_dataset[73],date("H:i:s",$daten_raw[73]-3600));
printf('<span class="dropt">%s<span>HeizungsReglerMehr</span></span>: %s <br>',$java_dataset[74],date("H:i:s",$daten_raw[74]-3600));
printf('<span class="dropt">%s<span>HeizungsReglerWeniger</span></span>: %s <br>',$java_dataset[75],date("H:i:s",$daten_raw[75]-3600));
printf('<span class="dropt">%s<span>Brauchwassersperre seit</span></span>: %s <br>',$java_dataset[77],date("H:i:s",$daten_raw[77]-3600));
printf('<span class="dropt">%s<span>Abtauen</span></span>: %s <br>',$java_dataset[141],date("H:i:s",$daten_raw[141]-3600));
?>
</li>
<li>
<?php
printf('<h2>Betriebseinheiten</h2>');
printf('<span class="dropt">%s<span>Betriebszeit Verdichter</span></span>: %.2f h<br>',$java_dataset[56],$daten_raw[56]/3600);
printf('<span class="dropt">%s<span>Betriebszyklen Impuls Verdichter</span></span>: %d<br>',$java_dataset[57],$daten_raw[57]);
printf('<span class="dropt">%s<span>Betriebszeit zweiter Waermeerzeuger</span></span>: %.2f h<br>',$java_dataset[60],$daten_raw[60]/3600);
printf('<span class="dropt">%s<span>Betriebszeit Waermepumpe</span></span>: %.2f h<br>',$java_dataset[63],$daten_raw[63]/3600);
printf('<span class="dropt">%s<span>Betriebszeit Heizung</span></span>: %.2f h<br>',$java_dataset[64],$daten_raw[64]/3600);
printf('<span class="dropt">%s<span>Betriebszeit Brauchwasser</span></span>: %.2f h<br>',$java_dataset[65],$daten_raw[65]/3600);
?>
</li>
<li>
<?php
printf('<h2>Anlagenstatus</h2>');


switch ($daten_raw[119]) {
	case 0:
		$bezstand = "Heizbetrieb";
		break;
	case 1:
		$bezstand = "Keine Anforderung";
		break;
	case 2:
		$bezstand = "Netz-Einschaltverzoegerung";
		break;
	case 3:
		$bezstand = "SSP Zeit";
		break;
	case 4:
		$bezstand = "Sperrzeit";
		break;
	case 5:
		$bezstand = "Brauchwasser";
		break;
	case 6:
		$bezstand = "Estrich-Programm";
		break;
	case 7:
		$bezstand = "Abtauen";
		break;
	case 8:
		$bezstand = "Pumpenvorlauf";
		break;
	case 9:
		$bezstand = "Thermische Desinfektion";
		break;
	case 10:
		$bezstand = "Kuehlbetrieb";
		break;
	case 12:
		$bezstand = "Schwimmbad";
		break;
	case 13:
		$bezstand = "Heizen Ext.";
		break;
	case 14:
		$bezstand = "Brauchwasser Ext.";
		break;
	case 16:
		$bezstand = "Durchflussueberwachung";
		break;
	case 17:
		$bezstand = "ZWE Betrieb";
		break;
	default:
		$bezstand = "Unbekannter Zustand";
}
//printf('<span class="dropt">%s<span>Betriebszustand</span></span>: %s<br>',$java_dataset[119],$bezstand);
printf('<span class="dropt">%s<span>Betriebszustand</span></span><br>',$bezstand);
?>
</li></ul>
</div>
<div id="footer">
<ul>
<?php
printf('<!--');
if ($i >= 67 & $i <= 77) // AblaufZeiten
        {($daten_raw[$i] = date("H:i:s",$daten_raw[$i]));
          printf('(%d)%s : %s<br>',$i,$java_dataset[$i],$daten_raw[$i]);
        }


for ($i = 0; $i < $JavaWerte; ++$i)//vorwärts
{
if ($i >= 10 & $i <= 28) // Temperaturen
	{($daten_raw[$i] = $daten_raw[$i]*0.1);
	  printf('(%d)%s : %.1f &#176C<br>',$i,$java_dataset[$i],$daten_raw[$i]);
	}
	
if ($i >= 29 & $i <= 34) // Eingänge
{if ($daten_raw[$i] == 1)
	printf('(%d)%s : EIN<br>',$i,$java_dataset[$i]);
 else 
	printf('(%d)%s : AUS<br>',$i,$java_dataset[$i]);
}

if ($i == 35) // Ausgänge
	printf('(%d)%s : %.1f Volt<br>',$i,$java_dataset[$i],$daten_raw[$i]);
	
if ($i >= 36 & $i <= 55) // Ausgänge
{if ($daten_raw[$i] == 1)
	printf('(%d)%s : EIN<br>',$i,$java_dataset[$i]);
 else 
	printf('(%d)%s : AUS<br>',$i,$java_dataset[$i]);
}

if ($i == 56) // Zähler
	{($daten_raw[$i] = $daten_raw[$i]/3600);
	  printf('(%d)%s : %.2f h<br>',$i,$java_dataset[$i],$daten_raw[$i]);
	}
if ($i == 57) // Zähler
	{($daten_raw[$i] = $daten_raw[$i]);
	  printf('(%d)%s : %d<br>',$i,$java_dataset[$i],$daten_raw[$i]);
	}
if ($i == 58) // Zähler
	{($daten_raw[$i] = $daten_raw[$i]/3600);
	  printf('(%d)%s : %.2f h<br>',$i,$java_dataset[$i],$daten_raw[$i]);
	}
if ($i == 59) // Zähler
	{($daten_raw[$i] = $daten_raw[$i]);
	  printf('(%d)%s : %d<br>',$i,$java_dataset[$i],$daten_raw[$i]);
	}

if ($i >= 60 & $i <= 66) // Zähler
	{($daten_raw[$i] = $daten_raw[$i]/3600);
	  printf('(%d)%s : %d h<br>',$i,$java_dataset[$i],$daten_raw[$i]);
	}

if ($i >= 67 & $i <= 77) // AblaufZeiten
	{($daten_raw[$i] = date("H:i:s",$daten_raw[$i]));
	  printf('(%d)%s : %s<br>',$i,$java_dataset[$i],$daten_raw[$i]);
	}

if ($i >= 78 & $i <= 158) // AblaufZeiten
	{
	  printf('(%d)%s : %d<br>',$i,$java_dataset[$i],$daten_raw[$i]);
	}	
}
printf('-->');
//$time2 = time();
//print( "Auslesedauer: " . ($time2 - $time1) . " secs\n");

//printf('========================== <br>');
printf('Ausleszeit: %s - %s Uhr <br>',$datum,$uhrzeit);
printf('<!-- Refresh alle '.$refreshtime.'s !<br> -->');
//printf('========================== <br>');
echo 'Maybe you want compare the stats with <a href="http://www.snakepitnetwork.de/wwc_2.php" target="new">http://www.snakepitnetwork.de/wwc_2.php</a> ';
echo '</ul></div></body></html>';
?>
