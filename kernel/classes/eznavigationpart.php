<?php
//
// Definition of eZNavigationPart class
//
// Created on: <18-Feb-2003 11:38:57 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*!
  \class eZNavigationPart eznavigationpart.php
  \brief eZNavigationPart handles grouping of functions across modules
  \ingroup eZKernel

A navigation part is a group of functions which belongs together. Every view can
return the navigation part it should use. It is up to the view to return the
proper navigation part. Views can internally check which navigation part to use,
in the case of content/view the view will check the navigation part set in
the section setup and use this.

If the view does not return any navigation part it will default
*/

class eZNavigationPart
{
    function eZNavigationPart()
    {
    }

    /*!
     \static
     Will return the navigation part array if the identifier is valid,
     the default will be returned if the identifier is not valid
    */
    function &fetchPartByIdentifier( $identifier )
    {
        $navigationParts = array( array( 'name' => 'Content',
                                         'identifier' => 'ezcontentnavigationpart' ),
                                  array( 'name' => 'Media',
                                         'identifier' => 'ezmedianavigationpart' ),
                                  array( 'name' => 'Shop',
                                         'identifier' => 'ezshopnavigationpart' ),
                                  array( 'name' => 'User',
                                         'identifier' => 'ezusernavigationpart' ),
		                  array( 'name' => 'Design',
                                         'identifier' => 'ezdesignnavigationpart' ),
                                  array( 'name' => 'Configuration',
                                         'identifier' => 'ezsetupnavigationpart' ),
                                  array( 'name' => 'My',
                                         'identifier' => 'ezmynavigationpart' ) );

        foreach ( $navigationParts as $part )
        {
            if ( $part['identifier'] == $identifier )
                return $part;
        }

        // Return the default part
        return array( 'name' => 'Content',
                      'identifier' => 'ezcontentnavigationpart' );
    }
}

?>
