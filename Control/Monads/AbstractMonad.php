<?php

	namespace Control\Monads
	{
		abstract class AbstractMonad
		{
			abstract public function mreturn($x);

			public function mbind($f)
			{
				return $this->mfmap($f)->mjoin();
			}

			public function mjoin()
			{
				return $this->mbind(function($x) { return $x; });
			}

			public function mfmap($f)
			{
				return $this->mbind(function(x) use($this, $f) { return $this->mreturn($f($x)); });
			}
		}
	}

?>
