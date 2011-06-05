<?php

	namespace Control\Monads
	{
		require_once(dirname(__FILE__).'/IMonadZero.php');

		interface IMonadPlus
		{
			public function mplus($m);
		}
	}

?>
