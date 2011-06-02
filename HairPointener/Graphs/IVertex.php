<?php

	namespace HairPointener\Graphs
	{
		require_once(dirname(__FILE__).'/IGraph.php');

		interface IVertex
		{
			public function attachTo(IGraph $graph);
			public function detach();
	
			public function setTag($tag);
			public function getTag();
			public function getOldTag();
	
			public function setDescription($description);
			public function getDescription();
		}
	}

?>
