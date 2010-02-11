<?php
/**
 * File containing the options object for the eZExtension class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package kernel
 *
 */

/**
 * Class containing options for eZExtension, this option system is only used
 * by eZExtension::getHandlerClass so far.
 *
 * @property string $iniFile
 *      Contains the ini file to read the handler settings from.
 *
 * @property string $iniSection
 *      Contains the ini [section] where the handler settings are defined.
 *      Default : 'HandlerSettings'
 *
 * @property string $iniVariable
 *      Contains the variable name of the ini setting to read handler name from (it can be array or string).
 *      Default : 'HandlerClassName'
 *
 * @property string $handlerIndex
 *      Sets this if you need to pick a certain index in the ini setting (given that it is an array).
 *      Default : null
 *
 * @property string $callMethod
 *      Name of function to call on the object to see if handler is valid.
 *      Default : null
 *
 * @property array $handlerParams
 *      The list of parameters to pass to the handler
 *      Default : null
 * 
 * @property string $aliasSection
 *      Default : null
 *
 * @property string $aliasVariable
 *      Default : null
 *      
  * @property string $aliasOptionalIndex
 *      Default : null
 *
 * @throws ezcBasePropertyNotFoundException
 *         If $options contains an undefined property
 * @throws ezcBaseValueException
 *         If $options contains a property with an illegal value
 *
 * @param array $options
 *
 * @package kernel
 */

class ezpExtensionOptions extends ezcBaseOptions
{
    public function __construct( array $options = array() )
    {
        $this->iniFile        = '';
        $this->iniSection     = 'HandlerSettings';
        $this->iniVariable    = 'HandlerClassName';
        $this->handlerIndex   = null;
        $this->callMethod     = null;
        $this->handlerParams  = null;
        $this->aliasSection   = null;
        $this->aliasVariable  = null;
        $this->aliasOptionalIndex = null;

        parent::__construct( $options );
    }

    public function __set( $name, $value )
    {
        switch ( $name )
        {
            case 'iniFile':
            case 'iniSection':
            case 'iniVariable':
                if( !is_string( $value ) )
                {
                    throw new ezcBaseValueException( $name, $value );
                }
                $this->properties[$name] = $value;
                break;

            case 'handlerIndex':
            case 'callMethod':
            case 'aliasSection':
            case 'aliasVariable':
            case 'aliasOptionalIndex':
                if( $value !== null and !is_string( $value ) )
                {
                    throw new ezcBaseValueException( $name, $value );
                }
                $this->properties[$name] = $value;
                break;

            case 'handlerParams':
                if( $value !== null and !is_array( $value ) and count( $value ) <= 0 )
                {
                    throw new ezcBaseValueException( $name, $value );
                }
                $this->properties[$name] = $value;
                break;

            default:
                throw new ezcBasePropertyNotFoundException( $name );
        }
    }
}

?>
