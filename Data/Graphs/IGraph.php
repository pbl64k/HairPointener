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
			public function isOrphanByTag($vertexTag);
			public function isNahproByTag($vertexTag);
			public function getChildTagsByTag($vertexTag);
			public function getParentTagsByTag($vertexTag);
			public function getArcsByTag($vertexTag);
			public function getScraByTag($vertexTag);
			public function getDescendantTagsByTag($vertexTag);
			public function getAncestorTagsByTag($vertexTag);
			public function getIndegreeByTag($vertexTag);
			public function getOutdegreeByTag($vertexTag);

			public function notifyVertexTagChange(IVertex $vertex);
		}
	}

?>
