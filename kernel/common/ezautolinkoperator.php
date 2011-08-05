<?php
/**
 * File containing the eZAutoLinkOperator class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

class eZAutoLinkOperator
{
    function eZAutoLinkOperator( $name = 'autolink' )
    {
        $this->Operators = array( $name );
    }

    /*!
     Returns the operators in this class.
    */
    function operatorList()
    {
        return $this->Operators;
    }

    /*!
     See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {
        return array( 'max_chars' => array( 'type' => 'integer',
                                            'required' => false,
                                            'default' => null ) );
    }

    function formatUri( $url, $max )
    {
        $text = $url;
        if (strlen($text) > $max)
        {
            $text = substr($text, 0, ($max / 2) - 3). '...'. substr($text, strlen($text) - ($max / 2));
        }
        return "<a href=\"$url\" title=\"$url\">$text</a>";
    }

    /*!
     \static
    */
    function addURILinks( $text, $max, $methods = 'http|https|ftp' )
    {
        return preg_replace(
            "`(?<!href=\"|href='|src=\"|src='|value=\"|value=')($methods):\/\/[\w]+(.[\w]+)([\w\-\.,@?^=%&:\/~\+#;*\(\)\!]*[\w\-\@?^=%&\/~\+#;*\(\)\!])?`e",
            'eZAutoLinkOperator::formatUri("$0", '. $max. ')',
            $text
        );
    }


    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        $ini = $tpl->ini();
        $max = $ini->variable( 'AutoLinkOperator', 'MaxCharacters' );
        if ( $namedParameters['max_chars'] !== null )
        {
            $max = $namedParameters['max_chars'];
        }

        $methods = $ini->variable( 'AutoLinkOperator', 'Methods' );
        $methodText = implode( '|', $methods );

        // Replace mail
        $operatorValue = preg_replace( "#(([a-zA-Z0-9_-]+\\.)*[a-zA-Z0-9_-]+@([a-zA-Z0-9_-]+\\.)*[a-zA-Z0-9_-]+)#", "<a href='mailto:\\1'>\\1</a>", $operatorValue );

        // Replace http/ftp etc. links
        $operatorValue = eZAutoLinkOperator::addURILinks($operatorValue, $max, $methodText);
    }

    /// \privatesection
    public $Operators;
}

?>
