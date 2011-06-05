<?php

	namespace Data\Graphs\tests
	{
		require_once('PHPUnit/Framework/TestCase.php');

		require_once(dirname(__FILE__).'/../../../Control/Monads/ArrayMonad.php');

		require_once(dirname(__FILE__).'/../SimpleVertex.php');
		require_once(dirname(__FILE__).'/../AcyclicDigraph.php');

		class AcyclicDigraphTest extends \PHPUnit_Framework_TestCase
		{
			const RANDOM_ARC_TEST_NUMBER = 100;

			public function provideGraph()
			{
				return array(
						array(
								array(
										'ROOT1' => array(
												'ELT1-1' => array(),
												'ELT1-2' => array(
														'ELT1-2-1' => array(),
														'ELT1-2-2' => array(),
														),
												'ELT1-3' => array(),
												),
										'ROOT2' => array(),
										'ROOT3' => array(
												'ELT3-1' => array(
														'ELT3-1-1' => array(),
														),
												'ELT3-2' => array(
														'ELT3-2-1' => array(),
														'ELT3-2-2' => array(
																'ELT3-2-2-1' => array(),
																),
														'ELT3-2-3' => array(),
														),
												),
										),
								),
						);
			}

			/**
			 * @test
			 * @dataProvider provideGraph
			 */
			public function checkCreateAndJuggle(array $structure)
			{
				$graph = \Data\Graphs\AcyclicDigraph::make();

				$this->addVertices($graph, \Data\Graphs\SimpleVertex::make(''), \Data\Graphs\SimpleVertex::make(''), $structure);

				$this->assertEquals(count($structure), count($graph->getOrphanTags()->pierceMonad()));

				$allTags = ($graph->getOrphanTags()->mbind(
						function($tag) use($graph)
						{
								return $graph->getDescendantTagsByTag($tag)->mplus(\Control\Monads\ArrayMonad::makeFromArray(array($tag)));
						}
						)->pierceMonad());

				for ($i = 0; $i != self::RANDOM_ARC_TEST_NUMBER; ++$i)
				{
					$this->checkPair($graph, $allTags[array_rand($allTags)], $allTags[array_rand($allTags)]);
				}
			}

			private function addVertices(\Data\Graphs\AcyclicDigraph $graph, \Data\Graphs\SimpleVertex $ancestor, \Data\Graphs\SimpleVertex $parent, array $structure)
			{
				foreach ($structure as $tag => $descendants)
				{
					$vertex = \Data\Graphs\SimpleVertex::make($tag);

					$this->assertFalse($graph->existsVertexByTag($tag));

					$graph->addVertex($vertex);

					$this->assertTrue($graph->existsVertexByTag($tag));

					$vertexInGraph = $graph->getVertexByTag($tag);

					$this->assertEquals($tag, $vertexInGraph->getTag());

					$this->assertTrue($graph->isNahproByTag($vertexInGraph->getTag()));
					$this->assertTrue($graph->isOrphanByTag($vertexInGraph->getTag()));
					$this->assertEquals(0, $graph->getIndegreeByTag($vertexInGraph->getTag()));
					$this->assertEquals(0, $graph->getOutdegreeByTag($vertexInGraph->getTag()));

					if ($parent->getTag())
					{
						$graph->addArcByTags($vertex->getTag(), $parent->getTag());

						$this->assertTrue($graph->isConnectedToByTags($vertexInGraph->getTag(), $parent->getTag()));
						$this->assertTrue($graph->isConnectedToByTags($vertexInGraph->getTag(), $ancestor->getTag()));
						$this->assertFalse($graph->isConnectedToByTags($parent->getTag(), $vertexInGraph->getTag()));
						$this->assertFalse($graph->isConnectedToByTags($ancestor->getTag(), $vertexInGraph->getTag()));

						$this->assertTrue($graph->isNahproByTag($vertexInGraph->getTag()));
						$this->assertFalse($graph->isOrphanByTag($vertexInGraph->getTag()));
						$this->assertEquals(0, $graph->getIndegreeByTag($vertexInGraph->getTag()));
						$this->assertEquals(1, $graph->getOutdegreeByTag($vertexInGraph->getTag()));

						$this->assertFalse($graph->isNahproByTag($parent->getTag()));
						$this->assertGreaterThan(0, $graph->getIndegreeByTag($parent->getTag()));

						if ($parent->getTag() !== $ancestor->getTag())
						{
							$this->assertFalse($graph->isOrphanByTag($parent->getTag()));
							$this->assertEquals(1, $graph->getOutdegreeByTag($parent->getTag()));
						}
						else
						{
							$this->assertTrue($graph->isOrphanByTag($parent->getTag()));
							$this->assertEquals(0, $graph->getOutdegreeByTag($parent->getTag()));
						}

						$this->assertFalse($graph->isNahproByTag($ancestor->getTag()));
						$this->assertTrue($graph->isOrphanByTag($ancestor->getTag()));
						$this->assertGreaterThan(0, $graph->getIndegreeByTag($ancestor->getTag()));
						$this->assertEquals(0, $graph->getOutdegreeByTag($ancestor->getTag()));

						$this->assertEquals(array($parent->getTag()), $graph->getParentTagsByTag($vertexInGraph->getTag())->pierceMonad());

						//$this->attemptInvalidTagChange($vertexInGraph, $parent->getTag());

						$this->assertEquals($tag, $vertexInGraph->getTag());

						$this->addVertices($graph, $ancestor, $vertex, $descendants);
					}
					else
					{
						$this->addVertices($graph, $vertex, $vertex, $descendants);
					}
				}
			}

			private function attemptInvalidTagChange(\Data\Graphs\SimpleVertex $vertex, $invalidTag)
			{
				$exception = FALSE;

				try
				{
					$vertex->setTag($invalidTag);
				}
				catch (\Data\Graphs\Exceptions\VertexAlreadyExists $e)
				{
					$exception = TRUE;
				}

				$this->assertTrue($exception);
			}

			private function checkPair(\Data\Graphs\AcyclicDigraph $graph, $tag1, $tag2)
			{
				$connected1 = $graph->isConnectedToByTags($tag1, $tag2);
				$connected2 = $graph->isConnectedToByTags($tag2, $tag1);

				//print('['.$tag1.'] -> ['.$tag2.']: '.($connected1 ? 'YES' : 'NO')."\n");
				//print('['.$tag2.'] -> ['.$tag1.']: '.($connected2 ? 'YES' : 'NO')."\n");

				if ($connected1 || $connected2)
				{
					$this->attemptInvalidArcCreation($graph, $tag1, $tag2);
				}
				else
				{
					$graph->addArcByTags($tag1, $tag2);

					$this->assertTrue($graph->isConnectedToByTags($tag1, $tag2));
				}
			}

			private function attemptInvalidArcCreation(\Data\Graphs\AcyclicDigraph $graph, $tag1, $tag2)
			{
				$exception = FALSE;

				try
				{
					$graph->addArcByTags($tag1, $tag2);
				}
				catch (\Data\Graphs\Exceptions\VerticesAlreadyConnected $e)
				{
					$exception = TRUE;
				}

				$this->assertTrue($exception);
			}
		}
	}

?>
