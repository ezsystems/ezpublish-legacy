<?php
/**
 * File containing the eZFileExtensionBlackListValidator class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZFileExtensionBlackListValidator ezfileextensionblacklistvalidator.php
  \brief The class eZFileExtensionBlackListValidator validates file extensions based on a blacklist.
*/

class eZFileExtensionBlackListValidator extends eZInputValidator
{
    /*!
     Constructor
    */
    function __construct()
    {
        $fileIni = eZINI::instance('file.ini');
        $this->constraints['extensionsBlackList'] = $fileIni->variable('FileSettings','FileExtensionBlackList');
    }

    /*!
     Tries to validate to the filename \a $filename and returns one of the validator states
     eZInputValidator::STATE_ACCEPTED, eZInputValidator::STATE_INTERMEDIATE or
     eZInputValidator::STATE_INVALID.
    */
    function validate( $filename )
    {
        if (
            pathinfo($filename, PATHINFO_BASENAME) !== $filename ||
            in_array(strtolower(pathinfo($filename, PATHINFO_EXTENSION)), $this->constraints['extensionsBlackList'], true)
        ) {
            return eZInputValidator::STATE_INVALID;
        }

        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     Return the list of blacklisted file extensions.
    */
    function extensionsBlackList()
    {
        return $this->constraints['extensionsBlackList'];
    }

    /// \privatesection
    protected $constraints = array(
        'extensionsBlackList' => array(),
    );
}
