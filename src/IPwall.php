<?php
namespace higherchen\ipwall;

class IPwall {

	protected $groups = array();

	function __construct($iniPath = "")
	{
		if(empty($iniPath))
		{
			$iniPath = dirname(__FILE__)."/config.ini";
		}
		$this->groups = parse_ini_file($iniPath, TRUE);
	}

	public function handle($group_name, $ip = '')
	{
		$groups = $this->groups;
		if(isset($groups[$group_name]))
		{
			// 如果在配置文件
			$group		=	$groups[$group_name];
			$allowed	=	explode(',', $group['ipAllowed']);
			$denied		=	explode(',', $group['ipDenied']);
			$match		=	new IPmatch();
			if(empty($ip))
			{
				$ip = IPtool::get_remote_ip();
			}
			foreach($allowed as $item)
			{
				if(empty($item))
					continue;
				$match->setExpression($item);
				if($match->match($ip))
				{
					return TRUE;
				}
			}
			foreach($denied as $item)
			{
				$match->setExpression($item);
				if($match->match($ip))
				{
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	public function add($request_uri, $ip_allowed, $ip_denied)
	{
		$groups = $this->groups;
		
		if(!isset($groups[$request_uri]))
		{
			if(!empty($ip_allowed))
			{
				$groups[$request_uri]['ipAllowed'] = is_array($ip_allowed) ? implode(',', $ip_allowed) : $ip_allowed;
			}
			if(!empty($ip_denied))
			{
				$groups[$request_uri]['ipDenied'] = is_array($ip_denied) ? implode(',', $ip_denied) : $ip_denied;
			}
			$this->groups = $groups;
		}

		return $this;
	}
}
