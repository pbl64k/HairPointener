<?php

	namespace View\Svg
	{
		require_once(dirname(__FILE__).'/IRectRenderer.php');

		class SimpleRectRenderer implements IRectRenderer
		{
			private $margin = NULL;
			private $cX = NULL;
			private $cY = NULL;

			static public function make($cX = 1, $cY = 1, $margin = 0)
			{
				$renderer = new self;

				$renderer->cX = $cX;
				$renderer->cY = $cY;
				$renderer->margin = $margin;

				return $renderer;
			}

			public function renderRect(array $rect)
			{
				if (! array_key_exists('m', $rect))
				{
					$rect['m'] = $this->margin;
				}

				return array(
						'x' => ($rect['x'] * $this->cX) + $rect['m'],
						'y' => ($rect['y'] * $this->cY) + $rect['m'],
						'w' => ($rect['w'] * $this->cX) - (2 * $rect['m']),
						'h' => ($rect['h'] * $this->cY) - (2 * $rect['m']),
						);
			}

			protected function __construct()
			{
			}
		}
	}

?>
