<?php

	namespace View\Svg
	{
		require_once(dirname(__FILE__).'/../../Data/Graphs/AcyclicDigraph.php');

		require_once(dirname(__FILE__).'/../../Xml/DomGenerator.php');

		class SimpleAcyclicDigraphTreeView extends \Xml\DomGenerator
		{
			private $graph;

			private $vertices = array();
			private $curX = 0;
			private $curY = 0;
			private $maxX = 0;
			private $maxY = 0;

			private $margin = 0.1;

			static public function make(\Data\Graphs\AcyclicDigraph $graph)
			{
				$view = new self($graph);

				return $view;
			}

			public function makeLayout()
			{
				$this->vertices = array();
				$this->curX = 0;
				$this->curY = 0;
				$this->maxX = 0;
				$this->maxY = 0;

				$topLevel = $this->graph->getOrphanTags()->pierceMonad();

				foreach ($topLevel as $tag)
				{
					$this->addVertexToLayout($tag);
				}

				return $this;
			}

			public function generate()
			{
				$this->genElt('svg')->genTxtAttr('version', '1.1');

				$this->genTxtAttr('viewBox', '0 0 '.strval($this->maxX + 1).' '.strval($this->maxY + 1));
				$this->genTxtAttr('preserveAspectRatio', 'xMidYMid meet');

				foreach ($this->vertices as $tag => $vertex)
				{
					$this->genVertex($tag);
				}

				$this->up();

				return $this;
			}

			protected function __construct(\Data\Graphs\AcyclicDigraph $graph)
			{
				parent::__construct();

				$this->graph = $graph;

				$this->setNs('http://www.w3.org/2000/svg');
			}

			final private function addVertexToLayout($tag)
			{
				if ($this->graph->isNahproByTag($tag))
				{
					$this->vertices[$tag] = array('x' => $this->curX, 'y' => $this->curY, 'w' => 1,);

					$this->incCurX();
				}
				else
				{
					$myX = $this->curX;

					$this->incCurY();

					foreach ($this->graph->getChildTagsByTag($tag)->pierceMonad() as $childTag)
					{
						$this->addVertexToLayout($childTag);
					}

					--$this->curY;

					$this->vertices[$tag] = array('x' => $myX, 'y' => $this->curY, 'w' => $this->curX - $myX,);
				}

				return $this;
			}

			final private function genVertex($tag)
			{
				$vertex = $this->vertices[$tag];

				$this->genElt('rect');

				$this->genTxtAttr('stroke', 'black');
				$this->genTxtAttr('stroke-width', '0.01');
				$this->genTxtAttr('fill', 'lightgrey');

				$this->genTxtAttr('x', strval($vertex['x'] + $this->margin));
				$this->genTxtAttr('y', strval($vertex['y'] + $this->margin));
				$this->genTxtAttr('width', strval($vertex['w'] - (2 * $this->margin)));
				$this->genTxtAttr('height', strval(1 - (2 * $this->margin)));

				$this->up();

				$this->genElt('text');

				$this->genTxtAttr('font-size', '0.2');
				$this->genTxtAttr('fill', 'black');
				$this->genTxtAttr('font-family', 'sans-serif');
				$this->genTxtAttr('baseline', 'central');
				$this->genTxtAttr('text-anchor', 'middle');
				$this->genTxtAttr('x', strval($vertex['x'] + ($vertex['w'] / 2)));
				$this->genTxtAttr('y', strval($vertex['y'] + 0.5));

				$this->genTxt($this->graph->getVertexByTag($tag)->getName());

				$this->up();

				return $this;
			}

			final private function incCurX()
			{
				++$this->curX;

				$this->maxX = max($this->curX, $this->maxX);
			}

			final private function incCurY()
			{
				++$this->curY;

				$this->maxY = max($this->curY, $this->maxY);
			}
		}
	}

?>
