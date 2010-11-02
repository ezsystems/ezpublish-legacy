<?php
/**
 * Special suite for the regression tests, properly sets up one
 * test entry per test file (.in)
 *
 * <code>
 * </code>
 *
 * @package
 * @version //autogen//
 */
class RegressionSuite extends PHPUnit_Framework_TestSuite
{
    public function __construct($theClass = '', $name = '')
    {
        $argumentsValid = FALSE;

        if ( is_object($theClass) &&
             $theClass instanceof ReflectionClass)
        {
             $argumentsValid = TRUE;
        }
        else if ( is_string($theClass) && $theClass !== ''
                  && class_exists( $theClass, FALSE ) )
        {
            $argumentsValid = TRUE;

            if ( $name == '' )
            {
                $name = $theClass;
            }

            $theClass = new ReflectionClass( $theClass );
        }
        else if ( is_string( $theClass ) )
        {
            $this->setName( $theClass );
            return;
        }

        if ( !$argumentsValid )
        {
            throw new InvalidArgumentException;
        }

        if ( $name != '' )
        {
            $this->setName( $name );
        }
        else
        {
            $this->setName( $theClass->getName() );
        }

        $constructor = $theClass->getConstructor();

        if ( $constructor !== NULL &&
             !$constructor->isPublic() )
        {
            $this->addTest(
                new PHPUnit_Framework_Warning(
                    sprintf(
                        'Class "%s" has no public constructor.',
                        $theClass->getName()
                        )
                    )
                );

            return;
        }

        $names = array();

        if ( $theClass->getName() != 'TemplateTest' && !$theClass->isSubclassOf( 'TemplateTest' ) )
        {
            $this->addTest(
                new PHPUnit_Framework_Warning(
                    sprintf(
                        'Class "%s" is not a subclass of TemplateTest.',
                        $theClass->getName()
                        )
                    )
                );
        }

        $mainTest = $theClass->newInstance();
        $files = $mainTest->getFiles();

        foreach ( $files as $fileEntry )
        {
            $this->addRegressionTestFile( $fileEntry['file'], $mainTest );
        }

        $tests = $this->tests();
        if ( empty( $tests ) )
        {
            $this->addTest(
                new PHPUnit_Framework_Warning(
                    sprintf(
                        'No regression tests found in class "%s".',
                        $theClass->getName()
                        )
                    )
                );
        }
    }

    public function addRegressionTestFile( $file, $mainTest )
    {
        $test = clone $mainTest;
        $test->setCurrentFile( $file );
        $this->addTest( $test );
    }

}

?>
