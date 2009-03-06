<?php
/**
 * File containing the ezpTestRegressionSuite class.
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

/**
 * Regression test suite needed for regression tests like WebDAV regression
 * tests.
 *
 * Same code as ezcTestRegressionSuite which is in UnitTest from eZ
 * Components trunk.
 */
class ezpTestRegressionSuite extends PHPUnit_Framework_TestSuite
{
    public function __construct( $theClass = '', $name = '' )
    {
        $argumentsValid = false;

        if ( is_object( $theClass ) &&
             $theClass instanceof ReflectionClass )
        {
             $argumentsValid = true;
        }
        else if ( is_string( $theClass )
                  && $theClass !== ''
                  && class_exists( $theClass, false ) )
        {
            $argumentsValid = true;

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
            throw new InvalidArgumentException();
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

        if ( $constructor !== null &&
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
/*
        if ( $theClass->getName() !== 'ezcTestRegressionTest'
             && !$theClass->isSubclassOf( 'ezcTestRegressionTest' ) )
        {
            $this->addTest(
                new PHPUnit_Framework_Warning(
                    sprintf(
                        'Class "%s" is not a subclass of ezcTestRegressionTest.',
                        $theClass->getName()
                        )
                    )
                );
        }
*/
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
