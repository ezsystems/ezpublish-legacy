<?php
/**
 * File containing the eZContentClassNameList class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

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
                    if ( !$language instanceof eZContentLanguage )
                    {
                        eZDebug::writeError( $languageLocale . ' is not a instance of eZContentLanguage', __METHOD__ );
                        continue;
                    }
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
}

?>
