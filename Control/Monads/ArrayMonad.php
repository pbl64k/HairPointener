<?php

	namespace Control\Monads
	{
		require_once(dirname(__FILE__).'/IMonadPlus.php');
		require_once(dirname(__FILE__).'/AbstractMonad.php');

		class ArrayMonad extends AbstractMonad implements IMonadPlus
		{
			private $array;

			static public function makeFromArray(array $array)
			{
				return new self($array);
			}

			public function pierceMonad()
			{
				return $this->array;
			}

			public function mreturn($x)
			{
				return self::makeFromArray(array($x));
			}

			public function mjoin()
			{
				if (empty($this->array))
				{
					return self::makeFromArray(array());
				}

				return self::makeFromArray(call_user_func_array(
						'array_merge', array_map(function($m) {
						return $m->pierceMonad(); }, $this->array)));
			}

			public function mfmap($f)
			{
				return self::makeFromArray(array_map($f, $this->array));
			}

			/*
			public function mbind($f)
			{
				$array = array_map($f, $this->array);

				if (empty($array))
				{
					return self::makeFromArray(array());
				}

				return self::makeFromArray(call_user_func_array(
						'array_merge', array_map(function($m) {
						return $m->pierceMonad(); }, $array)));
			}
			*/

			public function mzero()
			{
				return self::makeFromArray(array());
			}

			public function mplus($m)
			{
				$monad = $this->mplusHelper($m);

				return self::makeFromArray(array_merge($this->pierceMonad(),
						$monad->pierceMonad()));
			}

			protected function mplusHelper(ArrayMonad $m)
			{
				return $m;
			}

			private function __construct(array $m)
			{
				$this->array = $m;
			}
		}
	}

?>
