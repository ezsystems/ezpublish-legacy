<?php
//
// Definition of eZContentClassExportHandler class
//
// Created on: <23-Jul-2003 16:11:42 amos>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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

/*! \file ezcontentclassexporthandler.php
*/

/*!
  \class eZContentClassExportHandler ezcontentclassexporthandler.php
  \brief The class eZContentClassExportHandler does

*/

include_once( 'lib/ezxml/classes/ezxml.php' );
include_once( 'kernel/classes/ezcontentclass.php' );

class eZContentClassExportHandler
{
    /*!
     Constructor
    */
    function eZContentClassExportHandler()
    {
    }

    function handle( &$package, $parameters )
    {
        print( "Handling content classes\n" );
        print_r( $parameters );
        $classList = array();
        for ( $i = 0; $i < count( $parameters ); ++$i )
        {
            $parameter = $parameters[$i];
            if ( $parameter == '-class' )
            {
                $classList = explode( ',', $parameters[$i+1] );
                ++$i;
            }
        }
        print_r( $classList );
        if ( count( $classList ) > 0 )
        {
            foreach ( $classList as $classID )
            {
                if ( is_numeric( $classID ) )
                    $class =& eZContentClass::fetch( $classID );
                if ( !$class )
                    continue;
                $classNode =& eZDomDocument::createElementNode( 'content-class' );
                $classNode->appendChild( eZDomDocument::createElementTextNode( 'name',
                                                                               $class->attribute( 'name' ) ) );
                $package->appendInstall( 'part', false, false, true,
                                         array( 'content' => $classNode ) );
            }
        }
    }
}

?>
