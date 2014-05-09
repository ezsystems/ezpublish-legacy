<?php
/**
 * File containing the language_switcher template operator
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

class ezpLanguageSwitcherOperator
{
    function __construct()
    {
    }

    function operatorList()
    {
        return array( 'language_switcher' );
    }

    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        return array( 'language_switcher' => array( 'destination' => array( 'type' => 'string',
                                                                            'required' => false,
                                                                            'default' => '' ) ) );
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters, $placement )
    {
        $destination = $namedParameters['destination'];

        switch ( $operatorName )
        {
            case 'language_switcher':
            {
                $ini = eZINI::instance();
                if ( !$ini->hasVariable( 'RegionalSettings', 'LanguageSwitcherClass' ) )
                {
                    return;
                }

                // Append original query string if no query string has already been passed in $destination
                if ( strpos( $destination, '?' ) === false )
                    $destination .= eZSys::queryString();

                $className = $ini->variable( 'RegionalSettings', 'LanguageSwitcherClass' );
                $operatorValue = call_user_func( array( $className, 'setupTranslationSAList' ), $destination );
            } break;
        }
    }
}

?>
