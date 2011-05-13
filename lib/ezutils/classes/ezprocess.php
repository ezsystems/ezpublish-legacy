<?php
/**
 * File containing the eZProcess class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZProcess ezprocess.php
  \ingroup eZUtils
  \brief Executes php scripts with parameters safely

*/
class eZProcess
{
    static function run( $file, $Params = array(), $params_as_var = false )
    {
        return eZProcess::instance()->runFile( $Params, $file, $params_as_var );
    }

    /*!
     Helper function, executes the file.
     */
    function runFile( $Params, $file, $params_as_var )
    {
        $Result = null;
        if ( $params_as_var )
        {
            foreach ( $Params as $key => $dummy )
            {
                if ( $key != "Params" and
                     $key != "this" and
                     $key != "file" and
                     !is_numeric( $key ) )
                {
                    ${$key} = $Params[$key];
                }
            }
        }

        if ( file_exists( $file ) )
        {
            $includeResult = include( $file );
            if ( empty( $Result ) &&
                 $includeResult != 1 )
            {
                $Result = $includeResult;
            }
        }
        else
            eZDebug::writeWarning( "PHP script $file does not exist, cannot run.",
                                   "eZProcess" );
        return $Result;
    }

    /**
     * Returns a shared instance of the eZProcess class
     *
     * @return eZProcess
     */
    static function instance()
    {
        if ( empty( $GLOBALS['eZProcessInstance'] ) )
        {
            $GLOBALS['eZProcessInstance'] = new eZProcess();
        }
        return $GLOBALS['eZProcessInstance'];
    }
}

?>
