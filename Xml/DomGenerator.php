<?php

	namespace Xml
	{
		abstract class DomGenerator
		{
			private $dom = NULL;
			private $context = array();
			private $ns = '';

			abstract public function generate();

			protected function __construct()
			{
				$this->initDom()->setNs();
			}

			final protected function initDom()
			{
				$this->dom = new DOMDocument;

				array_push($this->context, $this->dom);

				return $this;
			}

			final protected function setNs($ns = '')
			{
				$this->ns = $ns;

				return $this;
			}

			final protected function genElt($name, $ns = NULL)
			{
				$namespace = is_null($ns) ? $this->ns : $ns;

				$elt = $this->dom->createElementNS($namespace, $name);

				$this->context[count($this->context) - 1]->appendChild($elt);

				$this->context[] = $elt;

				return $this;
			}

			final protected function genAttr($name, $ns = NULL)
			{
				$namespace = is_null($ns) ? $this->ns : $ns;

				$attr = $this->dom->createAttributeNS($namespace, $name);

				$this->context[count($this->context) - 1]->appendChild($attr);

				$this->context[] = $attr;

				return $this;
			}

			final protected function up()
			{
				array_pop($this->context);

				return $this;
			}

			final protected function genTxtElt($name, $value = NULL, $ns = NULL)
			{
				$this->genElt($name, $ns);

				$textNode = $this->dom->createTextNode(strval($value));

				$this->context[count($this->context) - 1]->appendChild($textNode);

				return $this->up();
			}

			final protected function genTxtAttr($name, $value = NULL, $ns = NULL)
			{
				$this->genAttr($name, $ns);

				$textNode = $this->dom->createTextNode(strval($value));

				$this->context[count($this->context) - 1]->appendChild($textNode);

				return $this->up();
			}
		}
	}

?>
