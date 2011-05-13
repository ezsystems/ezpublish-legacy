<?php
/**
 * File containing the ezpINIHelper class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class ezpINIHelper
{
    /**
     * Changes an INI setting value
     *
     * @param string|array $file
     *        Either the INI filename (site.ini) or an array specifying the
     *        filename and rootdir: array( 'fre-FR.ini', 'share/locale' )
     * @param string $block INI block name
     * @param string $variable INI variable name
     * @param mixed $value The new value
     *
     * @see restoreINISettings() to restore all the modified INI settings
     */
    public static function setINISetting( $file, $block, $variable, $value )
    {
        if ( is_array( $file ) )
        {
            $ini = eZINI::instance( $file[0], $file[1] );
            $file = $file[1] . DIRECTORY_SEPARATOR . $file[0];
        }
        else
        {
            $ini = eZINI::instance( $file );
        }

        // backup the value
        self::$modifiedINISettings[] = array( $file, $block, $variable, $ini->variable( $block, $variable ) );

        // change the value
        $ini->setVariable( $block, $variable, $value );
    }

    /**
     * Restores all the INI settings previously modified using setINISetting
     * and clear list of modifed ini settings
     *
     * @return void
     */
    public static function restoreINISettings()
    {
        // restore each changed value in reverse order to be sure history is correct
        foreach ( array_reverse( self::$modifiedINISettings ) as $key => $values )
        {
            list( $file, $block, $variable, $value ) = $values;
            $ini = eZINI::instance( $file );
            $ini->setVariable( $block, $variable, $value );
        }
        self::$modifiedINISettings = array();
    }

    /**
     * Changes multiple INI settings values using setINISetting
     * @param array $settings set of INI settings, as an array of 4 values
     */
    public static function setINISettings( $settings )
    {
        foreach( $settings as $iniSettings )
        {
            list( $file, $block, $variable, $value ) = $iniSettings;
            self::setINISetting( $file, $block, $variable, $value );
        }
    }

    /**
     * Modified INI settings, as an array of 4 keys array:
     * file, block, variable, value
     * @var array
     */
    protected static $modifiedINISettings = array();
}

?>
