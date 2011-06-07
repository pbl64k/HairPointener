<?php

	namespace FP\tests
	{
		require_once('PHPUnit/Framework/TestCase.php');

		require_once(dirname(__FILE__).'/../Lude.php');

		class LudeTest extends \PHPUnit_Framework_TestCase
		{
			public function provideInt()
			{
				return array(
						array(rand(0, 1023)),
						array(rand(0, 1023)),
						array(rand(0, 1023)),
						array(rand(0, 1023)),
						array(rand(0, 1023)),
						);
			}

			/**
			 * @test
			 * @dataProvider provideInt
			 */
			public function checkSimpleCombinators($int)
			{
				$this->assertEquals($int, \FP\f::ap(\FP\f::id(), $int));

				$this->assertEquals(($int + 1) * 2,
						\FP\f::ap(\FP\f::ap(\FP\f::ap(\FP\f::compose(),
						function($x) { return $x + 2; }), function($x)
						{ return $x + $x; }), $int));

				return $int;
			}
		}
	}

?>
