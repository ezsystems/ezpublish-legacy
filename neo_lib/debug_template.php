<?php
/**
 * File containing the ezpDebugTemplate class.
 *
 * @package
 * @version //autogen//
 * @copyright Copyright (C) 2005, 2006 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 *
 * <code>
 * </code>
 *
 * @package
 * @version //autogen//
 */

class ezpDebugTemplate extends ezcTemplate
{
    public static $errorTemplates = array();

    public function process( $location, ezcTemplateConfiguration $config = null )
    {
        try
        {
            $retval = ezcTemplate::process( $location, $config );
            $templateFile = ezpDebugTemplate::cleanPath( $this->stream );
            ezpDebugTemplate::logUsage( $templateFile );
            return ezpDebugTemplate::visualDebug( $retval, $templateFile, "neo: " );
        }
        catch ( Exception $e )
        {
            self::$errorTemplates[count( self::$errorTemplates ) - 1][] = $this->stream;
            throw $e;
        }
    }

    static function visualDebug( $result, $templateFile, $prefix )
    {
        global $ezpDebugTemplateVisualDebug;
        if ( isset( $ezpDebugTemplateVisualDebug ) && !$ezpDebugTemplateVisualDebug )
            return $result;

        if ( preg_match( "#/(page_head.tpl|link.tpl)$#", $templateFile ) )
            return $result;
        $templateName = $templateFile;
        if ( preg_match( "#^(design/([^/]+)/(override/)?templates/)(.+)$#", $templateFile, $matches ) )
        {
            $templateName = $matches[4] . " in " . $matches[1];
        }
        $name = htmlspecialchars( $prefix . $templateName );
        $name2 = htmlspecialchars( $prefix . $templateFile );
        $commentStart = "\n" . '<!-- start: ezp-template-wrap class="ezp-template" name="' . $name2 . '" -->' . "\n";
        $commentEnd   = '<!-- end: class="ezp-template" name="' . $name2 . '" ezp-template-wrap -->' . "\n";

        $pre  = '';
        $post = '';

        if ( preg_match( "#<body[^>]*>#is", $result, $matches, PREG_OFFSET_CAPTURE ) )
        {
            $offset1 = $matches[0][1];
            $offset2 = $offset1 + strlen( $matches[0][0] );
            if ( preg_match( "#<[ \t\r\n]*/[ \t\r\n]*body[ \t\r\n]*>#is", $result, $matchesEnd, PREG_OFFSET_CAPTURE, $offset2 ) )
            {
                $offset3 = $matchesEnd[0][1];
                $pre    = $commentStart . substr( $result, 0, $offset2 );
                $post   = substr( $result, $offset3 ) . $commentEnd;
                $result = substr( $result, $offset2, $offset3 - $offset2 );

                $commentStart = '';
                $commentEnd = '';
            }
            else
            {
                $pre    = $commentStart . substr( $result, 0, $offset2 );
                $result = substr( $result, $offset2 ) . $commentEnd;

                $commentStart = '';
                $commentEnd = '';
            }
        }

        $out = $pre . $commentStart . '<div class="ezp-template" title="' . $name . '">';
        $out .=  "\n" . $result . "\n" . '</div>' . $commentEnd;
        static $first = true;
        if ( $first )
            $out = "\n" . '<!-- start: ezp-template-style --><style type="text/css">div.ezp-template:hover {display: block; border: 2px solid #f00; margin: 2px}</style><!-- end: ezp-template-style -->' . "\n" . $out;
        $out .= $post;

        $first = false;
        return $out;
    }

    static function logUsage( $templateFile )
    {
        $usagelog = ".ezpneo.tplusage.log";
        if ( basename( realpath( "." ) ) == '.run' )
        {
            $usagelog = "../" . $usagelog;
        }
        $fd = fopen( $usagelog, "a" );
        fwrite( $fd, $templateFile . "\n" );
    }

    static function cleanPath( $templateFile )
    {
        $path = realpath( "." );
        if ( preg_match( "#^" . preg_quote( $path, "#" ) . "/(.*)$#", $templateFile, $matches ) )
        {
            $templateFile = $matches[1];
        }
        if ( preg_match( "#^new_templates/(.*)$#", $templateFile, $matches ) )
        {
            $templateFile = $matches[1];
        }
        return $templateFile;
    }
}

?>
