<h1>Translation of text</h1>

<p>
By using the Translation Manager it's possible to do translation of text into other languages or other forms.
At the moment it supports translation using Qt translation files
which are XML files and can be easily editied using <a href=\"http://doc.trolltech.com/3.0/linguist-manual.html\">Linguist</a>,
it also supports 1377 translation for dynamic translation, and random translation which is handled by randomly picking one of it's
sub translators to the work.
Adding custom made translation handlers are easy, this way it's possible to get advanced dynamic translations as well as
supporting other file formats.
</p>

<h2>Key elements</h2>
<p>
The translation system is based around source, context and comment.
</p>
<h3>Source</h3>
<p>
The source is the original text which is used to create and MD5 to lookup translations.
</p>
<h3>Context</h3>
<p>
The context determines in which context the text belongs to meaning that it's possible to have the same text in two contexts
with different meaning.
</p>
<h3>Comment</h3>
<p>
The comment is used for finding variant of a text, for instance in some languages the translation must differ according to
where it's used while in others the text should be the same, it's similar to the context but will fallback to the
default comment (the one with no comment name) if the specified comment can't be found.
</p>


<h2>Example</h2>
<p>The table below shows a test translation, notice the <b>Send for publishing</b> source is translated to 1337 text because
there was no manual translation available.</p>

<?php

include_once( "lib/ezi18n/classes/eztranslatormanager.php" );
include_once( "lib/ezi18n/classes/eztstranslator.php" );
include_once( "lib/ezi18n/classes/ez1337translator.php" );

$trans =& eZTranslatorManager::instance();
$tr = new eZTSTranslator( "edit.ts", "lib/ezi18n/sdk" );
$trans->registerHandler( $tr );
$tr2 = new eZ1337Translator();
$trans->registerHandler( $tr2 );

$texts = array(
    "Preview",
    array( "source" => "Preview",
           "context" => "edit" ),
    array( "source" => "Preview",
           "context" => "edit",
           "comment" => "side-pane" ),
    array( "source" => "Preview",
           "context" => "edit",
           "comment" => "bottom-pane" ),
    "Versions",
    array( "source" => "Versions",
           "context" => "edit" ),
    array( "source" => "Versions",
           "context" => "edit",
           "comment" => "side-pane" ),
    "Translate",
    "Permission",
    "Some text",
    "Store Draft",
    "Send for publishing",
    "Discard",
    "Find object"
    );

print( '<table cellspacing="0">
<tr>
  <th>Context</th><th>Source</th><th>Comment</th><th>Translation(no_NO)</th>
</tr>
');

$seq = array( "bgdark", "bglight" );

foreach( $texts as $text )
{
    $source = "";
    $context = "";
    $comment = null;
    if ( is_array( $text ) )
    {
        if ( isset( $text["source"] ) )
            $source = $text["source"];
        if ( isset( $text["context"] ) )
            $context = $text["context"];
        if ( isset( $text["comment"] ) )
            $comment = $text["comment"];
    }
    else
        $source = $text;
    $class = array_pop( $seq );
    array_splice( $seq, 0, 0, array( $class ) );
    $translation = $trans->translate( $context, $source, $comment );
    if ( $context == "" )
        $context = "default";
    print( "<tr>
  <td class=\"$class\">$context</td><td class=\"$class\">$source</td><td class=\"$class\">$comment</td><td class=\"$class\">$translation</td>
</tr>
" );
}

print( '</table>' );

$Result = array();
$Result["title"] = "Translation";
$Result["content"] = "";

?>
