<?php

	namespace Data\Graphs
	{
		require_once(dirname(__FILE__).'/../../Control/Monads/ArrayMonad.php');

		require_once(dirname(__FILE__).'/IVertex.php');
		require_once(dirname(__FILE__).'/IArc.php');
		require_once(dirname(__FILE__).'/IGraph.php');

		require_once(dirname(__FILE__).'/SimpleArc.php');

		require_once(dirname(__FILE__).'/Exceptions/InvalidTag.php');
		require_once(dirname(__FILE__).'/Exceptions/VertexDoesNotExist.php');
		require_once(dirname(__FILE__).'/Exceptions/VertexAlreadyExists.php');
		require_once(dirname(__FILE__).'/Exceptions/VerticesAlreadyConnected.php');

		class AcyclicDigraph implements IGraph
		{
			private $vertices = array();
	
			private $orphans = array();
			private $snahpro = array();
			private $arcs = array();
			private $scra = array();
	
			static public function make()
			{
				return new self;
			}
	
			final public function addVertex(IVertex $vertex)
			{
				$this->checkVertexValidity($vertex)->checkVertexNonexistenceByTag($vertex->getTag());
	
				$this->orphans[$vertex->getTag()] = TRUE;
				$this->snahpro[$vertex->getTag()] = TRUE;

				$this->vertices[$vertex->getTag()] = $vertex;
	
				$vertex->attachTo($this);
	
				return $this;
			}

			final public function addChildVertex($parentTag, IVertex $vertex)
			{
				$this->checkVertexExistenceByTag($parentTag)->checkVertexValidity($vertex)->checkVertexNonexistenceByTag($vertex->getTag());

				$this->addVertex($vertex)->addArcByTags($vertex->getTag(), $parentTag);

				return $this;
			}
	
			final public function existsVertexByTag($vertexTag)
			{
				$this->checkVertexTagWellFormedness($vertexTag);
	
				return array_key_exists($vertexTag, $this->vertices);
			}

			final public function getVertexByTag($vertexTag)
			{
				$this->checkVertexExistenceByTag($vertexTag);
	
				return $this->vertices[$vertexTag];
			}

			final public function removeVertexByTag($vertexTag)
			{
				$this->checkVertexExistenceByTag($vertexTag)->removeAllArcsByTag($vertexTag);
	
				$this->getVertexByTag($vertexTag)->detach();

				unset ($this->vertices[$vertexTag]);
	
				return $this;
			}

			final public function addArcByTags($sourceVertexTag, $targetVertexTag)
			{
				$this->checkVertexExistenceByTag($sourceVertexTag)->checkVertexExistenceByTag($targetVertexTag);
	
				$this->checkNonconnectednessByTags($sourceVertexTag, $targetVertexTag)->checkNonconnectednessByTags($targetVertexTag, $sourceVertexTag);
	
				if ($this->isOrphanByTag($sourceVertexTag))
				{
					$this->arcs[$sourceVertexTag] = array();

					unset ($this->orphans[$sourceVertexTag]);
				}
	
				$this->arcs[$sourceVertexTag][] = $targetVertexTag;
	
				if ($this->isNahproByTag($targetVertexTag))
				{
					$this->scra[$targetVertexTag] = array();

					unset ($this->snahpro[$targetVertexTag]);
				}
	
				$this->scra[$targetVertexTag][] = $sourceVertexTag;
	
				return $this;
			}
	
			final public function isConnectedToByTags($sourceVertexTag, $targetVertexTag)
			{
				$this->checkVertexExistenceByTag($sourceVertexTag)->checkVertexExistenceByTag($targetVertexTag);

				if ($sourceVertexTag === $targetVertexTag)
				{
					return TRUE;
				}
				
				if (! $this->isOrphanByTag($sourceVertexTag))
				{
					foreach ($this->arcs[$sourceVertexTag] as $arcEndTag)
					{
						if ($arcEndTag === $targetVertexTag)
						{
							return TRUE;
						}
	
						if ($this->isConnectedToByTags($arcEndTag, $targetVertexTag))
						{
							return TRUE;
						}
					}
				}
	
				return FALSE;
			}

			final public function getAllTags()
			{
				return \Control\Monads\ArrayMonad::makeFromArray(array_keys($this->vertices));
			}

			final public function isOrphanByTag($vertexTag)
			{
				$this->checkVertexExistenceByTag($vertexTag);

				return array_key_exists($vertexTag, $this->orphans);
			}

			final public function isNahproByTag($vertexTag)
			{
				$this->checkVertexExistenceByTag($vertexTag);

				return array_key_exists($vertexTag, $this->snahpro);
			}

			final public function getNahproTags()
			{
				return \Control\Monads\ArrayMonad::makeFromArray(array_keys($this->snahpro));
			}

			final public function getOrphanTags()
			{
				return \Control\Monads\ArrayMonad::makeFromArray(array_keys($this->orphans));
			}

			final public function getChildTagsByTag($vertexTag)
			{
				$this->checkVertexExistenceByTag($vertexTag);

				if ($this->isNahproByTag($vertexTag))
				{
					return \Control\Monads\ArrayMonad::makeFromArray(array());
				}

				return \Control\Monads\ArrayMonad::makeFromArray($this->scra[$vertexTag]);
			}
	
			final public function getParentTagsByTag($vertexTag)
			{
				$this->checkVertexExistenceByTag($vertexTag);

				if ($this->isOrphanByTag($vertexTag))
				{
					return \Control\Monads\ArrayMonad::makeFromArray(array());
				}

				return \Control\Monads\ArrayMonad::makeFromArray($this->arcs[$vertexTag]);
			}

			final public function getArcsByTag($vertexTag)
			{
				$this->checkVertexExistenceByTag($vertexTag);

				return $this->getParentTagsByTag($vertexTag)->mfmap(function($parentTag) use($vertexTag) { return SimpleArc::makeByTags($vertexTag, $parentTag); });
			}
	
			final public function getScraByTag($vertexTag)
			{
				$this->checkVertexExistenceByTag($vertexTag);

				return $this->getChildTagsByTag($vertexTag)->mfmap(function($childTag) use($vertexTag) { return SimpleArc::makeByTags($childTag, $vertexTag); });
			}
	
			final public function getDescendantTagsByTag($vertexTag)
			{
				$this->checkVertexExistenceByTag($vertexTag);

				$graph = $this;

				return $this->getChildTagsByTag($vertexTag)->mbind(
						function($tag) use($graph)
						{
							return $graph->getDescendantTagsByTag($tag)->mplus(\Control\Monads\ArrayMonad::makeFromArray(array($tag)));
						}
						);
			}
	
			final public function getAncestorTagsByTag($vertexTag)
			{
				$this->checkVertexExistenceByTag($vertexTag);

				$graph = $this;

				$parents = $this->getParentTagsByTag($vertexTag)->mbind(
						function($tag) use($graph)
						{
							return $graph->getAncestorTagsByTag($tag)->mplus(\Control\Monads\ArrayMonad::makeFromArray(array($tag)));
						}
						);
			}

			final public function getIndegreeByTag($vertexTag)
			{
				$this->checkVertexExistenceByTag($vertexTag);

				return count($this->getChildTagsByTag($vertexTag)->pierceMonad());
			}
	
			final public function getOutdegreeByTag($vertexTag)
			{
				$this->checkVertexExistenceByTag($vertexTag);

				return count($this->getParentTagsByTag($vertexTag)->pierceMonad());
			}
	
			final public function getMaxDepthByTag($vertexTag)
			{
				$this->checkVertexExistenceByTag($vertexTag);

				if ($this->isOrphanByTag($vertexTag))
				{
					return 0;
				}

				$graph = $this;

				return max($this->getParentTagsByTag($vertexTag)->mfmap(function($tag) use($graph) { return $graph->getMaxDepthByTag($tag) + 1; })->pierceMonad());
			}
	
			final public function getMinDepthByTag($vertexTag)
			{
				$this->checkVertexExistenceByTag($vertexTag);

				if ($this->isOrphanByTag($vertexTag))
				{
					return 0;
				}

				$graph = $this;

				return min($this->getParentTagsByTag($vertexTag)->mfmap(function($tag) use($graph) { return $graph->getMinDepthByTag($tag) + 1; })->pierceMonad());
			}
	
			final public function getMaxHeightByTag($vertexTag)
			{
				$this->checkVertexExistenceByTag($vertexTag);

				if ($this->isNahproByTag($vertexTag))
				{
					return 0;
				}

				$graph = $this;

				return max($this->getChildTagsByTag($vertexTag)->mfmap(function($tag) use($graph) { return $graph->getMaxHeightByTag($tag) + 1; })->pierceMonad());
			}
	
			final public function getMinHeightByTag($vertexTag)
			{
				$this->checkVertexExistenceByTag($vertexTag);

				if ($this->isNahproByTag($vertexTag))
				{
					return 0;
				}

				$graph = $this;

				return min($this->getChildTagsByTag($vertexTag)->mfmap(function($tag) use($graph) { return $graph->getMinHeightByTag($tag) + 1; })->pierceMonad());
			}
	
			final public function getWidthByTag($vertexTag)
			{
				assert(FALSE);
			}

			final public function sequenceArcByTags($sourceVertexTag, $targetVertexTag, ArrayMonad $vertices)
			{
				assert(FALSE);
			}

			final public function parallelizeArcByTags($sourceVertexTag, $targetVertexTag, ArrayMonad $vertices)
			{
				assert(FALSE);
			}

			// this is broken horribly, we need to reseat the arcs if this ever happens.
			final public function notifyVertexTagChange(IVertex $vertex)
			{
				assert(FALSE);
				$this->checkVertexValidity($vertex)->checkVertexExistenceByTag($vertex->getOldTag())->checkVertexNonexistenceByTag($vertex->getTag());
	
				$this->removeVertexByTag($vertex->getOldTag());

				$this->checkVertexValidity($tag);
	
				$this->vertices[$vertex->getTag()] = $vertex;
	
				return $this;
			}

			protected function __construct()
			{
			}
	
			protected function isValidVertex(IVertex $vertex)
			{
				return TRUE;
			}

			protected function isWellFormedVertexTag($vertexTag)
			{
				return is_string($vertexTag);
			}
	
			final private function checkVertexTagWellFormedness($vertexTag)
			{
				if (! $this->isWellFormedVertexTag($vertexTag))
				{
					throw new Exceptions\InvalidTag($vertexTag);
				}
			}

			final private function checkVertexValidity(IVertex $vertex)
			{
				return $this;
			}

			final private function checkVertexExistenceByTag($vertexTag)
			{
				if (! $this->existsVertexByTag($vertexTag))
				{
					throw new Exceptions\VertexDoesNotExist($vertexTag);
				}

				return $this;
			}
	
			final private function checkVertexNonexistenceByTag($vertexTag)
			{
				if ($this->existsVertexByTag($vertexTag))
				{
					throw new Exceptions\VertexAlreadyExists($vertexTag);
				}

				return $this;
			}
	
			final private function removeAllArcsByTag($vertexTag)
			{
				$this->checkVertexExistenceByTag($vertexTag);

				if (! $this->isOrphanByTag($vertexTag))
				{
					foreach ($this->arcs[$vertexTag] as $targetTag)
					{
						$this->scra[$targetTag] = array_filter($this->scra[$targetTag], function($tag) use ($vertexTag) { return $tag !== $vertexTag; } );
					}
	
					unset ($this->arcs[$vertexTag]);

					$this->orphans[$vertexTag] = TRUE;
				}
	
				return $this;
			}

			final private function removeAllScraByTag($vertexTag)
			{
				$this->checkVertexExistenceByTag($vertexTag);

				if (! $this->isNahproByTag($vertexTag))
				{
					foreach ($this->scra[$vertexTag] as $sourceTag)
					{
						$this->arcs[$sourceTag] = array_filter($this->arcs[$sourceTag], function($tag) use ($vertexTag) { return $tag !== $vertexTag; } );
					}
	
					unset ($this->scra[$vertexTag]);

					$this->snahpro[$vertexTag] = TRUE;
				}

				return $this;
			}

			final private function removeAllConnectionsByTag($vertexTag)
			{
				return $this->checkVertexExistenceByTag($vertexTag)->removeAllArcsByTag($vertexTag)->removeAllScraByTag($vertexTag);
			}
	
			final private function checkNonconnectednessByTags($sourceVertexTag, $targetVertexTag)
			{
				if ($this->isConnectedToByTags($sourceVertexTag, $targetVertexTag))
				{
					throw new Exceptions\VerticesAlreadyConnected(serialize(array($sourceVertexTag, $targetVertexTag)));
				}

				return $this;
			}
		}
	}

?>
