<?php

use qtism\data\storage\xml\XmlDocument;
use qtism\runtime\rendering\markup\xhtml\XhtmlRenderingEngine;

require_once(dirname(__FILE__) . '/../../vendor/autoload.php');

$doc = new XmlDocument();
$doc->load(dirname(__FILE__) . '/../samples/rendering/math_2.xml');

$renderer = new XhtmlRenderingEngine();
$rendering = $renderer->render($doc->getDocumentComponent());
$rendering->formatOutput = true;

$dom = new DOMDocument('1.0', 'UTF-8');
$dom->loadXML($rendering->saveXML());
echo $dom->saveXML();