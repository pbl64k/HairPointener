<?php

	namespace Data\Graphs
	{
		interface IGraph
		{
			public function addVertex(IVertex $vertex);
			public function existsVertexByTag($vertexTag);
			public function getVertexByTag($vertexTag);
			public function removeVertexByTag($vertexTag);
			public function addArcByTags($sourceVertexTag, $targetVertexTag);
			public function isConnectedToByTags($sourceVertexTag, $targetVertexTag);

			public function notifyVertexTagChange(IVertex $vertex);
		}
	}

?>
