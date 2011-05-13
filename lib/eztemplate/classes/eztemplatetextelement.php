<?php
/**
 * File containing the eZTemplateTextElement class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZTemplateTextElement eztemplatetextelement.php
  \ingroup eZTemplateElements
  \brief Represents a text element in the template tree.

 This class containst the text of a text element.
*/

class eZTemplateTextElement
{
    /*!
     Initializes the object with the text.
    */
    function eZTemplateTextElement( $text )
    {
        $this->Text = $text;
    }

    /*!
     Returns #text.
    */
    function name()
    {
        return "#text";
    }

    function serializeData()
    {
        return array( 'class_name' => 'eZTemplateTextElement',
                      'parameters' => array( 'text' ),
                      'variables' => array( 'text' => 'Text' ) );
    }

    /*!
     Appends the element text to $text.
    */
    function process( $tpl, &$text )
    {
        $text .= $this->Text;
    }

    /*!
     Returns a reference to the element text.
    */
    function &text()
    {
        return $this->Text;
    }

    /// The element text
    public $Text;
}

?>
