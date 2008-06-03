<?php
//
// Created on: <02-Oct-2006 13:37:23 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
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

//include_once( 'kernel/classes/ezserializedobjectnamelist.php' );
//include_once( 'kernel/classes/ezcontentclassname.php' );

class eZContentClassNameList extends eZSerializedObjectNameList
{
    function eZContentClassNameList( $serializedNameList = false )
    {
        eZSerializedObjectNameList::eZSerializedObjectNameList( $serializedNameList );
    }

    function create( $serializedNamesString = false )
    {
        $object = new eZContentClassNameList( $serializedNamesString );
        return $object;
    }

    function store( $contentClass )
    {
        if ( $this->hasDirtyData() && is_object($contentClass ) )
        {
            $classID = $contentClass->attribute( 'id' );
            $classVersion = $contentClass->attribute( 'version' );
            $languages = $contentClass->attribute( 'languages' );
            $initialLanguageID = $contentClass->attribute( 'initial_language_id' );

            // update existing
            $contentClassNames = eZContentClassName::fetchList( $classID, $classVersion, array_keys( $languages ) );
            foreach ( $contentClassNames as $className )
            {
                $languageLocale = $className->attribute( 'language_locale' );
                $className->setAttribute( 'name', $this->nameByLanguageLocale( $languageLocale ) );
                if ( $initialLanguageID == $className->attribute( 'language_id' ) )
                    $className->setAttribute( 'language_id', $initialLanguageID | 1 );

                $className->sync(); // avoid unnecessary sql-updates if nothing changed

                unset( $languages[$languageLocale] );
            }

            // create new
            if ( count( $languages ) > 0 )
            {
                foreach ( $languages as $languageLocale => $language )
                {
                    $languageID = $language->attribute( 'id' );
                    if ( $initialLanguageID == $languageID )
                        $languageID = $initialLanguageID | 1;

                    $className = new eZContentClassName( array( 'contentclass_id' => $classID,
                                                                'contentclass_version' => $classVersion,
                                                                'language_locale' => $languageLocale,
                                                                'language_id' => $languageID,
                                                                'name' => $this->nameByLanguageLocale( $languageLocale ) ) );
                    $className->store();
                }
            }

            $this->setHasDirtyData( false );
        }
    }

    static function remove( $contentClass )
    {
        eZContentClassName::removeClassName( $contentClass->attribute( 'id' ), $contentClass->attribute( 'version' ) );
    }
};

?>
