<h1>Translation</h1>

<p>
By using the Translation Manager it's possible to do translation of text into other languages or other forms.
At the moment it supports translation using Qt translation files
which are XML files and can be easily edited using <a href=\"http://doc.trolltech.com/3.0/linguist-manual.html\">Linguist</a>,
it also supports 1337 ('Leet') and Bork dynamic translation, and random translation which is handled by
randomly picking one of it's sub translators to do the work.
Adding custom made translation handlers are easy, this way it's possible to get advanced dynamic
translations as well as supporting other file formats.
</p>

<h2>Key elements</h2>
<p>
The translation system is based around source, context and comment.
</p>

<h3>Source</h3>
<p>
The source is the original text which is used to create an MD5 to lookup translations.
</p>

<h3>Context</h3>
<p>
The context determines in which context the text belongs to meaning that it's possible to have the same text in two contexts
with different meaning.
</p>

<h3>Comment</h3>
<p>
The comment is used for finding a variant of a text, for instance in some languages the translation
must differ according to where it's used while in others the text should be the same. It is similar to
the context but will fallback to the default comment (the one with no comment name) if the specified
comment can't be found.
</p>

<h2>Example</h2>
<p>
The table below shows a test translation to Norwegian, notice that the <b>Store Draft</b> source is
translated to 1337 because there was no manual translation available.
</p>

<img src="/doc/images/translation.jpg"/>

<p>
The table was produced by the following .ts file:
</p>

<pre class="example">
&lt;!DOCTYPE TS&gt;&lt;TS&gt;
&lt;context&gt;
    &lt;name&gt;edit&lt;/name&gt;
    &lt;message&gt;
        &lt;source&gt;Preview&lt;/source&gt;
        &lt;translation&gt;Forhaandsvisning&lt;/translation&gt;
    &lt;/message&gt;
    &lt;message&gt;
        &lt;source&gt;Preview&lt;/source&gt;
        &lt;comment&gt;side-pane&lt;/comment&gt;
        &lt;translation&gt;Vis fram&lt;/translation&gt;
    &lt;/message&gt;
    &lt;message&gt;
        &lt;source&gt;Versions&lt;/source&gt;
        &lt;translation&gt;Versjoner&lt;/translation&gt;
    &lt;/message&gt;
    &lt;message&gt;
        &lt;source&gt;Versions&lt;/source&gt;
        &lt;comment&gt;side-pane&lt;/comment&gt;
        &lt;translation&gt;Tidligere verk&lt;/translation&gt;
    &lt;/message&gt;
&lt;/context&gt;
&lt;context&gt;
    &lt;name&gt;default&lt;/name&gt;
    &lt;message&gt;
        &lt;source&gt;Preview&lt;/source&gt;
        &lt;translation&gt;Vis&lt;/translation&gt;
    &lt;/message&gt;
    &lt;message&gt;
        &lt;source&gt;Versions&lt;/source&gt;
        &lt;translation&gt;Versjon liste&lt;/translation&gt;
    &lt;/message&gt;
    &lt;message&gt;
        &lt;source&gt;Translate&lt;/source&gt;
        &lt;translation&gt;Oversett&lt;/translation&gt;
    &lt;/message&gt;
    &lt;message&gt;
        &lt;source&gt;Permission&lt;/source&gt;
        &lt;translation&gt;Rettigheter&lt;/translation&gt;
    &lt;/message&gt;
    &lt;message&gt;
        &lt;source&gt;Some text&lt;/source&gt;
        &lt;translation&gt;Litt tekst&lt;/translation&gt;
    &lt;/message&gt;
    &lt;message&gt;
        &lt;source&gt;Store Draft&lt;/source&gt;
        &lt;translation  type='unfinished'&gt;&lt;/translation&gt;
    &lt;/message&gt;
    &lt;message&gt;
        &lt;source&gt;Send for publishing&lt;/source&gt;
        &lt;translation&gt;Send til publisering&lt;/translation&gt;
    &lt;/message&gt;
    &lt;message&gt;
        &lt;source&gt;Discard&lt;/source&gt;
        &lt;translation&gt;Fjern&lt;/translation&gt;
    &lt;/message&gt;
    &lt;message&gt;
        &lt;source&gt;Find object&lt;/source&gt;
        &lt;translation&gt;Finn objekt&lt;/translation&gt;
    &lt;/message&gt;
&lt;/context&gt;
&lt;/TS&gt;
</pre>

<p>
And the following code:
</p>

<pre class="example">
include_once( "lib/ezi18n/classes/eztranslatormanager.php" );
include_once( "lib/ezi18n/classes/eztstranslator.php" );
include_once( "lib/ezi18n/classes/ez1337translator.php" );

$trans =& eZTranslatorManager::instance();
eZTSTranslator::initialize( "edit.ts", "lib/ezi18n/sdk", false );
eZ1337Translator::initialize();

$texts = array(
    "Preview",
    array( "source" =&gt; "Preview",
           "context" =&gt; "edit" ),
    array( "source" =&gt; "Preview",
           "context" =&gt; "edit",
           "comment" =&gt; "side-pane" ),
    array( "source" =&gt; "Preview",
           "context" =&gt; "edit",
           "comment" =&gt; "bottom-pane" ),
    "Versions",
    array( "source" =&gt; "Versions",
           "context" =&gt; "edit" ),
    array( "source" =&gt; "Versions",
           "context" =&gt; "edit",
           "comment" =&gt; "side-pane" ),
    "Translate",
    "Permission",
    "Some text",
    "Store Draft",
    "Send for publishing",
    "Discard",
    "Find object"
    );

print( '&lt;table cellspacing="0"&gt;
&lt;tr&gt;
  &lt;th&gt;Context&lt;/th&gt;&lt;th&gt;Source&lt;/th&gt;&lt;th&gt;Comment&lt;/th&gt;&lt;th&gt;Translation(nor-NO)&lt;/th&gt;
&lt;/tr&gt;
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
    $translation = $trans-&gt;translate( $context, $source, $comment );
    if ( $context == "" )
        $context = "default";
    print( "&lt;tr&gt;
  &lt;td class=\"$class\"&gt;$context&lt;/td&gt;&lt;td class=\"$class\"&gt;$source&lt;/td&gt;&lt;td class=\"$class\"&gt;$comment&lt;/td&gt;&lt;td class=\"$class\"&gt;$translation&lt;/td&gt;
&lt;/tr&gt;
" );
}

print( '&lt;/table&gt;' );
</pre>
