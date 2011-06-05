<?php

	namespace Data\Graphs
	{
		require_once(dirname(__FILE__).'/IVertex.php');
		require_once(dirname(__FILE__).'/IArc.php');
		require_once(dirname(__FILE__).'/IGraph.php');

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
	
			final static public function make()
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
	
				/*
				if (array_key_exists($tag, $this->reqs))
				{
					foreach ($this->reqs[$tag] as $depTag)
					{
						$this->sqer[$depTag] = array_filter($this->sqer[$depTag], function($t) use ($tag) { return $t === $t; } );
					}
	
					unset($this->reqs[$tag]);
				}
	
				if (array_key_exists($tag, $this->sqer))
				{
					foreach ($this->sqer[$tag] as $goalTag)
					{
						$this->reqs[$goalTag] = array_filter($this->reqs[$goalTag], function($t) use ($tag) { return $t === $t; } );
					}
	
					unset($this->sqer[$tag]);
				}
				*/

				$this->getVertexByTag($vertexTag)->detach();

				unset($this->vertices[$vertexTag]);
	
				return $this;
			}

			final public function addArcByTags($sourceVertexTag, $targetVertexTag)
			{
				$this->checkVertexExistenceByTag($sourceVertexTag)->checkVertexExistenceByTag($targetVertexTag);
	
				$this->checkNonconnectednessByTags($sourceVertexTag, $targetVertexTag)->checkNonconnectednessByTags($targetVertexTag, $sourceVertexTag);
	
				if (! array_key_exists($sourceVertexTag, $this->arcs))
				{
					$this->arcs[$sourceVertexTag] = array();
				}
	
				$this->arcs[$sourceVertexTag][] = $targetVertexTag;
	
				if (! array_key_exists($targetVertexTag, $this->scra))
				{
					$this->scra[$targetVertexTag] = array();
				}
	
				$this->scra[$targetVertexTag][] = $targetVertexTag;
	
				return $this;
			}
	
			final public function isConnectedToByTags($sourceVertexTag, $targetVertexTag)
			{
				$this->checkVertexExistenceByTag($sourceVertexTag)->checkVertexExistenceByTag($targetVertexTag);
				
				if (array_key_exists($targetVertexTag, $this->scra))
				{
					foreach ($this->arcs[$sourceVertexTag] as $arcEndTag)
					{
						if ($arcEndTag === $targetVertexTag)
						{
							return TRUE;
						}
	
						if ($this->isConnectedToByTag($arcEndTag, $targetVertexTag))
						{
							return TRUE;
						}
					}
				}
	
				return FALSE;
			}

			final public function notifyVertexTagChange(IVertex $vertex)
			{
				$this->checkVertexValidity($vertex)->checkVertexExistenceByTag($vertex->getOldTag())->removeVertexByTag($vertex->getOldTag());
	
				$this->checkVertexValidity($vertex)->checkVertexNonexistenceByTag($vertex->getTag());
	
				$this->vertices[$vertex->getTag()] = $vertex;
	
				return $this;
			}

			final private function isWellFormedVertexTag($vertexTag)
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

			final private function isValidVertex(IVertex $vertex)
			{
				return TRUE;
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
	
			final private function isOrphanByTag($vertexTag)
			{
				$this->checkVertexExistenceByTag($vertexTag);

				return array_key_exists($vertexTag, $this->orphans);
			}

			final private function isNahproByTag($vertexTag)
			{
				$this->checkVertexExistenceByTag($vertexTag);

				return array_key_exists($vertexTag, $this->snahpro);
			}

			final private function getChildTagsByTag($vertexTag)
			{
				$this->checkVertexExistenceByTag($vertexTag);

				assert(FALSE);
			}
	
			final private function getParentTagsByTag($vertexTag)
			{
				$this->checkVertexExistenceByTag($vertexTag);

				assert(FALSE);
			}
	
			final private function getDescendantTagsByTag($vertexTag)
			{
				$this->checkVertexExistenceByTag($vertexTag);

				assert(FALSE);
			}
	
			final private function getAncestorTagsByTag($vertexTag)
			{
				$this->checkVertexExistenceByTag($vertexTag);

				assert(FALSE);
			}
	
			final private function removeAllArcsByTag($vertexTag)
			{
				$this->checkVertexExistenceByTag($vertexTag);

				assert(FALSE);

				return $this;
			}

			final private function removeAllScraByTag($vertexTag)
			{
				$this->checkVertexExistenceByTag($vertexTag);

				assert(FALSE);

				return $this;
			}

			final private function removeAllConnectionsByTag($vertexTag)
			{
				return $this->checkVertexExistenceByTag($vertexTag)->removeAllArcsByTag($vertexTag)->removeAllScraByTag($vertexTag);
			}
	
			final private function checkNonconnectednessByTags($sourceVertexTag, $targetVertexTag)
			{
				if ($this->isConnectedByTags($sourceVertexTag, $targetVertexTag))
				{
					throw new Exceptions\VerticesAlreadyConnectedByAnArc(array($sourceVertecTag, $targetVertexTag));
				}

				return $this;
			}
	
			final private function __construct()
			{
			}
		}
	}

?>
