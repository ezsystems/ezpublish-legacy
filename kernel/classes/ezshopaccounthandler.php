<?php
/**
 * File containing the eZShopAccountHandler class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

class eZShopAccountHandler
{
    /**
     * Returns a shared instance of the eZShopAccountHandler class
     * as defined in shopaccount.ini[HandlerSettings]Repositories
     * and ExtensionRepositories.
     *
     * @return eZDefaultShopAccountHandler Or similar classes.
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
