<?php

$Result = array();
$Result["title"] = "TextCodec";
$Result["content"] = "";

include_once( "lib/ezi18n/classes/eztextcodec.php" );

$latinstr = "ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_";

$cyrstr = "АБВГДЕЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ";

$greekstr = "ΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩΪΫάέήί";

$hebrewstr = "אבגדהוזחטיךכלםמןנסעףפץצקרשת";

$arabicstr = "ءآأؤإئابةتثجحخدذرزسشصضطظعغ";

$northeurstr = "ĀÁÂÃÄÅÆĮČÉĘËĖÍÎĪĐŅŌĶÔÕÖ×ØŲÚÛÜŨŪß";

$sjisstr = "ぁあぃいぅうぇえぉおかがきぎくぐけげこごさざしじすずせぜそぞただちぢっ";

$sjisstr2 = "ァアィイゥウェエォオカガキギクグケゲコゴサザシジスズセゼソゾタダチヂ";

$sjisstr3 = "亜唖娃阿哀愛挨姶逢葵茜穐悪握渥旭葦芦鯵梓圧斡扱宛姐虻飴絢綾鮎或粟袷安庵按暗案闇鞍杏以伊位";

$sjisstr4 = "ｦｧｨｩｪｫｬｭｮｯｰｱｲｳｴｵｶｷｸｹｺｻｼｽｾｿﾀﾁﾂﾃﾄ";

$convert_list = array( array( "text" => $latinstr,
                              "charset" => "iso-8859-1",
                              "name" => "Latin1" ),
                       array( "text" => $cyrstr,
                              "charset" => "cyrillic",
                              "name" => "Cyrillic" ),
                       array( "text" => $greekstr,
                              "charset" => "iso-8859-7",
                              "name" => "Greek" ),
                       array( "text" => $northeurstr,
                              "charset" => "iso-8859-4",
                              "name" => "Northern Europe" ),
                       array( "text" => $hebrewstr,
                              "charset" => "iso-8859-8",
                              "name" => "Hebrew" ),
                       array( "text" => $arabicstr,
                              "charset" => "iso-8859-6",
                              "name" => "Arabic" ),
                       array( "text" => $sjisstr,
                              "charset" => "cp932",
                              "name" => "Hiragana" ),
                       array( "text" => $sjisstr2,
                              "charset" => "cp932",
                              "name" => "Katakana" ),
                       array( "text" => $sjisstr3,
                              "charset" => "cp932",
                              "name" => "CJK Unified" ),
                       array( "text" => $sjisstr4,
                              "charset" => "cp932",
                              "name" => "Halfwidth Katakana" )
                       );

$charset_list = array( "iso-8859-1", "cyrillic", "iso-8859-7", "iso-8859-4", "iso-8859-8",
                       "iso-8859-6", "cp932" );

$cur_charset = "iso-8859-1";
$http =& eZHTTPTool::instance();
if ( $http->hasPostVariable( "CharsetCode" ) )
{
    $cur_charset = $http->postVariable( "CharsetCode" );
}

$html_charset = $cur_charset;
if ( $html_charset == "cp932" )
    $html_charset = "sjis";
header( "Content-Type: text/html; charset=$html_charset" );

print( "
<form action=\"\" method=\"post\" name=\"CodepageMapping\">
<p>
The TextCodec allows for uniform conversion from one charset to another, it does conversion using codepages
and using the mbstring extension for some charset conversions. This page does not use the mbstring extension
to show that it can handle these charsets itself.
</p>

<p>
The table below displays some of the various charsets it can convert and it's utf8 conversion. Using
utf8 allows us to display the various characters in one page.
</p>
" );

print( '<select name="CharsetCode">' );
foreach( $charset_list as $charset )
{
    print( "<option value=\"$charset\" " . ( $cur_charset == $charset ? "selected=\"selected\"" : "" ) . ">$charset</option>" );
}
print( '</select>' );
print( "<input class=\"stdbutton\" type=\"submit\" Name=\"ChangeCharset\" value=\"Change charset\">" );

print( "<table>
<tr><th>Requested charset</th><th>Charset</th><th>Name</th><th>Text</th><th>Original strlen</th><th>Correct strlen</th></tr>\n" );

eZTextCodec::setUseMBString( false );

foreach( $convert_list as $convert_item )
{
    $charset = $convert_item["charset"];
    $text = $convert_item["text"];
    $name = $convert_item["name"];
    $codec =& eZTextCodec::instance( "utf-8", $cur_charset );
    $req_charset = $codec->requestedInputCharsetCode();
    $charset = $codec->inputCharsetCode();

    $out = $codec->convertString( $text );
    print( "<tr><td>$req_charset</td><td>$charset</td><td>$name</td><td>$out</td><td>" . strlen( $text ) . "</td><td>" . $codec->strlen( $text ) . "</td></tr>\n" );
}
print( "</table>
<p class=\"footnote\">Not all characters may be visible depending on your browser font support.</p>
</form>" );

?>
