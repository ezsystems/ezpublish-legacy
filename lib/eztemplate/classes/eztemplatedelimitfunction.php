<?php
/**
 * File containing the eZTemplateDelimitFunction class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZTemplateDelimitFunction eztemplatedelimitfunction.php
  \ingroup eZTemplateFunctions
  \brief Displays left and right delimiter in templates

  This class iss a template function for outputting the left and right delimiters.
  Since the left and right delimiters are always parsed by the template engine
  it's not possible to output these characters. By registering an instance of this
  class as template functions you can get these characters with {ldelim} and {rdelim}.

  The name of these functions can also be controlled by passing the names to the
  constructor.

  Example:
\code
$tpl->registerFunctions( new eZTemplateDelimitFunction() );
// or custom names
$tpl->registerFunctions( new eZTemplateDelimitFunction( "l", "r" ) );
// alternatively
$obj = new eZTemplateDelimitFunction();
$tpl->registerFunction( "ldelim", $obj );
$tpl->registerFunction( "rdelim", $obj );
\endcode
*/

class eZTemplateDelimitFunction
{
    /*!
     Initializes the object with a name for the left and right delimiter.
     Default is ldelim for left and rdelim for right.
    */
    function eZTemplateDelimitFunction()
    {
        $this->LName = 'ldelim';
        $this->RName = 'rdelim';
    }

    /*!
     Returns an array of the function names, required for eZTemplate::registerFunctions.
    */
    function functionList()
    {
        return array( $this->LName, $this->RName );
    }

    /*!
     Returns an array with hints for the template compiler.
    */
    function functionTemplateHints()
    {
        return array(
            $this->LName => array( 'parameters' => false, 'static' => false, 'tree-transformation' => true ),
            $this->RName => array( 'parameters' => false, 'static' => false, 'tree-transformation' => true )
        );
    }

    function templateNodeTransformation( $functionName, &$node,
                                         $tpl, $parameters, $privateData )
    {
        $newNodes = array();

        if ( $functionName == $this->LName )
        {
            $newNodes = array ( eZTemplateNodeTool::createTextNode( $tpl->leftDelimiter() ) );
        }
        else
        {
            $newNodes = array ( eZTemplateNodeTool::createTextNode( $tpl->rightDelimiter() ) );
        }
        return $newNodes;
    }

    /*!
     Outputs the left or right delimiter if the function names match.
    */
    function process( $tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $nspace, $current_nspace )
    {
        switch ( $functionName )
        {
            case $this->LName:
            {
                $textElements[] = $tpl->leftDelimiter();
            } break;
            case $this->RName:
            {
                $textElements[] = $tpl->rightDelimiter();
            } break;
        }
    }

    /*!
     Returns false, telling the template parser that this is a single tag.
    */
    function hasChildren()
    {
        return false;
    }

    /// The name of the left delimiter tag
    public $LName;
    /// The name of the right delimiter tag
    public $RName;
}

?>
