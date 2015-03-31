<?php

	$res = shell_exec("whois -h whois.radb.net '!gAS15169'|grep '/'");
	$raw = explode(' ', trim($res));

	$ranges = [];
	foreach($raw as $line) {
		$ranges[$line] = $line;
	}	

	echo ':foreach entry in=[/ip firewall address-list find where list=dst-google] do={/ip firewall address-list remove $entry;};';

        foreach($ranges as $prefix) {
                echo "/ip firewall address-list add address=$prefix list=dst-google;";
        }

