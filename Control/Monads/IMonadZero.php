<?php

	namespace Control\Monads
	{
		require_once(dirname(__FILE__).'/IMonad.php');

		interface IMonadZero extends IMonad
		{
			public function mzero();
		}
	}

?>
