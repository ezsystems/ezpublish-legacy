<?php
/**
 * File containing the eZImageFile class.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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
    function eZImageFile( $row )
    {
        $this->eZPersistentObject( $row );
    }

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
     * Returns images that are safe for removal for a specific $contentObjectAttributeID
     *
     * The ezimagefile table references in which attributes image files are used. This function returns
     * for a given $contentObjectAttributeID, the list of image files solely owned it.
     *
     * @param int $contentObjectAttributeID Content object acctribute ID
     *
     * @return array
     */
    public static function fetchRemovableImagesFromObjectAttribute( $contentObjectAttributeID )
    {
        $result = array();

        foreach (
            eZDB::instance()->arrayQuery(
                "SELECT i1.filepath ".
                "FROM ezimagefile AS i1 ".
                "LEFT JOIN ezimagefile AS i2 ON i1.filepath = i2.filepath AND i1.id < i2.id ".
                "WHERE i1.contentobject_attribute_id = " . (int)$contentObjectAttributeID . " AND i2.contentobject_attribute_id IS NULL"
            ) as $row
        )
        {
            $result[] = $row["filepath"];
        }

        return $result;
    }

    /**
     * Performs possible cleanup after an update to the alias path is performed.
     *
     * @param eZContentObjectAttribute $contentObjectAttribute
     */
    public static function cleanupDataAfterAliasPathUpdate( eZContentObjectAttribute $contentObjectAttribute )
    {
        $urlSet = array();
        $db = eZDB::instance();
        $contentObjectAttributeId = (int)$contentObjectAttribute->attribute( "id" );

        foreach (
            $db->arrayQuery(
                "SELECT data_text FROM ezcontentobject_attribute WHERE id = " . $contentObjectAttributeId
            ) as $row
        )
        {
            foreach ( simplexml_load_string( $row["data_text"] )->xpath( "//*/@url" ) as $url )
            {
                $url = (string)$url;

                if ( $url === "" )
                    continue;

                // generateSQLINStatement does not add quotes when casting to string
                $urlSet["'" . $db->escapeString( (string)$url ) . "'"] = true;
            }
        }

        $db->query(
            "DELETE FROM ezimagefile ".
            "WHERE contentobject_attribute_id = $contentObjectAttributeId AND " .
            $db->generateSQLINStatement( array_keys( $urlSet ), "filepath", true, false, "string" )
        );
    }

    /**
     * Looks up ezcontentobjectattribute entries matching an image filepath and
     * a contentobjectattribute ID
     *
     * @param string $filePath file path to look up as URL in the XML string
     * @param int $contentObjectAttributeID
     *
     * @return array An array of content object attribute ids and versions of
     *               image files where the url is referenced
     *
     * @todo Rewrite ! A where data_text LIKE '%xxx%' is a resource hog !
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
        $query = "SELECT id, version
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


    /// \privatesection
    public $ID;
    public $ContentObjectAttributeID;
    public $Filepath;
}

?>
