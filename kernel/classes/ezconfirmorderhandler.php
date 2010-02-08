<?php
// Created on: <08-Aug-2006 15:14:44 bjorn>
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

/*! \file
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
