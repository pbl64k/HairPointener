<?php

	namespace Data\Graphs
	{
		require_once(dirname(__FILE__).'/IVertex.php');

		interface IArc
		{
			public function getSourceVertexTag();
			public function getTargetVertexTag();
		}
	}

?>
