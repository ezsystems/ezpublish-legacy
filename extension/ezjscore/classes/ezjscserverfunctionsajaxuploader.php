<?php
/**
 * File containing the ezjscServerFunctionsAjaxUploader class.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package ezjscore
 * @subpackage ajaxuploader
 */

/**
 * This class handles AJAX calls for the ajax uploader component
 * @package ezjscore
 * @subpackage ajaxuploader
 */
class ezjscServerFunctionsAjaxUploader extends ezjscServerFunctions
{

    /**
     * Returns an ajaxuploader handler instance from the ezjscore function
     * arguments.
     *
     * @param array $args the arguments of the ezjscore ajax function
     * @return ezpAjaxUploaderHandlerInterface
     *
     * @throws InvalidArgumentException if the handler cannot be instanciated
     */
    private static function getHandler( array $args )
    {
        if ( !isset( $args[0] ) )
        {
            throw new InvalidArgumentException(
                ezpI18n::tr(
                    'extension/ezjscore/ajaxuploader',
                    'Unable to find the identifier of ajax upload handler'
                )
            );
        }

        $http = eZHTTPTool::instance();
        $handlerData = $http->postVariable( 'AjaxUploadHandlerData', array() );

        $handlerOptions = new ezpExtensionOptions();
        $handlerOptions->iniFile = 'ezjscore.ini';
        $handlerOptions->iniSection = 'AjaxUploader';
        $handlerOptions->iniVariable = 'AjaxUploadHandler';
        $handlerOptions->handlerIndex = $args[0];
        $handlerOptions->handlerParams = $handlerData;

        $handler = eZExtension::getHandlerClass( $handlerOptions );

        if ( !$handler instanceof ezpAjaxUploaderHandlerInterface )
        {
            throw new InvalidArgumentException(
                ezpI18n::tr(
                    'extension/ezjscore/ajaxuploader',
                    'Unable to load the ajax upload handler'
                )
            );
        }
        return $handler;
    }

    /**
     * Returns the upload form
     *
     * @param array $args ezjscore function arguments, the first element is the AJAX
     *                      upload handler identifier ({@link
     *                      ezjscServerFunctionsAjaxUploader::getHandler})
     * @return array( 'meta_data' => false, 'html' => string)
     *
     * @throw RuntimeException if the user is not allowed to upload a file
     */
    static function uploadForm( $args )
    {
        $handler = self::getHandler( $args );
        if ( !$handler->canUpload() )
        {
            throw new RuntimeException(
                ezpI18n::tr(
                    'extension/ezjscore/ajaxuploader',
                    'You are not allowed to upload a file.'
                )
            );
        }
        $tpl = eZTemplate::factory();
        return array(
            'meta_data' => false,
            'html' => $tpl->fetch( 'design:ajaxuploader/upload.tpl' )
        );
    }

    /**
     * Stores the uploaded file and returns the location form. The result of
     * this method is always json encoded.
     *
     * @param array $args
     * @return string a json encoded array
     */
    static function upload( $args )
    {
        try
        {
            $handler = self::getHandler( $args );
            $fileInfo = $handler->getFileinfo();
            $mimeData = $fileInfo['mime'];
            $file = $fileInfo['file'];
            $class = $handler->getContentClass( $mimeData );

            if ( $file->store( false, $mimeData['suffix'] ) === false )
            {
                throw new RuntimeException(
                    ezpI18n::tr(
                        'extension/ezjscore/ajaxuploader',
                        'Unable to store the uploaded file.'
                    )
                );
            }
            else
            {
                $fileHandler = eZClusterFileHandler::instance();
                $fileHandler->fileStore( $file->attribute( 'filename' ), 'tmp', true, $file->attribute( 'mime_type' ) );
            }

            $start = $handler->getDefaultParentNodeId( $class );
            $defaultParentNode = eZContentObjectTreeNode::fetch( $start );
            if ( !$defaultParentNode instanceof eZContentObjectTreeNode )
            {
                throw new RuntimeException(
                    ezpI18n::tr(
                        "extension/ezjscore/ajaxuploader",
                        "The default parent location for uploads cannot be retrieved! Check user permissions and correctness of settings."
                    )
                );
            }
        }
        catch ( Exception $e )
        {
            // manually catch exception to force json encode
            // because most browsers cannot upload
            // wit a json HTTP Accept header...
            // see JavaScript code in eZAjaxUploader::delegateWindowEvents();
            return json_encode(
                array(
                    'error_text' => $e->getMessage(),
                    'content' => ''
                )
            );
        }

        $browseItems = self::getBrowseItems( $defaultParentNode->attribute( 'parent' ), $class );

        $http = eZHTTPTool::instance();
        $tpl = eZTemplate::factory();
        $tpl->setVariable( 'file', $file );
        $tpl->setVariable( 'name', $http->postVariable( 'UploadName', $file->attribute( 'original_filename' ) ) );
        $tpl->setVariable( 'mime_data', $mimeData );
        $tpl->setVariable( 'class', $class );
        $tpl->setVariable( 'browse_start', $defaultParentNode->attribute( 'parent' ) );
        $tpl->setVariable( 'default_parent_node', $defaultParentNode );
        $tpl->setVariable( 'browse', $browseItems );
        // json_encode and url encode the HTML to be able to get it in the
        // JavaScript code. see eZAjaxUploader::delegateWindowEvents()
        return json_encode(
            array(
                'meta_data' => false,
                'html' => rawurlencode(
                    $tpl->fetch( 'design:ajaxuploader/location.tpl' )
                )
            )
        );
    }

    /**
     * Returns a struct containing the following values:
     *      - limit the number of element by page
     *      - offset the current offset
     *      - items array, each element contains
     *          - has_child boolean
     *          - can_create boolean
     *          - node eZContentObjectTreeNode
     *      - has_prev boolean, true if there's a previous page
     *      - has_next boolean, true if there's a next page
     *
     * @param eZContentObjectTreeNode $start the node where the browse will start
     * @param eZContentClass $class class of the object to be created
     * @param int $offset
     * @return array
     */
    private static function getBrowseItems( eZContentObjectTreeNode $start, eZContentClass $class, $offset = 0 )
    {
        $result = array(
            'limit' => 10,
            'offset' => $offset,
            'items' => array(),
            'has_prev' => ( $offset != 0 ),
            'has_next' => false
        );
        $containerClasses = eZPersistentObject::fetchObjectList(
            eZContentClass::definition(), null,
            array(
                'version' => eZContentClass::VERSION_STATUS_DEFINED,
                'is_container' => 1
            )
        );
        $classFilterArray = array();
        foreach ( $containerClasses as $c )
        {
            $classFilterArray[] = $c->attribute( 'identifier' );
        }
        $children = $start->subTree(
            array(
                'ClassFilterArray' => $classFilterArray,
                'ClassFilterType' => 'include',
                'Depth' => 1,
                'Limit' => $result['limit'],
                'Offset' => $offset
            )
        );
        $count = $start->subTreeCount(
            array(
                'ClassFilterArray' => $classFilterArray,
                'ClassFilterType' => 'include',
                'Depth' => 1
            )
        );
        if ( $count > ( $offset + $result['limit'] ) )
        {
            $result['has_next'] = true;
        }
        foreach( $children as $node )
        {
            $elt = array();
            $elt['node'] = $node;
            $canCreateClassist = $node->canCreateClassList();
            foreach( $canCreateClassist as $c )
            {
                if ( $c['id'] == $class->attribute( 'id' ) )
                {
                    $elt['can_create'] = true;
                    break;
                }
            }
            if ( !isset( $elt['can_create'] ) )
            {
                $elt['can_create'] = false;
            }
            $childrenContainerCount = $node->subTreeCount(
                array(
                    'ClassFilterArray' => $classFilterArray,
                    'ClassFilterType' => 'include',
                    'Depth' => 1
                )
            );
            $elt['has_child'] = ( $childrenContainerCount > 0 );
            $result['items'][] = $elt;
        }
        return $result;
    }

    /**
     * Browse AJAX action
     *
     * @param array $args containing the following values:
     *          - 0 the parent node id of the elements to display
     *          - 1 the class id of the element that is going to be created
     *          - 2 [optional] the offset
     * @return array
     * @throws InvalidArgumentException if the node or the class cannot be loaded
     */
    static function browse( $args )
    {
        if ( count( $args ) < 2 )
        {
            throw new InvalidArgumentException(
                ezpI18n::tr(
                    'extension/ezjscore/ajaxuploader', 'Arguments error.'
                )
            );
        }
        list( $nodeID, $classId ) = $args;
        $offset = 0;
        if ( isset( $args[2] ) )
        {
            $offset = (int) $args[2];
        }
        $node = eZContentObjectTreeNode::fetch( $nodeID );
        $class = eZContentClass::fetch( $classId );
        if ( !$node instanceof eZContentObjectTreeNode
                || !$class instanceof eZContentClass )
        {
            throw new InvalidArgumentException(
                ezpI18n::tr(
                    'extension/ezjscore/ajaxuploader', 'Arguments error.'
                )
            );
        }
        else if ( !$node->canRead() )
        {
            throw new InvalidArgumentException(
                ezpI18n::tr(
                    'extension/ezjscore/ajaxuploader', 'Arguments error.'
                )
            );
        }
        $browseItems = self::getBrowseItems( $node, $class, $offset );

        $tpl = eZTemplate::factory();
        $tpl->setVariable( 'browse', $browseItems );
        $tpl->setVariable( 'browse_start', $node );
        $tpl->setVariable( 'default_parent_node', array( 'node_id' => 0 ) );
        $tpl->setVariable( 'class', $class );

        return array(
            'meta_data' => false,
            'html' => $tpl->fetch( 'design:ajaxuploader/browse.tpl' )
        );
    }


    /**
     * Creates the object from the uploaded file and displays the preview of it
     *
     * @param array $args
     * @return array
     * @throw RuntimeException if the previously uploaded file cannot be fetched
     */
    static function preview( $args )
    {
        $http = eZHTTPTool::instance();
        $handler = self::getHandler( $args );
        if ( !$handler->canUpload() )
        {
            throw new RuntimeException(
                ezpI18n::tr(
                    'extension/ezjscore/ajaxuploader',
                    'You are not allowed to upload a file.'
                )
            );
        }

        $file = $http->postVariable( 'UploadFile', false );
        $fileHandler = eZClusterFileHandler::instance();
        if ( $file === false
                || !$fileHandler->fileExists( $file )
                || !$fileHandler->fileFetch( $file ) )
        {
            throw new RuntimeException(
                ezpI18n::tr(
                    'extension/ezjscore/ajaxuploader', 'Unable to retrieve the uploaded file.'
                )
            );
        }
        else
        {
            $tmpFile = eZSys::cacheDirectory() . '/' .
                        eZINI::instance()->variable( 'FileSettings', 'TemporaryDir' ) . '/' .
                        str_replace(
                            array( '/', '\\' ), '_',
                            $http->postVariable( 'UploadOriginalFilename' )
                        );
            eZFile::rename( $file, $tmpFile, true );
            $fileHandler->fileDelete( $file );
            $fileHandler->fileDeleteLocal( $file );
        }

        $contentObject = $handler->createObject(
            $tmpFile,
            $http->postVariable( 'UploadLocation', false ),
            $http->postVariable( 'UploadName', '' )
        );
        unlink( $tmpFile );

        $tpl = eZTemplate::factory();
        $tpl->setVariable( 'object', $contentObject );
        return array(
            'meta_data' => $handler->serializeObject( $contentObject ),
            'html' => $tpl->fetch( 'design:ajaxuploader/preview.tpl' )
        );
    }
}
?>
