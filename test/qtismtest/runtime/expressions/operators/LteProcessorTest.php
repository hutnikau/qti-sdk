<?php
namespace qtismtest\runtime\expressions\operators;

use qtismtest\QtiSmTestCase;
use qtism\common\datatypes\Boolean;
use qtism\common\datatypes\Integer;
use qtism\common\datatypes\Float;
use qtism\runtime\common\RecordContainer;
use qtism\common\datatypes\Point;
use qtism\runtime\expressions\operators\LteProcessor;
use qtism\runtime\expressions\operators\OperandsCollection;

class LteProcessorTest extends QtiSmTestCase {
	
	public function testLte() {
		$expression = $this->createFakeExpression();
		$operands = new OperandsCollection();
		$operands[] = new Float(0.5);
		$operands[] = new Integer(1);
		$processor = new LteProcessor($expression, $operands);
		$result = $processor->process();
		$this->assertInstanceOf('qtism\\common\\datatypes\\Boolean', $result);
		$this->assertTrue($result->getValue());
		
		$operands->reset();
		$operands[] = new Integer(1);
		$operands[] = new Float(0.5);
		$result = $processor->process();
		$this->assertInstanceOf('qtism\\common\\datatypes\\Boolean', $result);
		$this->assertFalse($result->getValue());
		
		$operands->reset();
		$operands[] = new Integer(1);
		$operands[] = new Integer(1);
		$result = $processor->process();
		$this->assertInstanceOf('qtism\\common\\datatypes\\Boolean', $result);
		$this->assertTrue($result->getValue());
	}
	
	public function testNull() {
		$expression = $this->createFakeExpression();
		$operands = new OperandsCollection();
		$operands[] = new Integer(1);
		$operands[] = null;
		$processor = new LteProcessor($expression, $operands);
		$result = $processor->process();
		$this->assertSame(null, $result);
	}
	
	public function testWrongBaseTypeOne() {
		$expression = $this->createFakeExpression();
		$operands = new OperandsCollection();
		$operands[] = new Integer(1);
		$operands[] = new Boolean(true);
		$processor = new LteProcessor($expression, $operands);
		$this->setExpectedException('qtism\\runtime\\expressions\\ExpressionProcessingException');
		$result = $processor->process();
	}
	
	public function testWrongBaseTypeTwo() {
		$expression = $this->createFakeExpression();
		$operands = new OperandsCollection();
		$operands[] = new Point(1, 2);
		$operands[] = new Integer(2);
		$processor = new LteProcessor($expression, $operands);
		$this->setExpectedException('qtism\\runtime\\expressions\\ExpressionProcessingException');
		$result = $processor->process();
	}
	
	public function testWrongCardinality() {
		$expression = $this->createFakeExpression();
		$operands = new OperandsCollection();
		$operands[] = new RecordContainer(array('A' => new Integer(1)));
		$operands[] = new Integer(2);
		$processor = new LteProcessor($expression, $operands);
		$this->setExpectedException('qtism\\runtime\\expressions\\ExpressionProcessingException');
		$result = $processor->process();
	}
	
	public function testNotEnoughOperands() {
		$expression = $this->createFakeExpression();
		$operands = new OperandsCollection();
		$this->setExpectedException('qtism\\runtime\\expressions\\ExpressionProcessingException');
		$processor = new LteProcessor($expression, $operands);
	}
	
	public function testTooMuchOperands() {
		$expression = $this->createFakeExpression();
		$operands = new OperandsCollection(array(new Integer(1), new Integer(2), new Integer(3)));
		$this->setExpectedException('qtism\\runtime\\expressions\\ExpressionProcessingException');
		$processor = new LteProcessor($expression, $operands);
	}
	
	public function createFakeExpression() {
		return $this->createComponentFromXml('
			<lte>
				<baseValue baseType="float">9.9</baseValue>
				<baseValue baseType="integer">10</baseValue>
			</lte>
		');
	}
}