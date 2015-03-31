<?php

	class CIDR {
		private $network;
		private $mask;
		private $last_ip;

		private $first_ip_long;
		private $last_ip_long;

		public function __construct($cidrOrNetwork, $mask=null) {
			if (strpos($cidrOrNetwork, '/') !== false) {
				$tokens = explode('/', $cidrOrNetwork);

				$network = ip2long($tokens[0]);
				$mask = $tokens[1];

				if (!is_numeric($mask) || $mask < 0 || $mask > 32)
					throw new Exception("Invalid Mask");

				if ($network === false)
					throw new Exception("Invalid Network");

				$ip_mask = ~((1 << (32 - $mask)) - 1);
				$network = $network & $ip_mask;

				$this->mask = $tokens[1];
				$this->first_ip_long = $network;
				$this->last_ip_long = $network + pow(2, (32 - $mask)) -1;
			}
		}

		public function includeCIDR(CIDR $compare_to) {
			return $this->first_ip_long <= $compare_to->first_ip_long && $compare_to->last_ip_long <= $this->last_ip_long;
		}

		public function getCIDR() {
			return long2ip($this->first_ip_long)."/".$this->mask;
		}

		public function includeIP($ip) {
			$ip = ip2long($ip);
			if ($ip === false)
				throw new Exception("Invalid IP");

			return $this->first_ip_long <= $ip && $ip <= $this->last_ip_long;
		}

		public function __toString() {
			return $this->getCIDR()." (".long2ip($this->first_ip_long)."-".long2ip($this->last_ip_long).")";
		}
	}


