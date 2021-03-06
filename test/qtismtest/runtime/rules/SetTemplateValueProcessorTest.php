<?php
namespace qtismtest\runtime\rules;

use qtismtest\QtiSmTestCase;
use qtism\runtime\rules\RuleProcessingException;
use qtism\common\datatypes\Boolean;
use qtism\runtime\common\State;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\runtime\common\TemplateVariable;
use qtism\runtime\rules\SetTemplateValueProcessor;

class setTemplateValueProcessorTest extends QtiSmTestCase {
	
	public function testSetTemplateValueSimple() {
		$rule = $this->createComponentFromXml('
			<setTemplateValue identifier="TPL1">
				<baseValue baseType="float">4.3</baseValue>
			</setTemplateValue>
		');
		
		$processor = new SetTemplateValueProcessor($rule);
		$tpl1 = new TemplateVariable('TPL1', Cardinality::SINGLE, BaseType::FLOAT);
		$state = new State(array($tpl1));
		$processor->setState($state);
		$processor->process();
		
		// The state must be modified.
		// TemplateVariable with identifier 'TPL1' must contain 4.3.
		$this->assertInstanceOf('qtism\\common\\datatypes\\Float', $state['TPL1']);
		$this->assertEquals(4.3, $state['TPL1']->getValue());
	}
	
	public function testSetTemplateValueJugglingFromIntToFloat() {
	    $rule = $this->createComponentFromXml('
	        <setTemplateValue identifier="TPL1">
	            <baseValue baseType="integer">4</baseValue>
	        </setTemplateValue>
	    ');
	    
	    $processor = new SetTemplateValueProcessor($rule);
	    $tpl1 = new TemplateVariable('TPL1', Cardinality::SINGLE, BaseType::FLOAT);
	    $state = new State(array($tpl1));
	    $processor->setState($state);
	    $processor->process();
	    
	    $this->assertInstanceOf('qtism\\common\\datatypes\\Float', $state['TPL1']);
	    $this->assertEquals(4.0, $state['TPL1']->getValue());
	}
	
	public function testSetTemplateValueJugglingFromFloatToInt() {
	    $rule = $this->createComponentFromXml('
	        <setTemplateValue identifier="TPL1">
	            <baseValue baseType="float">4.3</baseValue>
	        </setTemplateValue>
	    ');
	     
	    $processor = new SetTemplateValueProcessor($rule);
	    $tpl1 = new TemplateVariable('TPL1', Cardinality::SINGLE, BaseType::INTEGER);
	    $state = new State(array($tpl1));
	    $processor->setState($state);
	    $processor->process();
	     
	    $this->assertInstanceOf('qtism\\common\\datatypes\\INTEGER', $state['TPL1']);
	    $this->assertEquals(4, $state['TPL1']->getValue());
	}
	
	public function testSetTemplateValueWrongJugglingScalar() {
	    $rule = $this->createComponentFromXml('
	        <setTemplateValue identifier="TPL1">
	            <baseValue baseType="string">String!</baseValue>
	        </setTemplateValue>
	    ');
	    
	    $processor = new SetTemplateValueProcessor($rule);
	    $tpl1 = new TemplateVariable('TPL1', Cardinality::SINGLE, BaseType::INTEGER);
	    $state = new State(array($tpl1));
	    $processor->setState($state);
	    
	    $this->setExpectedException('qtism\\runtime\\rules\\RuleProcessingException');
	    $processor->process();
	}
	
	public function testSetTemplateValueWrongJugglingMultipleOne() {
	    $rule = $this->createComponentFromXml('
	        <setTemplateValue identifier="TPL1">
	            <baseValue baseType="integer">1337</baseValue>
	        </setTemplateValue>
	    ');
	     
	    $processor = new SetTemplateValueProcessor($rule);
	    $score = new TemplateVariable('TPL1', Cardinality::MULTIPLE, BaseType::INTEGER);
	    $state = new State(array($score));
	    $processor->setState($state);
	     
	    $this->setExpectedException('qtism\\runtime\\rules\\RuleProcessingException');
	    $processor->process();
	}
	
	public function testSetTemplateValueWrongJugglingMultipleTwo() {
	    $rule = $this->createComponentFromXml('
	        <setTemplateValue identifier="TPL1">
	            <multiple>
	                <baseValue baseType="float">1337.1337</baseValue>
	            </multiple>
	        </setTemplateValue>
	    ');
	
	    $processor = new SetTemplateValueProcessor($rule);
	    $score = new TemplateVariable('TPL1', Cardinality::SINGLE, BaseType::INTEGER);
	    $state = new State(array($score));
	    $processor->setState($state);
	
	    $this->setExpectedException('qtism\\runtime\\rules\\RuleProcessingException');
	    $processor->process();
	}
	
	public function testSetOutcomeValueModerate() {
		$rule = $this->createComponentFromXml('
			<setTemplateValue identifier="myBool">
				<member>
					<baseValue baseType="string">Incredible!</baseValue>
					<multiple>
						<baseValue baseType="string">This...</baseValue>
						<baseValue baseType="string">Is...</baseValue>
						<baseValue baseType="string">Incredible!</baseValue>
					</multiple>
				</member>
			</setTemplateValue>
		');
		
		$processor = new SetTemplateValueProcessor($rule);
		$myBool = new TemplateVariable('myBool', Cardinality::SINGLE, BaseType::BOOLEAN, new Boolean(false));
		$state = new State(array($myBool));
		$this->assertFalse($state['myBool']->getValue());
		
		$processor->setState($state);
		$processor->process();
		$this->assertInstanceOf('qtism\\common\\datatypes\\Boolean', $state['myBool']);
		$this->assertTrue($state['myBool']->getValue());
	}
	
	public function testSetOutcomeValueNoVariable() {
	    $rule = $this->createComponentFromXml('
	        <setTemplateValue identifier="TPLXXXX">
	            <baseValue baseType="float">1337.1337</baseValue>
	        </setTemplateValue>
	    ');
	    
	    $processor = new SetTemplateValueProcessor($rule);
	    $tpl = new TemplateVariable('SCORE', Cardinality::SINGLE, BaseType::INTEGER);
	    $state = new State(array($tpl));
	    $processor->setState($state);
	    
	    $this->setExpectedException(
	        'qtism\\runtime\\rules\\RuleProcessingException',
	        "No variable with identifier 'TPLXXXX' to be set in the current state.",
	        RuleProcessingException::NONEXISTENT_VARIABLE
	    );
	    
	    $processor->process();
	}
}