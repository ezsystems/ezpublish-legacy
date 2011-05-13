<?php
/**
 * File containing the eZTemplateParser class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZTemplateParser eztemplateparser.php
  \brief The class eZTemplateParser does

*/

class eZTemplateParser
{
    /*!
     Constructor
    */
    function eZTemplateParser()
    {
    }

    /*!
     Parses the template file $txt. The actual parsing implementation is done by inheriting classes.
    */
    function parse( $tpl, $sourceText, &$rootElement, $rootNamespace, &$relation )
    {
    }

}

?>
