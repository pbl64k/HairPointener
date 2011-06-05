<?php

	namespace Data\Graphs
	{
		require_once(dirname(__FILE__).'/IVertex.php');
		require_once(dirname(__FILE__).'/IArc.php');

		final class AcyclicDigraph implements IGraph
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
	
			final public function notifyVertexTagChange(IVertex $vertex)
			{
				assert($this->existsVertexByTag($vertex->getOldTag()));
	
				$this->removeVertexByTag($vertex->getOldTag());
	
				assert(! $this->existsVertexByTag($vertex->getTag()));
	
				$this->vertices[$vertex->getTag()] = $vertex;
	
				return $this;
			}

			final public function isWellFormedVertexTag($vertexTag)
			{
				return is_string($vertexTag);
			}
	
			final public function isValidVertex(IVertex $vertex)
			{
				return TRUE;
			}

			final public function addVertex(IVertex $vertex)
			{
				assert(! $this->existsVertexByTag($vertex->getTag()));
				assert($this->isValidVertex($vertex));
	
				$this->orphans[$vertex->getTag()] = TRUE;
				$this->snahpro[$vertex->getTag()] = TRUE;

				$this->vertices[$vertex->getTag()] = $vertex;
	
				$vertex->attachTo($this);
	
				return $this;
			}
	
			final public function existsVertexByTag($vertexTag)
			{
				assert($this->isWellFormedVertexTag($vertexTag));
	
				return array_key_exists($vertexTag, $this->vertices);
			}
	
			final public function isOrphanByTag($vertexTag)
			{
				assert($this->existsVertexByTag($vertexTag));

				return array_key_exists($vertexTag, $this->orphans);
			}

			final public function isNahproByTag($vertexTag)
			{
				assert($this->existsVertexByTag($vertexTag));

				return array_key_exists($vertexTag, $this->snahpro);
			}

			final public function getVertexByTag($vertexTag)
			{
				assert($this->existsVertexByTag($vertexTag));
	
				return $this->vertices[$vertexTag];
			}

			final public function getChildrenByTag($vertexTag)
			{
				assert($this->existsVertexByTag($vertexTag));

				assert(FALSE);
			}
	
			final public function getParentsByTag($vertexTag)
			{
				assert($this->existsVertexByTag($vertexTag));

				assert(FALSE);
			}
	
			final public function getDescendantsByTag($vertexTag)
			{
				assert($this->existsVertexByTag($vertexTag));

				assert(FALSE);
			}
	
			final public function getAncestorsByTag($vertexTag)
			{
				assert($this->existsVertexByTag($vertexTag));

				assert(FALSE);
			}
	
			final public function removeVertexByTag($vertexTag)
			{
				assert($this->existsVertexByTag($vertexTag));
	
				$this->removeAllArcsByTag($vertexTag);
	
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

			final public function removeAllArcsByTag($vertexTag)
			{
				assert($this->existsVertexByTag($vertexTag));

				assert(FALSE);

				return $this;
			}

			final public function removeAllScraByTag($vertexTag)
			{
				assert($this->existsVertexByTag($vertexTag));

				assert(FALSE);

				return $this;
			}

			final public function removeAllConnectionsByTag($vertexTag)
			{
				assert($this->existsVertexByTag($vertexTag));

				$this->removeAllArcsByTag($vertexTag);
				$this->removeAllScraByTag($vertexTag);

				return $this;
			}
	
			final public function isConnectedToByTag($sourceVertexTag, $targetVertexTag)
			{
				assert($this->existsVertexByTag($sourceVertexTag));
				assert($this->existsVertexByTag($targetVertexTag));
				
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
	
			final public function addArcByTag($sourceVertexTag, $targetVertexTag)
			{
				assert($this->existsVertexByTag($sourceVertexTag));
				assert($this->existsVertexByTag($targetVertexTag));
	
				assert(! $this->isConnectedToTag($sourceVertexTag, $targetVertexTag));
				assert(! $this->isConnectedToTag($targetVertexTag, $sourceVertexTag));
	
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
	
			final private function __construct()
			{
			}
		}
	}

?>
