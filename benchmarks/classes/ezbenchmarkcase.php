<?php
/**
 * File containing the eZBenchmarkCase class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZBenchmarkCase ezbenchmarkcase.php
  \ingroup eZTest
  \brief eZBenchmarkCase provides a base class for doing automated benchmarks

  This class provides basic functionality and interface
  for creating benchmarks. It keeps a list of marks and
  a name which are accessible with markList() and name().

  To add new tests use addMark() or addFunctionMark()
  with the appropriate mark data.

  The methods prime() and cleanup() will be called before
  and after the mark itself is handled. This allows for priming
  certain values for the mark and cleaning up afterwards.

  To create a mark case inherit this class and create some mark methods
  that takes one parameter \a $tr which is the current test runner and
  a $parameter which is optional parameters added to the mark entry.
  The constructor must call the eZBenchmarkCase constructor with a useful
  name and setup some test methods with addMark() and addFunctionMark().

  For running the marks you must pass the case to an eZBenchmarkRunner instance.

  \code
class MyTest extends eZBenchmarkCase
{
    function MyTest()
    {
        $this->eZBenchmarkCase( 'My test case' );
        $this->addmark( 'markFunctionA', 'Addition mark' );
        $this->addFunctionTest( 'MyFunctionMark', 'Addition mark 2' );
    }

    function markFunctionA( &$tr, $parameter )
    {
        $a = 1 + 2;
    }
}

function MyFunctionMark( &$tr, $parameter )
{
    $a = 1 + 2;
}

$case = new MyTest();
$runner = new eZBenchmarkCLIRunner();
$runner->run( $case );
  \endcode

*/

require_once( 'lib/ezutils/classes/ezdebug.php' );

class eZBenchmarkCase extends eZBenchmarkUnit
{
    /*!
     Constructor
    */
    function eZBenchmarkCase( $name = false )
    {
        $this->eZBenchmarkUnit( $name );
    }

    function addMark( $method, $name, $parameter = false )
    {
        if ( !method_exists( $this, $method ) )
        {
            eZDebug::writeWarning( "Mark method $method in mark " . $this->Name . " does not exist, cannot add", __METHOD__ );
        }
        if ( !$name )
            $name = $method;
        $this->addEntry( array( 'name' => $name,
                                'object' => &$this,
                                'method' => $method,
                                'parameter' => $parameter ) );
    }

    function addFunctionMark( $function, $name, $parameter = false )
    {
        if ( !function_exists( $function ) )
        {
            eZDebug::writeWarning( "Mark function $function does not exist, cannot add to mark " . $this->Name, __METHOD__ );
        }
        if ( !$name )
            $name = $function;
        $this->addEntry( array( 'name' => $name,
                                'function' => $function,
                                'parameter' => $parameter ) );
    }
}

?>
