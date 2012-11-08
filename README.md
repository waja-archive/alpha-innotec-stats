# Statistics and (Java-)Dashboard replacement for Alpha-InnoTec heatpumps

## Introduction

Heatpumps produced by [Alpha-InnoTec] (http://www.alpha-innotec.de/) should be actually equipped with a [Luxtronik 2.0] (https://www.google.com/search?q=alpha+innotec+luxtronik+2.0) module. This Managment Module can be connected with an ethernet network (see [brochure] (http://www.google.com/url?q=http://www.alpha-innotec.de/uploads/DE830523_210521_BA_Luxtronik_II_Fachhandwerker.pdf&ei=oqubUO_XJcOU0QWdy4DADw&sa=X&oi=unauthorizedredirect&ct=targetlink&ust=1352381098619768&usg=AFQjCNGqvyiJ0omzYVF8pbq0k_rdldUhDw) for qualified skilled worker, site 50-54).

Connected to your network, there are 2 ports open on the Linux system on the module. Port 23 (for telnet) and Port 8888 as control port. Login in via telnet is possible via user 'root' and empty password. For this project the port 8888 is the important part. Via this port we can communicate with the module. It should be possible to configure the module (and the heatpump), but for now I recommand to avoid that. Actually we just grab some condition and status values and store them into a MySQL database to get some nice graphs displayed.

![alt temperatures example] (https://holle.cyconet.org/public.php?service=files&token=c41c69868d0c4d4129a0ed54df13964f5ec17bfe&file=/Pictures/heating.png)
![alt dashboard example] (https://holle.cyconet.org/public.php?service=files&token=dc309a8e60267271cad7250542c7419c5e3d06ba&file=/Pictures/heating_dashboard.png)

## Installation
