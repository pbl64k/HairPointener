<?php

	namespace Control\Monads
	{
		interface IMonad
		{
			public function mreturn($x);
			public function mbind($f);
			public function mjoin();
			public function mfmap($f);
		}
	}

?>
