<?php

    require_once 'inc/as-tables.php';
    require_once 'inc/cidr.class.php';

    class AddressList {

        private $id;
        private $list_name;

        public function __construct($id, $list_name=null) {
            if (empty($list_name))
                $list_name = "dst-".$id;

            $this->id = $id;
            $this->list_name = $list_name;

            switch($id) {
                case "aws":
                    $list = $this->getAWSList();
                    break;
                case "facebook":
                case "google":
		case "bso":
		case "proceau":
		case "akamai":
		case "netflix":
                    $list = $this->getListByASN();
                    break;
                default:
                    throw new Exception("Invalid id");
            }

            echo $this->decoreList($list);
        }

        private function decoreList($ranges) {
            //Clean
            $res = ":foreach entry in=[/ip firewall address-list find where list={$this->list_name}] do={/ip firewall address-list remove \$entry;}; ";

            //Adding new ranges
            foreach($ranges as $range) {
                $range = $range->getCIDR();
                $res.= "/ip firewall address-list add address={$range} list={$this->list_name}; ";
            }

            return $res;
        }

        private function getAWSList() {
            $url = "https://ip-ranges.amazonaws.com/ip-ranges.json";
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true
            ]);

            $res = curl_exec($ch);
            $raw = json_decode($res);
            $ranges = [];
            foreach($raw->prefixes as $line) {
                $ranges[$line->ip_prefix] = $line->ip_prefix;
            }

            $cidrs = [];
            foreach($ranges as $range) {
                $cidrs[] = new CIDR($range);
            }

            $ranges = static::reduce($cidrs);

            return $ranges;
        }

        private function getListByASN() {
	    global $AS_TABLES;
	    $asn = $AS_TABLES[$this->id]["number"];
            $res = shell_exec("whois -h whois.radb.net '!gAS$asn'|grep '/'");
            $raw = explode(' ', trim($res));

            $ranges = [];
            foreach($raw as $line) {
                $ranges[$line] = $line;
            }

            $cidrs = [];
            foreach($ranges as $range) {
                $cidrs[] = new CIDR($range);
            }

            $ranges = static::reduce($cidrs);

            return $ranges;
        }

        public static function reduce (array $ranges) {
            $pairs = [];
            $includes = [];
            $includes2 = [];
            for ($i = 0; $i < count($ranges); $i++) {
                for ($j = 0; $j < count($ranges); $j++) {
                    if ($i != $j) {
                        $pairs[] = ["left" => $i, "right" => $j];
                    }
                }
                $includes[$i] = [];
                $includes2[$i] = [];
            }

            foreach($pairs as $pair) {
                $left = $ranges[$pair["left"]];
                $right = $ranges[$pair["right"]];

                if ($left->includeCIDR($right)) {
                    $includes[$pair["left"]][$pair["right"]] = $pair["right"];
                    $includes2[$pair["left"]][$pair["right"]] = $pair["right"];
                } else if ($right->includeCIDR($left)) {
                    $includes[$pair["right"]][$pair["left"]] = $pair["left"];
                    $includes2[$pair["right"]][$pair["left"]] = $pair["left"];
                }
            }

            $res = [];
            foreach($includes as $inc) {
                foreach($inc as $i) {
                    unset($includes2[$i]);
                }
            }
            foreach($includes2 as $key => $inc) {
                $res[] = $ranges[$key];
            }

            return $res;
        }

    }
