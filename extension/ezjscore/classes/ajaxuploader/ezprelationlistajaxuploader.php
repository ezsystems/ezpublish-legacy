<?php
/**
 * File containing the ezpRelationListAjaxUploader class.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package ezjscore
 * @subpackage ajaxuploader
 */

/**
 * This class handles AJAX Upload for eZObjectRelationList attributes
 *
 * @package ezjscore
 * @subpackage ajaxuploader
 */
class ezpRelationListAjaxUploader implements ezpAjaxUploaderHandlerInterface
{
    /**
     * The attribute where the uploaded file will be added
     *
     * @var eZContentObjectAttribute
     */
    protected $attribute;

    /**
     * Constructor
     *
     * @param int $attributeId
     * @param int $version
     *
     * @throw InvalidArgumentException if the attribute cannot be loaded
     */
    public function __construct( $attributeId, $version )
    {
        $this->attribute = eZContentObjectAttribute::fetch( $attributeId, $version );
        if ( !$this->attribute instanceof eZContentObjectAttribute )
        {
            throw new InvalidArgumentException(
                ezpI18n::tr(
                    'extension/ezjscore/ajaxuploader',
                    'Provided attribute id and version number are invalid.'
                )
            );
        }
    }

    /**
     * Checks if a file can be uploaded
     *
     * @return boolean
     */
    public function canUpload()
    {
        $access = eZUser::instance()->hasAccessTo( 'content', 'create' );
        if ( $access['accessWord'] === 'no' )
        {
            return false;
        }
        $ini = eZINI::instance( 'upload.ini' );
        $uploadableClassList = $ini->variable( 'CreateSettings', 'MimeClassMap' );
        $uploadableClassList[] = $ini->variable( 'CreateSettings', 'DefaultClass' );
        $classContent = $this->attribute->attribute( 'class_content' );

        $intersect = array_intersect( $classContent['class_constraint_list'], $uploadableClassList );
        if ( !empty( $classContent['class_constraint_list'] ) && empty( $intersect ) )
        {
            return false;
        }
        return true;
    }
    /**
     * Returns infos on the uploaded file
     *
     * @return array( 'mime' => array(), 'file' => eZHTTPFile )
     * @throw RuntimeException if the uploaded file can not be found
     */
    public function getFileInfo()
    {
        $upload = new eZContentUpload();
        $errors = array();
        $mimeData = $file = '';
        $result = $upload->fetchHTTPFile( 'UploadFile', $errors, $file, $mimeData );

        if ( $result === false )
        {
            throw new RuntimeException(
                ezpI18n::tr(
                    'extension/ezjscore/ajaxuploader',
                    'Unable to retrieve the uploaded file: %message',
                    null, array( '%message' => $errors[0]['description'] )
                )
            );
        }
        return array(
            'file' => $file,
            'mime' => $mimeData
        );
    }

    /**
     * Returns the content class to use when creating the content object from
     * the file
     *
     * @param array $mimeData
     * @return eZContentClass
     * @throw RuntimeException if the found class identifier does not exists
     * @throw DomainException if objects of the found class are not allowed
     */
    public function getContentClass( array $mimeData )
    {
        $upload = new eZContentUpload();

        $classIdentifier = $upload->detectClassIdentifier( $mimeData['name'] );
        $class = eZContentClass::fetchByIdentifier( $classIdentifier );
        if ( !$class instanceof eZContentClass )
        {
            throw new RuntimeException(
                ezpI18n::tr(
                    'extension/ezjscore/ajaxuploader',
                    'Unable to load the class which identifier is "%class",' .
                    ' this is probably a configuration issue in upload.ini.',
                    null, array( '%class' => $classIdentifier )
                )
            );
        }

        $classContent = $this->attribute->attribute( 'class_content' );

        if ( !empty( $classContent['class_constraint_list'] )
                && !in_array( $classIdentifier, $classContent['class_constraint_list'] ) )
        {
            throw new DomainException(
                ezpI18n::tr(
                    'extension/ezjscore/ajaxuploader', 'The file cannot be processed because' .
                    ' it would result in a \'%class\' object and this relation' .
                    ' does not accept this type of object.',
                    null, array( '%class' => $class->attribute( 'name' ) )
                )
            );
        }
        return $class;
    }

    /**
     * Returns the node id of the default location of the future object
     *
     * @param eZContentClass $class
     * @return int
     */
    public function getDefaultParentNodeId( eZContentClass $class )
    {
        $parentNodes = array();
        $parentMainNode = null;
        $upload = new eZContentUpload();
        $upload->detectLocations(
            $class->attribute( 'identifier' ), $class, 'auto',
            $parentNodes, $parentMainNode
        );
        return $parentMainNode;
    }

    /**
     * Creates the eZContentObject from the uploaded file
     *
     * @param eZHTTPFile $file
     * @param eZContentObjectTreeNode $location
     * @param string $name
     * @return eZContentObject
     * @throw InvalidArgumentException if the parent location does not exists
     * @throw RuntimeException if the object can not be created
     */
    public function createObject( $file, $parentNodeId, $name = '' )
    {
        $result = array();
        $parentNode = eZContentObjectTreeNode::fetch( $parentNodeId );
        if ( !$parentNode instanceof eZContentObjectTreeNode )
        {
            throw new InvalidArgumentException(
                ezpI18n::tr(
                    'extension/ezjscore/ajaxuploader', 'Location not found.'
                )
            );
        }

        $upload = new eZContentUpload();
        $r = $upload->handleLocalFile(
            $result, $file, $parentNodeId, null, $name,
            $this->attribute->attribute( 'language_code' )
        );
        if ( !$r || !$result['contentobject'] instanceof eZContentObject )
        {
            throw new RuntimeException(
                ezpI18n::tr(
                    'extension/ezjscore/ajaxuploader',
                    'Unable to create the content object to add to the relation: %detail',
                    null, array( '%detail', $result['errors'][0]['description'] )
                )
            );
        }
        return $result['contentobject'];
    }

    /**
     * Serialize the eZContentObject to be used to build the result in
     * JavaScript
     *
     * @param eZContentObject $object
     * @return array
     */
    public function serializeObject( eZContentObject $contentObject )
    {
        $section = eZSection::fetch( $contentObject->attribute( 'section_id' ) );
        return array(
            'object_info' => array(
                'id' => $contentObject->attribute( 'id' ),
                'name' => $contentObject->attribute( 'name' ),
                'class_name' => $contentObject->attribute( 'class_name' ),
                'section_name' => $section->attribute( 'name' ),
                'published' => ezpI18n::tr( 'design/standard/content/datatype', 'Yes' ),
            )
        );
    }
}

?>
