# Statistics and (Java-)Dashboard replacement for Alpha-InnoTec heatpumps

## Introduction

Heatpumps produced by [Alpha-InnoTec] (http://www.alpha-innotec.de/) should be actually equipped with a [Luxtronik 2.0] (https://www.google.com/search?q=alpha+innotec+luxtronik+2.0) module. This Managment Module can be connected with an ethernet network (see [brochure] (http://www.google.com/url?q=http://www.alpha-innotec.de/uploads/DE830523_210521_BA_Luxtronik_II_Fachhandwerker.pdf&ei=oqubUO_XJcOU0QWdy4DADw&sa=X&oi=unauthorizedredirect&ct=targetlink&ust=1352381098619768&usg=AFQjCNGqvyiJ0omzYVF8pbq0k_rdldUhDw) for qualified skilled worker, site 50-54).

Connected to your network, there are 2 ports open on the Linux system on the module. Port 23 (for telnet) and Port 8888 as control port. Login in via telnet is possible via user 'root' and empty password. For this project the port 8888 is the important part. Via this port we can communicate with the module. It should be possible to configure the module (and the heatpump), but for now I recommand to avoid that. Actually we just grab some condition and status values and store them into a MySQL database to get some nice graphs displayed.

## Installation

Download the package into a DocumentRoot of you PHP enabled webserver and unpack it there.

Installing the required software on Debian/Ubuntu:

	aptitude install libapache2-mod-php5
	service apache2 restart

Download the project (here to /var/www/heating)

	git clone https://github.com/waja/alpha-innotec-stats.git /var/www/heating

### Dashboard

![alt dashboard example] (https://github.com/downloads/waja/alpha-innotec-stats/heating_dashboard.png)

Just modify includes/config.php to your environment:

	// connection to heatpump management module
	$IpWwc = "192.168.178.252";
	$WwcJavaPort = "8888";

Point you browser to http://&lt;yourserver&gt;/&lt;path&gt;/wwc.php!

### Statistics

![alt temperatures example] (https://github.com/downloads/waja/alpha-innotec-stats/heating.png)

For storing data, we need a database.

	aptitude install mysql-server php5-mysql

Now we need a database and an user for accessing the database. In the shipped examples we use:

	$myhost="localhost";
	$myuser="heating";
	$mypasswd="f00b4r";
	$mydatabase="heating";

Maybe you will adjust these, or you copy them to includes/

	cp examples/sql_cred.php.example includes/sql_cred.php

Lets create a database:

	mysql -p < examples/heating_structure.sql

To collect the data, we need to run a pooler (and install the php5 cli binary)

	aptitude install php5-cli

Please add the following via 'crontab -e', but try avoiding this as root

	* * * * * php -q /var/www/heating/poller.php

Feel free to adjust the frequency running the poller script to your needs.
