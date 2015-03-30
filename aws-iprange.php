<?php

	$url = "https://ip-ranges.amazonaws.com/ip-ranges.json";
	$ch = curl_init();
	curl_setopt_array($ch, [
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
	]);

	$res = curl_exec($ch);
	$raw = json_decode($res);
	$ranges = [];
	foreach($raw->prefixes as $line) {
		$ranges[$line->ip_prefix] = $line->ip_prefix;
	}

	echo ':foreach entry in=[/ip firewall address-list find where list=dst-amazonaws] do={
		/ip firewall address-list remove $entry;
	};';

	foreach($ranges as $prefix) {
		echo "/ip firewall address-list add address=$prefix list=dst-amazonaws;";
	}
