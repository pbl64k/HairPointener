<?php

	namespace Control\Monads\tests
	{
		require_once('PHPUnit/Framework/TestCase.php');

		require_once(dirname(__FILE__).'/../ArrayMonad.php');

		class ArrayMonadTest extends \PHPUnit_Framework_TestCase
		{
			public function provideArray()
			{
				return array(
						array(array()),
						array(array(0,)),
						array(array(1,)),
						array(array(1, 2, 3,)),
						array(array(1, 1, 2, 3, 5, 8, 13, 21,)),
						);
			}

			/**
			 * @test
			 * @dataProvider provideArray
			 */
			public function checkCreateAndJuggle(array $array)
			{
				$monad = \Control\Monads\ArrayMonad::makeFromArray($array);

				$this->assertEquals($array, $monad->pierceMonad());

				$this->checkMreturn($monad);

				$this->checkMfmap($monad);

				$this->checkMbind($monad);

				return $monad;
			}

			private function checkMreturn(\Control\Monads\ArrayMonad $monad)
			{
				$this->assertEquals(array($monad->pierceMonad()), $monad->mreturn($monad->pierceMonad())->pierceMonad());

				return $monad;
			}

			private function checkMfmap(\Control\Monads\ArrayMonad $monad)
			{
				$this->assertEquals(array_map(function($x) { return 2 * $x; }, $monad->pierceMonad()), $monad->mfmap(function($y) { return $y + $y; })->pierceMonad());

				return $monad;
			}

			private function checkMbind(\Control\Monads\ArrayMonad $monad)
			{
				$dup = array();

				foreach ($monad->pierceMonad() as $elt)
				{
					$dup[] = $elt;
					$dup[] = $elt;
				}

				$this->assertEquals($dup, $monad->mbind(function ($x) { return \Control\Monads\ArrayMonad::makeFromArray(array($x, $x)); })->pierceMonad());

				return $monad;
			}
		}
	}

?>
