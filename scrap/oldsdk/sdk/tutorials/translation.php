<?php
//
// Created on: <12-Dec-2002 22:15:53 gl>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

?>

<h1>Translation and i18n</h1>

<ul>
<li><a href="#whatyouneed">What you need</a></li>
<li><a href="#making">Making translations</a></li>
<li><a href="#installing">Installing translations</a></li>
<li><a href="#templates">Making i18n-friendly templates</a></li>
<li><a href="#phpcode">Making i18n-friendly PHP code</a></li>
</ul>

<br/>
<h2 id="whatyouneed">What you need</h2>

<p>
eZ publish 3 requires two programs to create and maintain translations, <b>ezlupdate</b> and <b>linguist</b>. These programs are based on the same tools from the Qt toolkit by <a href="http://www.trolltech.com">Trolltech</a>. The unix version of this toolkit is released under the GPL. eZ systems provides binaries for Windows and Mac OS X, see the translations page on <a href="http://ez.no/developer">ez.no/developer</a>.
</p>

<p>
The linguist is unmodified from the Qt original, so you can also get it from other sources, such as RPMs. If you run Linux or a similar system, you will find the linguist as part of qt-3.*, qt-devel-3.* or a similarly named package.
</p>

<p>
The ezlupdate program is modified to make it understand eZ publish files. You will find the source code and build instructions in <b>support/lupdate-ezpublish3</b> in the eZ publish distribution.
</p>


<br/>
<h2 id="making">Making translations</h2>

<p>
<strong>Note:</strong> The database content of eZ publish is not translated, meaning that for instance class attributes are shown in English wherever this information is visible. This is normally only in the admin interface. The classes provided with eZ publish are merely examples provided to get you up and running quickly. You are encouraged to extend and/or replace these classes with your own classes. If you need a non-English administrative interface, you can translate your classes in the "Setup" section. (The translation system covers  content in templates and PHP code.)
</p>

<p>
You must decide the locale code of your language. eZ publish 3 uses locale codes on the form <b>aaa-AA</b>, where the 3 first lowercase letters describe the language, while the last two uppercase letter describe the country in which the language is spoken. For instance, English as it is spoken in Great Britain would be eng-GB, while US English is eng-US.
</p>

<p>
Countries are specified by the ISO 3166 Country Code:
<a href="http://www.iso.ch/iso/en/prods-services/iso3166ma/index.html">http://www.iso.ch/iso/en/prods-services/iso3166ma/index.html</a>
</p>

<p>
Language is specified by the ISO 639 Language Code:
<a href="http://www.w3.org/WAI/ER/IG/ert/iso639.htm">http://www.w3.org/WAI/ER/IG/ert/iso639.htm</a>
</p>

<p>
You can also create a variation of a locale, you will for instance find two variations of nor-NO, nor-NO@intl and nor-NO@spraakraad, that are slight modifications of the original.
</p>

<p>
For the rest of this part, I assume you are translating nor-NO.
</p>

<p>
Copy share/locale/eng-GB.ini to share/locale/nor-NO.ini. Edit this file with a text editor. Here you set locale details such as time formats, currency and the names of the week days.
</p>

<p>
Now, enter the main eZ publish directory in a terminal and type
</p>

<pre class="example">
bin/linux/ezlupdate -v nor-NO
</pre>

<p>
(Provided that nor-NO is your locale, of course. -v is for verbose, showing messages about what happens. You can also use -vv for extra verbose output, or omit it for silent behavior. Run bin/linux/ezlupdate -h for an explanation of the arguments.
</p>

<p>
You will now find a translation in <b>share/translations/nor-NO/translation.ts</b> or similar for other locales. Open this file in linguist and do the translation.
</p>

<p>
You will find documentation on linguist on Trolltechs page:
<a href="http://doc.trolltech.com/3.0/linguist-manual-3.html">http://doc.trolltech.com/3.0/linguist-manual-3.html</a>
</p>

<p>
When you are done translating in linguist (or earlier, if you want to test part of a translation), open <b>settings/site.ini</b>. Go to the section <b>[RegionalSettings]</b> and set <b>Locale=nor-NO</b>. Also set <b>TextTranslation=enabled</b>, or the default (eng-GB) will be used.
</p>

<p>
Sample entry in settings/site.ini:
</p>

<pre class="example">
[RegionalSettings]
Locale=nor-NO
TextTranslation=enabled
</pre>

<p>
If you run a multi-language site, you will also need to translate content objects. Set <b>ContentObjectLocale=nor-NO</b> if you want the default language to be nor-NO. Important: Before you do this, you should make sure that the new locale is added to the system. Go to Setup -&gt; Translations and add your locale here if it does not exist. You should also translate the most used objects of your site before you change the ContentObjectLocale. To translate an object, edit it, and click "Edit" under "Translations" in the right-hand menu.
</p>

<p>
To distribute your translation, create a compressed archive, for instance .zip or tar.gz, of these two files:<br />
<b>share/locale/nor-NO.ini</b><br />
<b>share/translations/nor-NO/translation.ts</b>
</p>

<p>
You could for instance do it like this:
</p>

<pre class="example">
tar -zcvf nor-NO.tar.gz \
 share/locale/nor-NO.ini share/translations/nor-NO/translation.ts
</pre>

<br/>
<h2 id="installing">Installing translations</h2>

<p>
To install a translation, unpack the package in the eZ publish root directory. Make sure that the files go in the right place. The file translation.ts should be placed in the share/translations/[your language]/ directory, while the ini-file should be placed in share/locale. (If you unpack in the eZ publish root directory, this should be correct by default.)
</p>

<p>
Next, set the appropriate entries in settings/site.ini or the corresponding override file. These are the keys you need to change to enable the translation:
</p>

<pre class="example">
[RegionalSettings]
Locale=nor-NO
TextTranslation=enabled
TranslationCache=enabled
</pre>

<p>
After you have enabled the translation, the translation cache must be generated. This takes some time, so the first page view will be slow. After that, the site should be almost as fast as with the eng-GB translation. Note: The cache generation will be very slow if you don't have the PHP mbstring extension. If you get timeouts, that is probably the cause. You can disable the translation cache by setting TranslationCache=disabled. This will work, but will be a lot slower than using the cache.
</p>


<br/>
<h2 id="templates">Making i18n-friendly templates</h2>

<p>
All user-visible strings in templates should be embedded in the <b>i18n operator</b>. All such strings can be translated in the linguist. The i18n operator is used like this:
</p>

<pre class="example">
&lt;b&gt;{"This text can be translated"|i18n("design/mydesign/path")}&lt;/b&gt;
&lt;input type="submit" name="MyButton"
 value="{'My Cool Button'|i18n('design/mydesign/path')}" /&gt;
{include uri="design:gui/button.tpl" name=remove id_name=RemoveButton
 value="Click to remove"|i18n("design/mydesign/path")}
</pre>

<p>
The path in this example, design/mydesign/path, is used as a <b>context</b>. It is by this context that the strings are sorted in the linguist. The context does not have to be the actual path to the template file in question, but this can be useful. See the templates of the admin interface for examples.
</p>

<p>
You can also add an optional <b>comment</b> as a second argument to the i18n operator. Such comments are shown in the linguist. Use this for instance to explain how a word is used, this can be helpful for translators.
</p>

<p>
If you need to add <b>variables</b>, use the third argument to the i18n operator.
</p>

<pre class="example">
&lt;b&gt;{"The node %1 is a child of %2."|i18n("design/mydesign/path",,
 array($node.name,$parent_node.name))}&lt;/b&gt;
&lt;b&gt;{"The node %node is a child of %parent_node."|i18n("design/mydesign/path",,
 hash("%node",$node.name,"%parent_node",$parent_node.name))}&lt;/b&gt;
</pre>

<p>
As the examples show, variables can be used in two ways. You can use the keys %1 to %9, and an array of values to replace them with. You can also use any key you want, as long as you specify them in a hash. Note that automatic translators, such as the Bork translator, may not treat the keys correctly.
</p>

<p>
The i18n operator calls the <b>ezi18n()</b> function in <b>kernel/common/i18n.php</b>. If translation is not enabled in settings/site.ini, then it will simply output the source it is given. Otherwise, it will look up the string in the corresponding .ts file. If a translation is not found, it will use the automatic Bork translation. This is useful, as it makes it easy to spot strings that don't have the i18n operator.
</p>

<p>
In <b>extensions</b> you should use the <b>x18n</b> operator instead of i18n. The only difference is that x18n takes an additional parameter before the others: The name of the extension.
</p>

<pre class="example">
&lt;b&gt;{"The node %1 is a child of %2."|x18n("myextension","design/mydesign/path",,
 array($node.name,$parent_node.name))}&lt;/b&gt;
</pre>



<br/>
<h2 id="phpcode">Making i18n-friendly PHP code</h2>

<p>
All user-visible strings in PHP-code should be embedded in the <b>ezi18n()</b> function. It is similar to the template operator, except that it takes the source as the second argument. For extensions, use <b>ezx18n()</b>. As with the template operator, you can also use arguments. Use either a plain array and the keys %1 to %9, or an associative array where you specify the keys yourself.
</p>

<pre class="example">
$myObject->myFunction( ezi18n( 'kernel/classes/datatypes',
                               'Input is not integer.',
                               'Comment: eZIntegerType' ) );

$myObject->myFunction( ezx18n( 'myextension'
                               'modules/mymodule',
                               'Input is not integer.',
                               'Comment: eZIntegerType' ) );

$myObject->myFunction( ezi18n( 'kernel/classes/datatypes',
                               'The node %1 is a child of %2.',
                               null,
                               array( $node->name(), $parent_node->name() ) ) );

$myObject->myFunction( ezx18n( 'myextension'
                               'modules/mymodule',
                               'The node %node is a child of %parent_node.',
                               null,
                               array( "%node" => $node->name(),
                                      "%parent_node" => $parent_node->name() ) ) );
</pre>
