<?php

// if ( file_exists( '/tmp/use.xt' ) )
//     unlink( '/tmp/use.xt' );
// xdebug_start_trace( '/tmp/use' );

include_once( 'lib/ezi18n/classes/ezchartransform.php' );

$trans = new eZCharTransform();

include_once( 'lib/ezi18n/classes/eztextcodec.php' );
include_once( 'lib/ezi18n/classes/ezcharsetinfo.php' );
include_once( 'lib/ezutils/classes/ezhttptool.php' );


eZDebug::updateSettings( array( 'debug-enabled' => true,
                                'debug-by-ip' => false ) );

$http =& eZHTTPTool::instance();

$unicodeCodec =& eZTextCodec::instance( 'utf8', 'unicode' );
$utf8Codec =& eZTextCodec::instance( 'unicode', 'utf8' );

$isConverted = false;

if ( $http->hasPostVariable( 'TranslateButton' ) )
{
    $text = array( $http->postVariable( 'Text' ) );
    $isConverted = true;
}
else
{
//    $text = array( "ut på kjøretur!!!", 1041, 1042 );
    $text = array( "Ходо�ков�кий и Лебедев о�ве�гли в�е п�ед��вленн�е им обвинени�

ut på kjøretur!!!��¿

àáâãäåæçèéêëìíîïðñóòôõö�����������������������

«¹¼½¾»<=>ʹʺʻʼʽ'" );
}
$orgText = array();
$newText = array();
foreach ( $text as $str )
{
    if ( is_string( $str ) )
    {
        $list = $unicodeCodec->convertString( $str );
        $orgText = array_merge( $orgText, $list );
    }
    else
    {
        $orgText[] = $str;
    }
}

$allIdentifiers = array( 'space', 'hyphen', 'doublequote_normalize', 'apostrophe_normalize', 'doublequote_to_apostrophe', 'doublequote_to_singlequote', 'questionmark', 'special', 'ascii_lowercase', 'ascii_uppercase', 'cyrillic_transliterate', 'latin1_transliterate', 'diacritical' );
$identifierList = array( 'diacritical', 'ascii_uppercase' );
if ( $http->hasPostVariable( 'IdentifierList' ) )
{
    $identifierList = $http->postVariable( 'IdentifierList' );
}

$newstr = $trans->transform( $str, $identifierList, 'utf8' );

// small test using a different charset
$newstr2 = $trans->transform( "ut p� kj�retur!!!�", $identifierList, 'iso-8859-1' );
$codec2 =& eZTextCodec::instance( 'iso-8859-1', 'utf8' );

// xdebug_stop_trace();

print( '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
        <meta http-equiv="Content-language" content="eng-GB" /> </header>' );
print( '<body>' );
print( '<form action="" method="post">' );
print( $codec2->convertString( $newstr2 ) . "<br/>" );
print( '<label>Conversion type</label><br/>' );
print( '<select multiple="multiple" name="IdentifierList[]">' );
foreach ( $allIdentifiers as $identifier )
{
    print( '<option value="' . $identifier . '"' );
    if ( in_array( $identifier, $identifierList ) )
        print( 'selected="selected"' );
    print( '>' . $identifier . '</option>' );
}
print( '</select><br/>' );
print( "<label>Original</label><br/><textarea cols=\"60\" rows=\"20\" name=\"Text\">$str</textarea> <input type=\"submit\" name=\"TranslateButton\" value=\"Convert\" /> <br/>\n" );
if ( $isConverted )
{
    print( "<label>Converted</label><br/><pre>$newstr</pre><br/>\n" );
}

print( '</form>' );
eZDebug::printReport();

print( '</body>' );
print( '</html>' );


?>
