<?php

	namespace View\Svg
	{
		require_once(dirname(__FILE__).'/../../Data/Graphs/AcyclicDigraph.php');

		require_once(dirname(__FILE__).'/../../Xml/DomGenerator.php');

		require_once(dirname(__FILE__).'/IRectRenderer.php');

		class SimpleAcyclicDigraphTreeView extends \Xml\DomGenerator
		{
			private $graph;

			private $vertices = array();
			private $curX = 0;
			private $curY = 0;
			private $maxX = 0;
			private $maxY = 0;

			private $vertexRectRenderer = NULL;

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

			public function setVertexRectRenderer(IRectRenderer $renderer)
			{
				$this->vertexRectRenderer = $renderer;

				return $this;
			}

			public function generate()
			{
				$this->genElt('svg')->genTxtAttr('version', '1.1');

				$transformedSize = $this->vertexRectRenderer->renderRect(array(
						'x' => 0,
						'y' => 0,
						'w' => $this->maxX,
						'h' => $this->maxY,
						'm' => 0,
						));

				$this->genTxtAttr('viewBox', implode(' ', array(
						strval($transformedSize['x']),
						strval($transformedSize['y']),
						strval($transformedSize['w']),
						strval($transformedSize['h']),
						)));

				$this->genTxtAttr('preserveAspectRatio', 'xMidYMid meet');

				foreach (array_reverse($this->vertices) as $tag => $vertex)
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
					$this->vertices[$tag] = array('x' => $this->curX, 'y' => $this->curY, 'w' => 1, 'h' => 1,);

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

					$this->vertices[$tag] = array('x' => $myX, 'y' => $this->curY, 'w' => $this->curX - $myX, 'h' => 1,);
				}

				return $this;
			}

			final private function genVertex($tag)
			{
				$vertex = $this->vertices[$tag];

				$r = $this->vertexRectRenderer->renderRect($vertex);

				$this->vertices[$tag]['r'] = $r;

				$this->genElt('rect');

				$this->genTxtAttr('stroke', 'black');
				$this->genTxtAttr('stroke-width', '0.01');
				$this->genTxtAttr('fill', 'lightgrey');

				$this->genTxtAttr('x', strval($r['x']));
				$this->genTxtAttr('y', strval($r['y']));
				$this->genTxtAttr('width', strval($r['w']));
				$this->genTxtAttr('height', strval($r['h']));

				$this->up();

				$this->genElt('text');

				$this->genTxtAttr('font-size', '0.1');
				$this->genTxtAttr('fill', 'black');
				$this->genTxtAttr('font-family', 'monospace');
				$this->genTxtAttr('font-weight', 'black');
				$this->genTxtAttr('dominant-baseline', 'text-after-edge');
				$this->genTxtAttr('text-anchor', 'middle');
				$this->genTxtAttr('x', strval($r['x'] + ($r['w'] / 2)));
				$this->genTxtAttr('y', strval($r['y'] + ($r['h'] / 2)));

				$this->genTxt($this->graph->getVertexByTag($tag)->getName());

				$this->up();

				$this->genElt('text');

				$this->genTxtAttr('font-size', '0.05');
				$this->genTxtAttr('fill', 'black');
				$this->genTxtAttr('font-family', 'monospace');
				$this->genTxtAttr('font-weight', 'black');
				$this->genTxtAttr('font-style', 'italic');
				$this->genTxtAttr('dominant-baseline', 'text-after-edge');
				$this->genTxtAttr('text-anchor', 'middle');
				$this->genTxtAttr('x', strval($r['x'] + ($r['w'] / 2)));
				$this->genTxtAttr('y', strval($r['y'] + ($r['h'] * 0.75)));

				$this->genTxt($this->graph->getVertexByTag($tag)->getPosition());

				$this->up();

				if (count($parentTags = $this->graph->getParentTagsByTag($tag)->pierceMonad()))
				{
					$this->genElt('line');
	
					$this->genTxtAttr('stroke', 'black');
					$this->genTxtAttr('stroke-width', '0.01');

					$this->genTxtAttr('x1', strval($r['x'] + ($r['w'] / 2)));
					$this->genTxtAttr('y1', strval($r['y']));
					$this->genTxtAttr('x2', strval($r['x'] + ($r['w'] / 2)));
					$this->genTxtAttr('y2', strval($this->vertices[$parentTags[0]]['r']['y'] + $this->vertices[$parentTags[0]]['r']['h']));
	
					$this->up();
				}

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
