<?php
/**
 * File containing the eZAutoLinkOperator class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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

    static function formatUri( $url, $max, $method = '' )
    {
        if ( $method == 'www.' )
        {
            $textFull = $method . $url;
            $text = $textFull;
            $url = 'http://' . $method . $url;
        }
        else
        {
            $textFull = $url;
            $text = $textFull;
            $url = $method . $url;
        }
        if (strlen($text) > $max)
        {
            $text = substr($text, 0, ($max / 2) - 3). '...'. substr($text, strlen($text) - ($max / 2));
        }
        return "<a href=\"$url\" title=\"$textFull\">$text</a>";
    }

    /*!
     \static
    */
    static function addURILinks( $text, $max, $methods = 'http|https|ftp' )
    {
        return preg_replace(
            "`(?<!href=\"|href='|src=\"|src='|value=\"|value=')((?:(?:$methods):\/\/)|www.)([\w]+(?:.[\w]+)(?:[\w\-\.,@?^=%&:\/~\+#;*\(\)\!]*[\w\-\@?^=%&\/~\+#;*\(\)\!])?)`e",
            'eZAutoLinkOperator::formatUri("$2", '. $max. ', "$1")',
            $text
        );
    }


    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters, $placement )
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
