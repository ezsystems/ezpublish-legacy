<?php
//
// Definition of eZShopAccountHandler class
//
// Created on: <12-Feb-2003 16:50:52 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

class eZShopAccountHandler
{
    function eZShopAccountHandler()
    {

    }

    /*!
     returns the current shop account instance
    */
    function &instance()
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
            include_once( 'kernel/classes/shopaccounthandlers/ezdefaultshopaccounthandler.php' );
            $accountHandler =& new eZDefaultShopAccountHandler();
        }
        return $accountHandler;
    }
}

?>
