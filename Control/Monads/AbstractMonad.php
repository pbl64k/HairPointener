<?php

	namespace Control\Monads
	{
		require_once(dirname(__FILE__).'/../../FP/Lude.php');

		abstract class AbstractMonad
		{
			public function mbind($f)
			{
				return $this->mfmap($f)->mjoin();
			}

			public function mjoin()
			{
				return $this->mbind(\FP\f::id());
			}

			public function mfmap($f)
			{
				$m = $this;

				return $this->mbind(\FP\f::ap(\FP\f::ap(\FP\f::compose(),
						function($x) use($m) { return $m->mreturn($x); }),
						$f));
			}
		}
	}

?>
