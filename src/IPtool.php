<?php
namespace higherchen\ipwall;

class IPtool {

	public static function validate()
	{
		return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
	}

	public static function get_ip($single = FALSE)
	{
		$keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR');
		$ip = '';
		foreach($keys as $k)
		{
			if(isset($_SERVER[$k]))
			{
				$ip = $_SERVER[$k];
				break;
			}
		}
		if(FALSE !== strpos($ip, ',') && $single)
		{
			$ip = reset(explode(',', $ip));
		}
		return trim($ip);
	}

	public static function get_client_ip()
	{
		// IP in http header (can be modified)
		return $_SERVER['HTTP_CLIENT_IP'];
	}

	public static function get_remote_ip()
	{
		// last IP (can not be modified)
		return $_SERVER['REMOTE_ADDR'];
	}

	public static function get_x_forwarded_ip()
	{
		// Maybe a lot of ips
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
}