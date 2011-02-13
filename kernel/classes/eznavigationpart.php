<?php
//
// Definition of eZNavigationPart class
//
// Created on: <18-Feb-2003 11:38:57 bf>
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
    /*!
     \static
     Will return the navigation part array if the identifier is valid,
     the default will be returned if the identifier is not valid.

     The navigation parts are defined in the INI file \c menu.ini
     under the \c NavigationPart group.
    */
    static function fetchPartByIdentifier( $identifier )
    {
        $parts = eZNavigationPart::fetchList();

        if ( isset( $parts[$identifier] ) )
            return $parts[$identifier];

        // Return the first part which is the default
        if ( isset( $parts[0] ) )
            return $parts[0];

        return false;
    }

    /*!
     \static
     \return The current list of navigation part identifiers

     \note The list is cached in the global variable \c eZNavigationPartList.
    */
    static function fetchList()
    {
        $list =& $GLOBALS['eZNavigationPartList'];
        if ( isset( $list ) )
            return $list;

        $ini = eZINI::instance( 'menu.ini' );
        $parts = $ini->variable( 'NavigationPart', 'Part' );
        $list = array();
        foreach ( $parts as $identifier => $name )
        {
            $list[$identifier] = array( 'name' => ezpI18n::tr( 'kernel/navigationpart', $name, 'Navigation part' ),
                                        'identifier' => $identifier );
        }
        return $list;
    }

    /*!
     \private
     \note This funtion only exists for the i18n entries to be picked up by ezlupdate.
    */
    private static function i18nDummy()
    {
        ezpI18n::tr( 'kernel/navigationpart', 'Content structure', 'Navigation part' );
        ezpI18n::tr( 'kernel/navigationpart', 'Media library', 'Navigation part' );
        ezpI18n::tr( 'kernel/navigationpart', 'User accounts', 'Navigation part' );
        ezpI18n::tr( 'kernel/navigationpart', 'Webshop', 'Navigation part' );
        ezpI18n::tr( 'kernel/navigationpart', 'Design', 'Navigation part' );
        ezpI18n::tr( 'kernel/navigationpart', 'Setup', 'Navigation part' );
        ezpI18n::tr( 'kernel/navigationpart', 'My account', 'Navigation part' );
    }

}

?>
