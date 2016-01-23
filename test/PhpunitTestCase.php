<?php


class PhpunitTestCase extends \PHPUnit_Framework_TestCase
{
    protected $instance;
    
    /**
     * Call an inaccessible method on the instance
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function callInaccessibleMethod($method, $args = []) {
    
        $method = new \ReflectionMethod($this->instance, $method);
    
        $method->setAccessible(true);
    
        return $method->invokeArgs($this->instance,$args);
    }

    /**
     * Set the value of an inaccessible property
     * @param string $propertyName
     * @param mixed $value
     */
    public function setInaccessiblePropertyValue($propertyName, $value) {
        
        $property = new \ReflectionProperty($this->instance, $propertyName);
        
        $property->setAccessible(true);
        
        $property->setValue($this->instance, $value);
        
    }

    /**
     * Get the value of an inaccessible property
     * @param $propertyName
     * @return mixed
     * @author GACH
     */
    public function getInaccessiblePropertyValue($propertyName) {

        $property = new \ReflectionProperty($this->instance, $propertyName);

        $property->setAccessible(true);

        return $property->getValue($this->instance);

    }

    /**
     * Create a mock object from an config array
     * @param string $originalClassName The full classname to mock
     * @param boolean $forAbstract 
     * @param array $config 
     * @param boolean $callOriginalConstructor
     * @param boolean $callOriginalClone
     * @param boolean $callAutoload
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getMockFromArray($originalClassName, $forAbstract = false, $config = [], 
            $callOriginalConstructor = false, $callOriginalClone = false, $callAutoload = true) {
        
        $mockClassName = 'Mock' . uniqid();
   
        if($forAbstract) {
            $mockAdapter = $this->getMockForAbstractClass(
                    $originalClassName, [], $mockClassName, $callOriginalConstructor, $callOriginalClone, $callAutoload,
                    array_keys($config)
            );
        } else {
            $mockAdapter = $this->getMock($originalClassName, array_keys($config),[], $mockClassName, 
                $callOriginalConstructor, $callOriginalClone, $callAutoload);
        } 
        
        foreach($config as $method => $methodConfig) {
            $matcher = isset($methodConfig['expects']) ? $methodConfig['expects'] : $this->once();
            $mockMethod = $mockAdapter
                ->expects($matcher)
                ->method($method)
            ;
            
            if(isset($methodConfig['will'])) {
                $mockMethod->will($methodConfig['will']);
            }
            if(isset($methodConfig['with'])) {
                if(!is_array($methodConfig['with'])) {
                    $methodConfig['with'] = (array) $methodConfig['with'];
                }
                call_user_func_array([$mockMethod , 'with'], $methodConfig['with']);
            }
        }
        return $mockAdapter;
    }

    protected function setMockInjector($className , $classMock) {

        $mock = $this->getMock('CrocCroc\Application\Injector\Container' , ['get' , 'has', 'unStoredNextObject']);

        $mock->expects($this->once())
                    ->method('get')
                    ->with($className)
                    ->willReturn($classMock);

        return $mock;

    }

    public function tearDown()
    {
        $this->instance = null;
    }
    
}