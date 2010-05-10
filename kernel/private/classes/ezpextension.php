<?php
/**
 * File containing the ezpExtension class.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * Object representing an eZ Publish extension
 */
class ezpExtension
{
    public $name;

    /**
     * Array of multiton instances (Multiton pattern)
     *
     * @see getInstance
     */
    private static $instances = array();

    /**
     * ezpExtension constructor.
     *
     * @param string $name Name of the extension
     */
    protected function __construct( $name )
    {
        $this->name = $name;
    }

    /**
     * ezpExtension constructor.
     *
     * @see $instances
     *
     * @param string $name Name of the extension
     * @return ezpExtension
     */
    public static function getInstance( $name )
    {
        if (! isset( self::$instances[$name] ) )
            self::$instances[$name] = new self( $name );

        return self::$instances[$name];
    }

    /**
     * Returns the loading order informations.
     * First tries extension.xml, then loading.php for backward compatibility
     *
     * @return array array( before => array( a, b ), after => array( c, d ) ) or an empty array if not available
     * @note This structure voluntarily matches the one from loading.php for now
     */
    public function getLoadingOrder()
    {
        if ( is_readable( $XMLDependencyFile = eZExtension::baseDirectory() . "/{$this->name}/extension.xml" ) )
        {
            libxml_use_internal_errors( true );
            $xml = simplexml_load_file( $XMLDependencyFile );
            // xml parsing error
            if ( $xml === false )
            {
                // @todo Add correct error handling
                eZDebug::writeError( libxml_get_errors(), "ezpExtension( {$this->name} )::getLoadingOrder()" );
                return null;
            }
            foreach( $xml->dependencies as $dependenciesNode )
            {
                foreach( $dependenciesNode as $dependencyType => $dependenciesNode )
                {
                    // @todo Use a mapping array instead
                    // @todo Implement error handling using exceptions
                    switch( $dependencyType )
                    {
                        case 'requires':
                            $relationship = 'after';
                            break;

                        case 'uses':
                            $relationship = 'after';
                            break;

                        case 'extends':
                            $relationship = 'before';
                            break;
                    }
                    if ( !isset( $return[$relationship] ) )
                        $return[$relationship] = array();

                    foreach( $dependenciesNode as $dependency )
                    {
                        $return[$relationship][] = (string)$dependency['name'];
                    }
                }
            }
            return $return;
        }
        // Backward compatibility layer with the temporary loading.php
        elseif ( is_readable( $PHPDependencyFile = eZExtension::baseDirectory() . "/{$this->name}/loading.php" ) )
        {
            return require $PHPDependencyFile;
        }
        // no dependency informations
        else
            return array();
    }

    /**
     * Returns the extension informations
     * Uses extension.xml by default, then tries ezinfo.php for backwards compatibility
     *
     * @since 4.4
     * @return array|null array of extension informations, or null if no source exists
     */
    public function getInfo()
    {
        // try extension.xml first
        if ( is_readable( $XMLFilePath = eZExtension::baseDirectory() . "/{$this->name}/extension.xml" ) )
        {
            $infoFields = array( 'name', 'version', 'copyright', 'license', 'info_url' );

            libxml_use_internal_errors( true );
            $xml = simplexml_load_file( $XMLFilePath );
            // xml parsing error
            if ( $xml === false )
            {
                // @todo Add correct error handling
                eZDebug::writeError( libxml_get_errors(), "ezpExtension({$this->name})::getInfo()" );
                return null;
            }
            $return = array();
            $metadataNode = $xml->metadata;

            // standard extension metadata
            foreach( $infoFields as $field )
            {
                if ( (string)$metadataNode->$field !== '' )
                    $return[$field] = (string)$metadataNode->$field;
            }

            // 3rd party software
            $index = 1;
            foreach( $metadataNode->software->uses as $software )
            {
                $label = "Includes the following third-party software";
                if ( $index > 1 )
                    $label .= " (" . $index . ")";

                foreach( $infoFields as $field )
                {
                    if ( (string)$software->$field !== '' )
                        $return[$label][$field] = (string)$software->$field;
                }
                $index++;
            }

            return $return;
        }
        // then try ezinfo.php, for backwards compatibility
        elseif ( is_readable( $infoFilePath = eZExtension::baseDirectory() . "/{$this->name}/ezinfo.php" ) )
        {
            include_once( $infoFilePath );
            $className = $this->name . 'Info';
            if ( is_callable( array( $className, 'info' ) ) )
            {
                $result = call_user_func_array( array( $className, 'info' ), array() );
                if ( is_array( $result ) )
                {
                    return $result;
                }
            }
        }
        else
        {
            return null;
        }
    }
}
?>
