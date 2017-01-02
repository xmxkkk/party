<?php

namespace Home\Behavior;

class LoginBehavior{
	public function run(&$param){echo 222;
		print_r($param);
		var_dump($param);
	}
}