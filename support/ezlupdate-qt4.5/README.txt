Translation/Internationalization (i18n) of eZ Publish
-----------------------------------------------------

0. Introduction

1. Building the program

2. Making translations


Copyrights
----------
"eZ Publish" is Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
"Trolltech", "Qt" and "Qt Linguist" are Copyright (C) 2000-2005 Trolltech AS.
All rights reserved.


0. Introduction
---------------

eZ Publish requires two programs to create and maintain translations,
'ezlupdate' and 'linguist'. These programs are based on the same tools from the
Qt toolkit by Trolltech (www.trolltech.com). The unix version of this toolkit
is released under the GPL. eZ Systems will provide binaries for Windows and
Mac OS X.
The linguist is unmodified from the Qt original, so you also can get this from
other sources, such as RPMs. ezlupdate is modified to make it understand
eZ Publish files.



1. Building the program
-----------------------

The linguist is not provided with eZ Publish, as this is distributed in the
Qt library available from Trolltech.

The following assumes that you are building under unix. If you have a
commercial licence of Qt for Windows and/or Mac OS X, you can build it in a
similar way. If you don't have such a licence, you can get binaries from
eZ Systems.

First, make sure that you have the Qt library installed. If you use a package
system such as RPM, make sure that you also have the qt-devel package. You 
need version 4.5 of the Qt library.

If you build ezlupdate against the Qt/X11 library, it will require X11 to run,
even though it is a console program. If you have installed eZ Publish on a
server without X11 it is recommended that you build ezlupdate against
Qt/embedded.

Build instructions:

cd support/ezlupdate-qt4.5/ezlupdate
qmake ezlupdate.pro
make

If everything went ok, the binary will be found in bin/linux under the main
eZ Publish directory.



2. Making translations
----------------------

First of all, you must decide the locale code of your language. eZ Publish
uses locale codes on the form aaa-AA, where the 3 first lowercase letters
describe the language, while the last two uppercase letter describe the country
in which the language is spoken. For instance, English as it is spoken in Great
Britain would be eng-GB, while US English is eng-US.

Countries are specified by the ISO 3166 Country Code
http://www.iso.ch/iso/en/prods-services/iso3166ma/index.html

Language is specified by the ISO 639 Language Code
http://www.w3.org/WAI/ER/IG/ert/iso639.htm

You can also create a variation of a locale, you will for instance find two
variations of nor-NO, nor-NO@intl and nor-NO@spraakraad, that are slight modofications of the original.

For the rest of this part, I assume you are translating nor-NO.

Copy share/locale/eng-GB.ini to share/locale/nor-NO.ini. Edit this file with a
text editor. Here you set locale details such as time formats, currency and the
names of the week days.

Now, enter the main eZ Publish directory in a terminal and type
bin/linux/ezlupdate -v nor-NO
(Provided that nor-NO is your locale, of course. -v is for verbose, showing
messages about what happens. You can also use -vv for extra verbose output, or
omit it for silent behavior. Run bin/linux/ezlupdate -h for an explanation of
the arguments.

You will now find a translation in share/translations/nor-NO/translation.ts or
similar for other locales. Open this file in linguist and do the translation.

You will find documentation for linguist on Trolltechs page:
http://doc.trolltech.com/4.5/linguist-translators.html

When you are done translating in linguist, open settings/site.ini. Go to the
section [RegionalSettings] and set Locale=nor-NO to translate the interface,
and ContentObjectLocale=nor-NO if you want to set the language of content
objects. Finally, set TextTranslation=enabled, or the default (eng-GB) will be
used.

To distribute your translation, create a compressed archive, for instance .zip
or tar.gz, of these two files:
share/locale/nor-NO.ini
share/translations/nor-NO/translation.ts

You can also use the shell script bin/shell/makei18ndist.sh to do this.


Please see the translation tutorial here for more information:
http://ez.no/products/ez_publish_cms/documentation/configuration/configuration/language_and_charset/creating_a_new_translation
http://ez.no/products/ez_publish_cms/documentation/configuration/configuration/language_and_charset/installing_a_language_pack
