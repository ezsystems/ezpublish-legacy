<?php
//
// Definition of eZContentTranslation class
//
// Created on: <23-ñÎ×-2003 13:00:55 sp>
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

/*! \file ezcontenttranslation.php
*/

/*!
  \class eZContentTranslation ezcontenttranslation.php
  \brief The class eZContentTranslation does

*/

class eZContentTranslation extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZContentTranslation( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "name" => array( 'name' => "Name",
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ),
                                         "locale" => array( 'name' => "Locale",
                                                            'datatype' => 'string',
                                                            'default' => '',
                                                            'required' => true )
                                         ),
                      "keys" => array( "id" ),
                      "function_attributes" => array( 'locale_object' => 'localeObject' ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZContentTranslation",
                      "name" => "ezcontent_translation" );

    }

    function &createNew( $translationName, $translationLocale )
    {
        return new eZContentTranslation( array( 'name' => $translationName,
                                                'locale' => $translationLocale ) );
    }

    function fetchByLocale()
    {
    }

    function hasAttribute( $attribute )
    {
        return ( $attribute == 'locale_object' or
                 eZPersistentObject::hasAttribute( $attribute ) );
    }

    function &attribute( $attribute )
    {
        if ( $attribute == 'locale_object' )
            return $this->localeObject();
        else
            return eZPersistentObject::attribute( $attribute );
    }

    function &localeObject()
    {
        include_once( 'lib/ezlocale/classes/ezlocale.php' );
        $locale =& eZLocale::instance( $this->Locale );
        return $locale;
    }

    function fetch( $translationID )
    {
        return eZPersistentObject::fetchObject( eZContentTranslation::definition(),
                                                null, array('id' => $translationID ), true);
    }

    function hasTranslation( $translation )
    {
        $translationList =& eZPersistentObject::fetchObjectList( eZContentTranslation::definition(),
                                                                 null, array( 'locale' => $translation ), null,null,
                                                                 false );
        return $translationList !== null and count( $translationList ) > 0;
    }

    function &fetchList()
    {
        $translationList =& eZPersistentObject::fetchObjectList( eZContentTranslation::definition(),
                                                                 null, array(), null,null,
                                                                 true );
        include_once( 'kernel/classes/ezcontentobject.php' );
        $defaultLanguage =& eZContentObject::defaultLanguage();
        $foundDefaultLanguage = false;
        foreach ( $translationList as $translationItem )
        {
            if ( $translationItem->attribute( 'locale' ) == $defaultLanguage )
            {
                $foundDefaultLanguage = true;
                break;
            }
        }
        if ( !$foundDefaultLanguage )
        {
            include_once( 'lib/ezlocale/classes/ezlocale.php' );
            $defaultLanguageLocale =& eZLocale::instance( $defaultLanguage );
            $translationList[] = new eZContentTranslation( array( 'id' => null,
                                                                  'name' => $defaultLanguageLocale->languageName(),
                                                                  'locale' => $defaultLanguageLocale->localeCode() ) );
        }
        return $translationList;
    }

    function &fetchLocaleList()
    {
        $translationArray =&  eZPersistentObject::fetchObjectList( eZContentTranslation::definition(),
                                                                   null, array(), null,null,
                                                                   false );
        include_once( 'kernel/classes/ezcontentobject.php' );
        $defaultLanguage =& eZContentObject::defaultLanguage();
        $foundDefaultLanguage = false;
        $localeList = array();
        foreach ( array_keys( $translationArray ) as $key )
        {
            $localeList[] = $translationArray[$key]['locale'];
            if ( $translationArray[$key]['locale'] == $defaultLanguage )
            {
                $foundDefaultLanguage = true;
            }
        }
        if ( !$foundDefaultLanguage )
        {
            $localeList[] = $defaultLanguage;
        }
        return $localeList;
    }

    function updateObjectNames()
    {
        include_once( 'kernel/classes/ezcontentobject.php' );
        $defaultLanguage = eZContentObject::defaultLanguage();
        $newLanguage = $this->attribute( 'locale' );
        $db =& eZDB::instance();
        $existingNamesArray = $db->arrayQuery( "SELECT *
                                                FROM ezcontentobject_name
                                                WHERE content_translation = '$defaultLanguage'  " );
        $nameCount =  count( $existingNamesArray );
        for ( $i=0;$i<$nameCount;++$i )
        {
            $db->query( "INSERT INTO ezcontentobject_name(contentobject_id,
                                                          name,
                                                          content_version,
                                                          content_translation,
                                                          real_translation)
                                                  VALUES( '" . $existingNamesArray[$i]['contentobject_id'] . "',
                                                          '" .$existingNamesArray[$i]['name'] . "',
                                                          '" .$existingNamesArray[$i]['content_version'] . "',
                                                          '$newLanguage',
                                                          '$defaultLanguage' )" );
        }
    }

    function translatedObjectsCount()
    {
        $db =& eZDB::instance();
        $locale = $this->attribute( 'locale' );
        $query = "select count(distinct contentobject_id) as object_count from ezcontentobject_attribute where language_code='$locale'";
        $countResultArray = $db->arrayQuery( $query );
        return $countResultArray[0]['object_count'];
    }

    function remove()
    {
        $id = $this->attribute( 'id' );
        $locale = $this->attribute( 'locale' );
        $db =& eZDB::instance();
        $db->query( "DELETE from ezcontentobject_name WHERE content_translation = '$locale' " );
        $db->query( "DELETE from ezcontentobject_attribute WHERE language_code = '$locale'" );
        eZPersistentObject::remove();
    }
}

?>
