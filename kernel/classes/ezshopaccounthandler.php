<?php
//
// Definition of eZShopAccountHandler class
//
// Created on: <12-Feb-2003 16:50:52 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

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
