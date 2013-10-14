<?php
/**
 * File containing the eZImageFile class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZImageFile ezimagefile.php
  \ingroup eZDatatype
  \brief The class eZImageFile handles registered images

*/

class eZImageFile extends eZPersistentObject
{
    static function definition()
    {
        static $definition = array( 'fields' => array( 'id' => array( 'name' => 'id',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'contentobject_attribute_id' => array( 'name' => 'ContentObjectAttributeID',
                                                                                'datatype' => 'integer',
                                                                                'default' => 0,
                                                                                'required' => true,
                                                                                'foreign_class' => 'eZContentObjectAttribute',
                                                                                'foreign_attribute' => 'id',
                                                                                'multiplicity' => '1..*' ),
                                         'filepath' => array( 'name' => 'Filepath',
                                                              'datatype' => 'string',
                                                              'default' => '',
                                                              'required' => true ) ),
                      'keys' => array( 'id' ),
                      'class_name' => 'eZImageFile',
                      'name' => 'ezimagefile' );
        return $definition;
    }

    static function create( $contentObjectAttributeID, $filepath  )
    {
        $row = array( "contentobject_attribute_id" => $contentObjectAttributeID,
                      "filepath" => $filepath );
        return new eZImageFile( $row );
    }

    static function fetchForContentObjectAttribute( $contentObjectAttributeID, $asObject = false )
    {
        $rows = eZPersistentObject::fetchObjectList( eZImageFile::definition(),
                                                      null,
                                                      array( "contentobject_attribute_id" => $contentObjectAttributeID ),
                                                      null,
                                                      null,
                                                      $asObject );
        if ( !$asObject )
        {
            $files = array();
            foreach ( $rows as $row )
            {
                $files[] = $row['filepath'];
            }
            return array_unique( $files );
        }
        else
            return $rows;
    }

    /**
     * Looks up ezcontentobjectattribute entries matching an image filepath and
     * a contentobjectattribute ID
     *
     * @param string $filePath file path to look up as URL in the XML string
     * @param int $contentObjectAttributeID
     *
     * @return array An array with a series of ezcontentobject_attribute's id, version and language_code
     */
    static function fetchImageAttributesByFilepath( $filepath, $contentObjectAttributeID )
    {
        $db = eZDB::instance();
        $contentObjectAttributeID = (int) $contentObjectAttributeID;

        $cond = array( 'id' => $contentObjectAttributeID );
        $fields = array( 'contentobject_id', 'contentclassattribute_id' );
        $limit = array( 'offset' => 0, 'length' => 1 );
        $rows = eZPersistentObject::fetchObjectList( eZContentObjectAttribute::definition(),
                                                     $fields,
                                                     $cond,
                                                     null,
                                                     $limit,
                                                     false );
        if ( count( $rows ) != 1 )
            return array();

        $contentObjectID = (int)( $rows[0]['contentobject_id'] );
        $contentClassAttributeID = (int)( $rows[0]['contentclassattribute_id'] );
        // Transform ", &, < and > to entities since they are being transformed in entities by DOM
        // See eZImageAliasHandler::initialize()
        // Ref https://jira.ez.no/browse/EZP-20090
        $filepath = $db->escapeString(
            htmlspecialchars(
                $filepath,
                // Forcing default flags to be able to specify encoding. See http://php.net/htmlspecialchars
                version_compare( PHP_VERSION, '5.4.0', '>=' ) ? ENT_COMPAT | ENT_HTML401 : ENT_COMPAT,
                'UTF-8'
            )
        );
        // Escape _ in like to avoid it to act as a wildcard !
        $filepath = addcslashes( $filepath, "_" );
        $query = "SELECT id, version, language_code
                  FROM   ezcontentobject_attribute
                  WHERE  contentobject_id = $contentObjectID AND
                         contentclassattribute_id = $contentClassAttributeID AND
                         data_text LIKE '%url=\"$filepath\"%'";
        if ( $db->databaseName() == 'oracle' )
        {
            $query .= " ESCAPE '\'";
        }
        $rows = $db->arrayQuery( $query );
        return $rows;
    }

    /**
     * Fetches the eZImageFile objects matching $filepath, optionally filtered by content object attribute id
     *
     * @param int $contentObjectAttributeId Optional content object attribute id to filter on. Set to false to disable.
     * @param string $filepath
     * @param bool $asObject
     *
     * @return eZImageFile
     *
     * @todo This method is actually wrong: the method could return multiple objects (EZP-21324)
     */
    static function fetchByFilepath( $contentObjectAttributeID, $filepath, $asObject = true )
    {
        // Fetch by file path without $contentObjectAttributeID
        if ( $contentObjectAttributeID === false )

            return eZPersistentObject::fetchObject( eZImageFile::definition(),
                                                    null,
                                                    array( 'filepath' => $filepath ),
                                                    $asObject );

        return eZPersistentObject::fetchObject( eZImageFile::definition(),
                                                null,
                                                array( 'contentobject_attribute_id' => $contentObjectAttributeID,
                                                       'filepath' => $filepath ),
                                                $asObject );
    }

    /**
     * Fetches unique eZImageFile data (as an array) for $filePath
     *
     * @param string $filePath
     *
     * @return array array of hash. Keys: contentobject_attribute_id, filepath
     */
    static function fetchListByFilePath( $filePath )
    {
        return eZPersistentObject::fetchObjectList(
            eZImageFile::definition(),
            array( 'contentobject_attribute_id', 'filepath' ),
            array( 'filepath' => $filePath ),
            null,
            null,
            false
        );
    }

    static function moveFilepath( $contentObjectAttributeID, $oldFilepath, $newFilepath )
    {
        $db = eZDB::instance();
        $db->begin();

        eZImageFile::removeFilepath( $contentObjectAttributeID, $oldFilepath );
        $result = eZImageFile::appendFilepath( $contentObjectAttributeID, $newFilepath );

        $db->commit();
        return $result;
    }

    static function appendFilepath( $contentObjectAttributeID, $filepath, $ignoreUnique = false )
    {
        if ( empty( $filepath ) )
            return false;

        if ( !$ignoreUnique )
        {
            // Fetch ezimagefile objects having the $filepath
            $imageFiles = eZImageFile::fetchByFilepath( false, $filepath, false );
            // Checking If the filePath already exists in ezimagefile table
            if ( isset( $imageFiles[ 'contentobject_attribute_id' ] ) )
                return false;
        }
        $fileObject = eZImageFile::fetchByFilepath( $contentObjectAttributeID, $filepath );
        if ( $fileObject )
            return false;
        $fileObject = eZImageFile::create( $contentObjectAttributeID, $filepath );
        $fileObject->store();
        return true;
    }

    static function removeFilepath( $contentObjectAttributeID, $filepath )
    {
        if ( empty( $filepath ) )
            return false;
        $fileObject = eZImageFile::fetchByFilepath( $contentObjectAttributeID, $filepath );
        if ( !$fileObject )
            return false;
        $fileObject->remove();
        return true;
    }

    static function removeForContentObjectAttribute( $contentObjectAttributeID )
    {
        eZPersistentObject::removeObject( eZImageFile::definition(), array( 'contentobject_attribute_id' => $contentObjectAttributeID ) );
    }

    /**
     * Tests if $filepath is referenced by content attributes of the same $attributeId in different $attributeVersion or $languageCode
     *
     * @param string $filepath
     * @param int $attributeId Content object attribute ID
     * @param int $attributeVersion Content object attribute version
     * @param string $languageCode Attribute language code
     * @return bool true if $filepath is referenced by other attributes, false otherwise
     */
    public static function isReferencedByOtherAttributes( $filepath, $attributeId, $attributeVersion, $languageCode )
    {
        $db = eZDB::instance();
        $filepath = $db->escapeString( $filepath );

        // Check eZImageFile and eZContentobjectAttribute for references to the alias file, in another version/language
        $rows = $db->arrayQuery(
            sprintf(
                "
                SELECT count(*) as count FROM ezcontentobject_attribute
                INNER JOIN ezimagefile ON ezcontentobject_attribute.id=ezimagefile.contentobject_attribute_id
                WHERE ezimagefile.filepath='%s'
                AND ezcontentobject_attribute.data_text LIKE '%%url=\"%s\"%%'
                AND ezcontentobject_attribute.id = %d
                AND ( ezcontentobject_attribute.version != %d OR ezcontentobject_attribute.language_code != '%s' )
                ",
                $filepath, $filepath,
                $attributeId,
                $attributeVersion,
                $languageCode
            )
        );

        return (int)$rows[0]['count'] > 0;
    }

    /**
     * Tests if there are ezimagefile rows with a different $attributeId that reference $filepath
     *
     * @param string $filepath
     * @param int $attributeId
     * @return bool
     */
    public static function isReferencedByOtherImageFiles( $filepath, $attributeId )
    {
        $db = eZDB::instance();
        $filepath = $db->escapeString( $filepath );

        $rows = $db->arrayQuery(
            sprintf(
                "
                SELECT count(*) as count FROM ezcontentobject_attribute
                INNER JOIN ezimagefile ON ezcontentobject_attribute.id=ezimagefile.contentobject_attribute_id
                WHERE ezimagefile.filepath='%s'
                AND ezcontentobject_attribute.id != %d
                ",
                $filepath,
                $attributeId
            )
        );

        return (int)$rows[0]['count'] > 0;
    }


    /// \privatesection
    public $ContentObjectAttributeID;
    public $Filepath;
}

?>
