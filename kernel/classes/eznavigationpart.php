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

  If the view does not return any navigation part it will default to the Content part.

  The navigation parts are controlled by the \c menu.ini file, look for the
  \c NavigationPart group.
  You can easily add new entries in override files or in extensions by adding
  to the \c Part list.

*/

class eZNavigationPart
{
    function eZNavigationPart()
    {
    }

    /*!
     \static
     Will return the navigation part array if the identifier is valid,
     the default will be returned if the identifier is not valid.

     The navigation parts are defined in the INI file \c menu.ini
     under the \c NavigationPart group.
    */
    function fetchPartByIdentifier( $identifier )
    {
        $parts = eZNavigationPart::fetchList();

        if ( isset( $parts[$identifier] ) )
            return $parts[$identifier];

        // Return the first part which is the default
        return $parts[0];
    }

    /*!
     \static
     \return The current list of navigation part identifiers

     \note The list is cached in the global variable \c eZNavigationPartList.
    */
    function fetchList()
    {
        $list =& $GLOBALS['eZNavigationPartList'];
        if ( isset( $list ) )
            return $list;

        $ini =& eZINI::instance( 'menu.ini' );
        $parts = $ini->variable( 'NavigationPart', 'Part' );
        $list = array();
        foreach ( $parts as $identifier => $name )
        {
            $list[$identifier] = array( 'name' => ezi18n( 'kernel/navigationpart', $name, 'Navigation part' ),
                                        'identifier' => $identifier );
        }
        return $list;
    }

    /*!
     \private
     \note This funtion only exists for the i18n entries to be picked up by ezlupdate.
    */
    function i18nDummy()
    {
        ezi18n( 'kernel/navigationpart', 'Content structure', 'Navigation part' );
        ezi18n( 'kernel/navigationpart', 'Media library', 'Navigation part' );
        ezi18n( 'kernel/navigationpart', 'User accounts', 'Navigation part' );
        ezi18n( 'kernel/navigationpart', 'Webshop', 'Navigation part' );
        ezi18n( 'kernel/navigationpart', 'Design', 'Navigation part' );
        ezi18n( 'kernel/navigationpart', 'Setup', 'Navigation part' );
        ezi18n( 'kernel/navigationpart', 'My account', 'Navigation part' );
    }

}

?>
