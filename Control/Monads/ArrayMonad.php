<?php

	namespace Control\Monads
	{
		require_once(dirname(__FILE__).'/IMonad.php');
		require_once(dirname(__FILE__).'/AbstractMonad.php');

		class ArrayMonad extends AbstractMonad implements IMonad
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

				return self::makeFromArray(call_user_func_array('array_merge', array_map(function($m) { return $m->pierceMonad(); }, $this->array)));
			}

			public function mfmap($f)
			{
				return self::makeFromArray(array_map($f, $this->array));
			}

			private function __construct(array $m)
			{
				$this->array = $m;
			}
		}
	}

?>
