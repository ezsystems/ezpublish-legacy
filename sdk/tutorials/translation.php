<?php
//
// Created on: <12-Dec-2002 22:15:53 gl>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
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

<p>
eZ publish 3 requires two programs to create and maintain translations, <b>ezlupdate</b> and <b>linguist</b>. These programs are based on the same tools from the Qt toolkit by <a href="http://www.trolltech.com">Trolltech</a>. The unix version of this toolkit is released under the GPL. eZ systems will provide binaries for Windows and Mac OS X. The linguist is unmodified from the Qt original, so you can also get it from other sources, such as RPMs. ezlupdate is modified to make it understand eZ publish files.
</p>



<h2>Building the program</h2>

<p>
The linguist is not provided with eZ publish 3, as this is distributed in the <b>Qt</b> library available from <a href="http://www.trolltech.com">Trolltech</a>.
</p>

<p>
The following assumes that you are building under unix. If you have a commercial licence of Qt for Windows and/or Mac OS X, you can build it in a similar way. If you don't have such a licence, you can get binaries from eZ systems.
</p>

<p>
First, make sure that you have the Qt library installed. If you use a package system such as RPM, make sure that you also have the qt-devel package.
</p>

<p>
If you build ezlupdate against the Qt/X11 library, it will require X11 to run, even though it is a console program. If you have installed eZ publish on a server without X11 it is recommended that you build ezlupdate against Qt/embedded.
</p>

<p>
Now you are ready to build the program:
</p>

<pre class="example">
cd support/lupdate-ezpublish3
qmake ezlupdate.pro
make
</pre>

<p>
If everything went ok, the binary will be found in <b>bin/linux</b> under the main eZ publish directory.
</p>



<h2>Making translations</h2>

<p>
First of all, you must decide the locale code of your language. eZ publish 3 uses locale codes on the form <b>aaa-AA</b>, where the 3 first lowercase letters describe the language, while the last two uppercase letter describe the country in which the language is spoken. For instance, English as it is spoken in Great Britain would be eng-GB, while US English is eng-US.
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
You can also create a variation of a locale, you will for instance find two variations of nor-NO, nor-NO@intl and nor-NO@spraakraad, that are slight modofications of the original.
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
When you are done translating in linguist, open <b>settings/site.ini</b>. Go to the section <b>[RegionalSettings]</b> and set <b>Locale=nor-NO</b> to translate the interface, and <b>ContentObjectLocale=nor-NO</b> if you want to set the language of content objects. Finally, set <b>TextTranslation=enabled</b>, or the default (eng-GB) will be used.
</p>

<p>
Sample entry in settings/site.ini:
</p>

<pre class="example">
[RegionalSettings]
Locale=nor-NO
ContentObjectLocale=nor-NO
TextTranslation=enabled
</pre>

<p>
To distribute your translation, create a compressed archive, for instance .zip or tar.gz, of these two files:<br />
<b>share/locale/nor-NO.ini</b><br />
<b>share/translations/nor-NO/translation.ts</b>
</p>

<p>
You could for instance do it like this:
</p>

<pre class="example">
tar -zcvf nor-NO.tar.gz share/locale/nor-NO.ini share/translations/nor-NO/translation.ts
</pre>

<p>
To install a translation, simply unpack the package and set the appropriate entries in settings/site.ini. These are the keys you need to change to enable the translation:
</p>

<pre class="example">
[RegionalSettings]
Locale=nor-NO
TextTranslation=enabled
TranslationCache=enabled
</pre>



<h2>Making i18n-friendly templates</h2>

<p>
All user-visible strings in templates should be embedded in the <b>i18n operator</b>. All such strings can be translated in the linguist. The i18n operator is used like this:
</p>

<pre class="example">
&lt;b&gt;{"This text can be translated"|i18n("design/mydesign/path")}&lt;/b&gt;
&lt;input type="submit" name="MyButton" value="{'My Cool Button'|i18n('design/mydesign/path')}" /&gt;
{include uri="design:gui/button.tpl" name=remove id_name=RemoveButton value="Click to remove"|i18n("design/mydesign/path")}
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
&lt;b&gt;{"The node %1 is a child of %2."|i18n("design/mydesign/path",,array($node.name,$parent_node.name))}&lt;/b&gt;
&lt;b&gt;{"The node %node is a child of %parent_node."|i18n("design/mydesign/path",,hash("%node",$node.name,"%parent_node",$parent_node.name))}&lt;/b&gt;
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
&lt;b&gt;{"The node %1 is a child of %2."|x18n("myextension","design/mydesign/path",,array($node.name,$parent_node.name))}&lt;/b&gt;
</pre>



<h2>Making i18n-friendly PHP code</h2>

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
