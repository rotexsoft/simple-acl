<?php
/** @noinspection PhpFullyQualifiedNameUsageInspection */
declare(strict_types=1);
use SimpleAcl\Utils;

/**
 * Description of ArraysCollectionTest
 *
 * @author aadegbam
 */
class UtilsTest extends \PHPUnit\Framework\TestCase{
    
    protected function setUp(): void { 
        
        parent::setUp();
    }

    public function testThat_array_key_first_WorksAsExpected() {
        
        $arr = [];
        $arr1 = ['One', 'Two', 'Three', 'Four'];
        $arr2 = ['a'=>'One', 'Two', 'Three', 'Four'];
        $arr3 = ['z'=>'One', 'Two', 'Three', 'Four'];
      
        $this->assertEquals(null, Utils::array_key_first($arr));
        $this->assertEquals(0, Utils::array_key_first($arr1));
        $this->assertEquals('a', Utils::array_key_first($arr2));
        $this->assertEquals('z', Utils::array_key_first($arr3));
    }
    
    public function testThat_array_key_last_WorksAsExpected() {
        
        $arr = [];
        $arr1 = ['One', 'Two', 'Three', 'Four'];
        $arr2 = ['One', 'Two', 'Three', 'a'=>'Four'];
        $arr3 = ['One', 'Two', 'Three', 'z'=>'Four'];

        $this->assertEquals(null, Utils::array_key_last($arr));
        $this->assertEquals(3, Utils::array_key_last($arr1));
        $this->assertEquals('a', Utils::array_key_last($arr2));
        $this->assertEquals('z', Utils::array_key_last($arr3));
    }
    
    public function testThatGetClosureFromCallableWorksAsExpected() {
        
        $this->assertTrue(
            Utils::getClosureFromCallable('my_callback_function') instanceof Closure
        ); // from non-anonymous & non-class function
        
        $this->assertTrue(
            Utils::getClosureFromCallable([Ancestor::class, 'who']) instanceof Closure
        ); // static method call on a class
        
        $this->assertTrue(
            Utils::getClosureFromCallable([ (new Descendant() ), 'echoOut']) instanceof Closure
        ); // instance method call on a class instance
        
        $this->assertTrue(
            Utils::getClosureFromCallable(Descendant::class.'::who') instanceof Closure
        ); // static method call string syntax
        
        $this->assertTrue(
            Utils::getClosureFromCallable([Descendant::class, 'parent::who']) instanceof Closure
        ); // parent class' static method call
        
        $this->assertTrue(
            Utils::getClosureFromCallable( (new Descendant()) ) instanceof Closure
        ); // instance of class that has __invoke()
        
        $anon_func = function($a) {
            return $a * 2;
        };
        $this->assertTrue(
            Utils::getClosureFromCallable($anon_func) instanceof Closure
        ); // anonymous function a.k.a Closure
    }

    public function testThatBindObjectAndScopeToClosureWorksAsExpected() {
        
        $this->assertTrue(
            Utils::getClosureFromCallable('my_callback_function') instanceof Closure
        ); // from non-anonymous & non-class function
        
        $this->assertTrue(
            Utils::getClosureFromCallable([Ancestor::class, 'who']) instanceof Closure
        ); // static method call on a class
        
        $this->assertTrue(
            Utils::getClosureFromCallable([ (new Descendant() ), 'echoOut']) instanceof Closure
        ); // instance method call on a class instance
        
        $this->assertTrue(
            Utils::getClosureFromCallable(Descendant::class.'::who') instanceof Closure
        ); // static method call string syntax
        
        $this->assertTrue(
            Utils::getClosureFromCallable([Descendant::class, 'parent::who']) instanceof Closure
        ); // parent class' static method call
        
        $this->assertTrue(
            Utils::getClosureFromCallable( (new Descendant()) ) instanceof Closure
        ); // instance of class that has __invoke()
        
        $anon_func = function($a) {
            return $a * 2;
        };
        $this->assertTrue(
            Utils::bindObjectAndScopeToClosure($anon_func, $this) instanceof Closure
        ); // anonymous function a.k.a Closure

        $this->expectException(InvalidArgumentException::class);

        // Code should generate Exception: binding $this to a static Closure
        Utils::bindObjectAndScopeToClosure(
            Utils::getClosureFromCallable([Ancestor::class, 'who']),
            $this
        );
    }
    
    public function testThatGetThrowableAsStrWorksAsExpected() {
        
        $e1 = new DescendantException('Descendant Thrown', 911);
        $e2 = new AncestorException('Ancestor Thrown', 777, $e1);
        $e3 = new Exception('Base Thrown', 187, $e2);
        
        $ex_as_str = Utils::getThrowableAsStr($e3);

        $this->assertStringContainsString('187', $ex_as_str);
        $this->assertStringContainsString('Base Thrown', $ex_as_str);
        $this->assertStringContainsString('777', $ex_as_str);
        $this->assertStringContainsString('AncestorException', $ex_as_str);
        $this->assertStringContainsString('Ancestor Thrown', $ex_as_str);
        $this->assertStringContainsString('911', $ex_as_str);
        $this->assertStringContainsString('DescendantException', $ex_as_str);
        $this->assertStringContainsString('Descendant Thrown', $ex_as_str);
    }
    
    public function testThatStrtolowerWorksAsExpected() {
        
        $this->assertStringContainsString(Utils::strtolower('Base Thrown'), 'base thrown');
        $this->assertStringContainsString(Utils::strtolower('777'), '777');
    }
}

function my_callback_function() { echo 'hello world!'; }
