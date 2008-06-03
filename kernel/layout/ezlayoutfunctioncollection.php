<?php
//
// Definition of eZLayoutFunctionCollection class
//
// Created on: <21-Nov-2002 12:36:51 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

/*! \file ezlayoutfunctioncollection.php
*/

/*!
  \class eZLayoutFunctionCollection ezlayoutfunctioncollection.php
  \brief The class eZLayoutFunctionCollection does

*/

//include_once( 'kernel/error/errors.php' );

class eZLayoutFunctionCollection
{
    /*!
     Constructor
    */
    function eZLayoutFunctionCollection()
    {
    }

    function fetchSitedesignList()
    {
        //include_once( 'lib/ezutils/classes/ezini.php' );
        $contentINI = eZINI::instance( 'content.ini' );
        if ( $contentINI->hasVariable( 'VersionView', 'AvailableSiteDesigns' ) )
        {
            $sitedesignList = $contentINI->variableArray( 'VersionView', 'AvailableSiteDesigns' );
        }
        else if ( $contentINI->hasVariable( 'VersionView', 'AvailableSiteDesignList' ) )
        {
            $sitedesignList = $contentINI->variable( 'VersionView', 'AvailableSiteDesignList' );
        }
        if ( !$sitedesignList )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => eZError::KERNEL_NOT_FOUND ) );
        return array( 'result' => $sitedesignList );
    }

}

?>
