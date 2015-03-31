<?php

	require_once 'cidr.class.php';

echo "10.0.0.0/8: ".new CIDR("10.0.0.0/8")."\n";
echo "192.168.0.0/16: ".new CIDR("192.168.0.0/16")."\n";
echo "192.168.1.1/24: ".new CIDR("192.168.1.1/24")."\n";
echo "89.30.118.128/28: ".new CIDR("89.30.118.128/28")."\n\n";

$cidr1 = new CIDR("192.168.0.0/16"); $cidr2 = new CIDR("192.168.1.1/24");
echo "192.168.0.0/16 includes 192.168.1.1/24: ".var_export($cidr1->includeCIDR($cidr2),true)."\n";
$cidr1 = new CIDR("192.168.1.1/24"); $cidr2 = new CIDR("192.168.0.0/16");
echo "192.168.1.1/24 includes 192.168.0.0/16: ".var_export($cidr1->includeCIDR($cidr2),true)."\n";
$cidr1 = new CIDR("192.168.0.0/16"); $cidr2 = new CIDR("192.168.0.0/24");
echo "192.168.0.0/16 includes 192.168.0.0/24: ".var_export($cidr1->includeCIDR($cidr2),true)."\n\n";

$cidr1 = new CIDR("192.168.0.0/16");
echo "192.168.0.0/16 includes 192.168.1.1: ".var_export($cidr1->includeIP("192.168.1.1"),true)."\n";
$cidr1 = new CIDR("192.168.0.0/16");
echo "192.168.0.0/16 includes 10.0.0.1: ".var_export($cidr1->includeIP("10.0.0.1"),true)."\n";
