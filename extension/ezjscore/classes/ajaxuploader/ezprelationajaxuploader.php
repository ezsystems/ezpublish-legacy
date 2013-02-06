<?php
/**
 * File containing the ezpRelationAjaxUploader class.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package ezjscore
 * @subpackage ajaxuploader
 */

/**
 * This class handles AJAX Upload for eZObjectRelation attributes
 *
 * @package ezjscore
 * @subpackage ajaxuploader
 */
class ezpRelationAjaxUploader extends ezpRelationListAjaxUploader
{
    /**
     * Checks if a file can be uploaded.
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
        return true;
    }

}

?>
