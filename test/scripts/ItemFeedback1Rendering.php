<?php

use qtism\runtime\common\State;
use qtism\data\storage\xml\XmlDocument;
use qtism\runtime\rendering\markup\xhtml\XhtmlRenderingEngine;
use qtism\runtime\rendering\markup\AbstractMarkupRenderingEngine;
use qtism\runtime\common\OutcomeVariable;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\common\datatypes\Identifier;

require_once(dirname(__FILE__) . '/../../vendor/autoload.php');

$doc = new XmlDocument();
$doc->load(dirname(__FILE__) . '/../samples/rendering/itemfeedback_1.xml');

$outcome1 = new OutcomeVariable('FEEDBACK', Cardinality::SINGLE, BaseType::IDENTIFIER, new Identifier(''));
$renderer = new XhtmlRenderingEngine();

if (isset($argv[1]) && $argv[1] === 'CONTEXT_AWARE') {
    $renderer->setFeedbackShowHidePolicy(AbstractMarkupRenderingEngine::CONTEXT_AWARE);

    if (isset($argv[2])) {
        $outcome1->setValue(new Identifier($argv[2]));
    }
}

$renderer->setState(new State(array($outcome1)));
$rendering = $renderer->render($doc->getDocumentComponent());
$rendering->formatOutput = true;

echo $rendering->saveXML();