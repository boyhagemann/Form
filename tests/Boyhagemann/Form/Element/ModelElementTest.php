<?php

namespace Boyhagemann\Form\Element;

use Mockery as m;

class ModelElementTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var TestModelElement
	 */
	protected $element;
    
    public function setUp()
	{
		$this->element = new TestModelElement();
	}

	public function testSetField()
	{
        $this->assertSame($this->element, $this->element->field('test'));
    }

	public function testSetKey()
	{
        $this->assertSame($this->element, $this->element->key('test'));
    }

	public function testSetModel()
	{
        $this->assertSame($this->element, $this->element->model('test'));
    }
    
	public function testSetContainer()
	{
        $container = m::mock('Illuminate\Container\Container');
        $this->assertSame($this->element, $this->element->container($container));
    }


	public function testSetAlias()
	{
        $this->assertSame($this->element, $this->element->alias('test'));
    }

	public function testSetChoices()
	{
        $this->assertSame($this->element, $this->element->choices(array('foo' => 'bar')));
    }

	public function testSetBefore()
	{
        $this->assertSame($this->element, $this->element->before(function() { }));
    }
    
	public function testSetAfter()
	{
        $this->assertSame($this->element, $this->element->after(function() { }));
    }
    
    /**
     * @depends testSetChoices
     */
    public function testGetChoices()
    {        
        $this->element->choices(array('foo' => 'bar'));        
        $this->assertSame(array('foo' => 'bar'), $this->element->getChoices());
    }

    
    public function testGetChoicesFromModelIfNoOptionsAreSet()
    {
        $model = $this->getModel();
                
        $this->element->model($model);
        
        $this->assertSame(array('foo' => 'bar'), $this->element->getChoices());
    }
    
    public function testGetChoicesFromQueryBuilderIfNoOptionsAreSet()
    {
        $qb = m::mock('Illuminate\Database\Query\Builder');
        $qb->shouldReceive('lists')->once()->andReturn(array('foo' => 'bar'));

        $this->element->query($qb);
        
        $this->assertSame(array('foo' => 'bar'), $this->element->getChoices());
    }
    
    public function testGetChoicesFromContainerIfNoOptionsAreSet()
    {
        $model = $this->getModel();
        
        $container = m::mock('Illuminate\Container\Container');
        $container->shouldReceive('make')->with('testmodel')->once()->andReturn($model);
                
        $this->element->model('testmodel')->container($container);
        
        $this->assertSame(array('foo' => 'bar'), $this->element->getChoices());
    }
    
    public function testGetChoicesBeforeAndAfterHook()
    {        
        $qb = m::mock('Illuminate\Database\Query\Builder');
        $qb->shouldReceive('lists')->once()->andReturn(array('foo' => 'bar'));
        $qb->shouldReceive('whereFoo')->with('bar')->twice();
                
        $this->element->query($qb)->before(function($qb) {
            $qb->whereFoo('bar');
        });   
        $this->element->query($qb)->after(function($qb) {
            $qb->whereFoo('bar');
        });
        
        $this->assertSame(array('foo' => 'bar'), $this->element->getChoices());
    }


    public function tearDown()
    {
        m::close();
    }

    
    /**
    * Mock some of the internals of DB connection
    * used for proper DateTime format. Assuming MySQL, but
    * any can be used, as long as its DateTIme format is consistent
    */
    protected function getModel()
    {        
        $model = m::mock('Illuminate\Database\Eloquent\Model');
        $model->shouldReceive('lists')->once()->andReturn(array('foo' => 'bar'));
        $model->shouldReceive('hasGetMutator')->andReturn(true);
        $model->shouldReceive('query')->andReturn(m::mock('Illuminate\Database\Query\Builder'));
        
        $model->shouldReceive('setConnectionResolver')->with($resolver = m::mock('Illuminate\Database\ConnectionResolverInterface'));
        $resolver->shouldReceive('connection')->andReturn($mockConnection = m::mock('Illuminate\Database\Connection'));
        $mockConnection->shouldreceive('getPostProcessor')->andReturn(m::mock('Illuminate\Database\Query\Processors\Processor'));
        $mockConnection->shouldReceive('getQueryGrammar')->andReturn($queryGrammar = m::mock('Illuminate\Database\Query\Grammars\Grammar'));
        $queryGrammar->shouldReceive('getDateFormat')->andReturn('Y-m-d H:i:s');
        
        return $model;
    }
}

class TestModelElement extends ModelElement {}