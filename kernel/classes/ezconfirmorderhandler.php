<?php
/**
 * File containing the eZConfirmOrderHandler class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZConfirmOrderHandler ezconfirmorderhandler.php
  \brief The class eZConfirmOrderHandler does

*/

class eZConfirmOrderHandler
{
    /*!
     Constructor
    */
    function eZConfirmOrderHandler()
    {
    }

    /**
     * Returns a shared instance of the eZConfirmOrderHandler class
     * as defined in shopaccount.ini[HandlerSettings]Repositories
     * and ExtensionRepositories.
     *
     * @return eZDefaultConfirmOrderHandler Or similar clases.
     */
    static function instance()
    {
        $confirmOrderHandler = null;
        if ( eZExtension::findExtensionType( array( 'ini-name' => 'shopaccount.ini',
                                                    'repository-group' => 'HandlerSettings',
                                                    'repository-variable' => 'Repositories',
                                                    'extension-group' => 'HandlerSettings',
                                                    'extension-variable' => 'ExtensionRepositories',
                                                    'type-group' => 'ConfirmOrderSettings',
                                                    'type-variable' => 'Handler',
                                                    'alias-group' => 'ConfirmOrderSettings',
                                                    'alias-variable' => 'Alias',
                                                    'subdir' => 'confirmorderhandlers',
                                                    'type-directory' => false,
                                                    'extension-subdir' => 'confirmorderhandlers',
                                                    'suffix-name' => 'confirmorderhandler.php' ),
                                             $out ) )
        {
            $filePath = $out['found-file-path'];
            include_once( $filePath );
            $class = $out['type'] . 'ConfirmOrderHandler';
            return new $class();
        }

        return new eZDefaultConfirmOrderHandler();
    }

}

?>
