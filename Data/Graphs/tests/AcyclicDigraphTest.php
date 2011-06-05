<?php

	namespace Data\Graphs\tests
	{
		require_once('PHPUnit/Framework/TestCase.php');

		require_once(dirname(__FILE__).'/../SimpleVertex.php');
		require_once(dirname(__FILE__).'/../AcyclicDigraph.php');

		class AcyclicDigraphTest extends \PHPUnit_Framework_TestCase
		{
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

					if ($parent->getTag())
					{
						$graph->addArcByTags($vertex->getTag(), $parent->getTag());

						$this->assertTrue($graph->isConnectedToByTags($vertexInGraph->getTag(), $parent->getTag()));
						$this->assertTrue($graph->isConnectedToByTags($vertexInGraph->getTag(), $ancestor->getTag()));
						$this->assertFalse($graph->isConnectedToByTags($parent->getTag(), $vertexInGraph->getTag()));
						$this->assertFalse($graph->isConnectedToByTags($ancestor->getTag(), $vertexInGraph->getTag()));

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
		}
	}

?>
