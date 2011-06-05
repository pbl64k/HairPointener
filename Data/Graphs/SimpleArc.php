<?php

	namespace Data\Graphs
	{
		require_once(dirname(__FILE__).'/IArc.php');

		class SimpleArc implements IArc
		{
			private $sourceVertexTag = NULL;
			private $targetVertexTag = NULL;

			static public function makeByTags($sourceVertexTag, $targetVertexTag)
			{
				$arc = new self;

				$arc->setSourceVertexTag($sourceVertexTag)->setTargetVertexTag($targetVertexTag);

				return $arc;
			}

			final public function getSourceVertexTag()
			{
				return $this->sourceVertexTag;
			}

			final public function getTargetVertexTag()
			{
				return $this->targetVertexTag;
			}

			protected function __construct()
			{
			}

			final private function setSourceVertexTag($vertexTag)
			{
				$this->sourceVertexTag = $vertexTag;
			}

			final private function setTargetVertexTag($vertexTag)
			{
				$this->targetVertexTag = $vertexTag;
			}
		}
	}

?>
