<?php
/**
 * File containing the eZShopAccountHandler class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

class eZShopAccountHandler
{
    function eZShopAccountHandler()
    {

    }

    /**
     * Returns a shared instance of the eZShopAccountHandler class
     * as defined in shopaccount.ini[HandlerSettings]Repositories
     * and ExtensionRepositories.
     *
     * @return eZDefaultShopAccountHandler Or similar clases.
     */
    static function instance()
    {
        $accountHandler = null;
        if ( eZExtension::findExtensionType( array( 'ini-name' => 'shopaccount.ini',
                                                    'repository-group' => 'HandlerSettings',
                                                    'repository-variable' => 'Repositories',
                                                    'extension-group' => 'HandlerSettings',
                                                    'extension-variable' => 'ExtensionRepositories',
                                                    'type-group' => 'AccountSettings',
                                                    'type-variable' => 'Handler',
                                                    'alias-group' => 'AccountSettings',
                                                    'alias-variable' => 'Alias',
                                                    'subdir' => 'shopaccounthandlers',
                                                    'type-directory' => false,
                                                    'extension-subdir' => 'shopaccounthandlers',
                                                    'suffix-name' => 'shopaccounthandler.php' ),
                                             $out ) )
        {
            $filePath = $out['found-file-path'];
            include_once( $filePath );
            $class = $out['type'] . 'ShopAccountHandler';
            $accountHandler = new $class( );
        }
        else
        {
            $accountHandler = new eZDefaultShopAccountHandler();
        }
        return $accountHandler;
    }
}

?>
