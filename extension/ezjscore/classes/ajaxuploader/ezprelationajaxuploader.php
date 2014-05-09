<?php
/**
 * File containing the ezpRelationAjaxUploader class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
