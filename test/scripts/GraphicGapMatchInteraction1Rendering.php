<?php

use qtism\data\storage\xml\XmlDocument;
use qtism\runtime\rendering\markup\xhtml\XhtmlRenderingEngine;

require_once(dirname(__FILE__) . '/../../src/qtism.php');

$doc = new XmlDocument();
$doc->load('../samples/rendering/graphicgapmatchinteraction_1.xml');

$renderer = new XhtmlRenderingEngine();

$rendering = $renderer->render($doc->getDocumentComponent());
$rendering->formatOutput = true;

echo $rendering->saveXML();