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
    static $max = 72;
    
    static $isLinkWwwUrl = false;
    
    static $hideProtoclText = '';
    
    
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

    /*!
     Leave for backward compatibilty.
     \static
     \deprecated
    */
    static function formatUri( $url, $max )
    {
        $text = $url;
        if (strlen($text) > $max)
        {
            $text = substr($text, 0, ($max / 2) - 3). '...'. substr($text, strlen($text) - ($max / 2));
        }
        return "<a href=\"$url\" title=\"$url\">$text</a>";
    }
    
    static function formatUriCallback( $matches )
    {
        $method = $matches[1];
        $www = $matches[2];
        $domainPath = $matches[3];
        
        $url = $method . $www . $domainPath;
        
        if ( self::$isLinkWwwUrl && $www == 'www.' )
        {
            $url = 'http://' . $www . $domainPath;
        }
        
        $textFull = $url;
        
        if ( self::$hideProtoclText != '' )
        {
            $textFull = preg_replace( '`^(?:' . self::$hideProtoclText . '):\/\/(.*)$`', '$1', $textFull );
        }
        
        $text = $textFull;
        if (strlen($text) > self::$max)
        {
            $text = substr($text, 0, (self::$max / 2) - 3). '...'. substr($text, strlen($text) - (self::$max / 2));
        }
        return "<a href=\"$url\" title=\"$textFull\">$text</a>";
    }

    /*!
      $max - leave for backward compatibilty
     \static
    */
    static function addURILinks( $text, $max = false, $methods = 'http|https|ftp' )
    {
        if ( $max !== false && is_int( $max ) )
            self::$max = $max;
        
        if ( self::$isLinkWwwUrl )
            $mainSearchText = "(?:((?:$methods):\/\/)|(www\.))";
        else
            $mainSearchText = "($methods)(:\/\/)";
        
        return preg_replace_callback(
            "`(?<!href=\"|href='|src=\"|src='|value=\"|value=')$mainSearchText([\w]+(?:.[\w]+)(?:[\w\-\.,@?^=%&:\/~\+#;*\(\)\!]*[\w\-\@?^=%&\/~\+#;*\(\)\!])?)`",
            'eZAutoLinkOperator::formatUriCallback',
            $text
        );
    }


    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters, $placement )
    {
        $ini = $tpl->ini();
        self::$max = $ini->variable( 'AutoLinkOperator', 'MaxCharacters' );
        if ( $namedParameters['max_chars'] !== null )
        {
            self::$max = $namedParameters['max_chars'];
        }

        $methods = $ini->variable( 'AutoLinkOperator', 'Methods' );
        $methodText = implode( '|', $methods );

        self::$isLinkWwwUrl = ( $ini->variable( 'AutoLinkOperator', 'LinkWwwUrlsWithoutProtocol' ) == 'enabled' );
        
        $hideProtocls = $ini->variable( 'AutoLinkOperator', 'HideProtocols' );
        self::$hideProtoclText = implode( '|', $hideProtocls );
        
        // Replace mail
        $operatorValue = preg_replace( "#(([a-zA-Z0-9_-]+\\.)*[a-zA-Z0-9_-]+@([a-zA-Z0-9_-]+\\.)*[a-zA-Z0-9_-]+)#", "<a href='mailto:\\1'>\\1</a>", $operatorValue );

        // Replace http/ftp etc. links
        $operatorValue = eZAutoLinkOperator::addURILinks( $operatorValue, false, $methodText );
    }

    /// \privatesection
    public $Operators;
}

?>
