<?php
namespace higherchen\ipwall;

class IPmatch {
	
	protected $expression;

	function __construct($expression = null) {
		if(!empty($expression))
		{
			$this->setExpression($expression);
		}
	}

	public function setExpression($expression)
	{
		$expression = strtolower(trim($expression));
		$expression = preg_replace('/\*+/', '*', $expression);

		$this->expression = $expression;
	}

	public function match($address)
	{
		$addr_chunks = explode('.', $address);
		$exp_chunks = preg_split("/\./", $this->expression);

		if (count($addr_chunks) !== count($exp_chunks)) {
			throw new \Exception('Expression and ip address do not contain the same amount of chunks');
		}

		foreach($exp_chunks as $id => $item)
		{
			if(strpos($item, '*') === false)
			{
				if($addr_chunks[$id] != $item)
				{
					return false;
				}
			}
			else
			{
				$item = str_replace('*', '[0-9]+', $item);
				if(!preg_match("/^{$item}$/", $addr_chunks[$id]))
				{
					return false;
				}
			}
		}
		return true;
	}
}