Translation of eZ publish 3
---------------------------

0. Introduction

1. Building the program

2. Making translations

3. Installing translations

4. Making your PHP-code translation-friendly

5. Making your templates translation-friendly


0. Introduction
---------------

eZ publish 3 requires two programs to create and maintain translations,
'ezlupdate' and 'linguist'. These programs are based on the same tools from the
Qt toolkit by Trolltech (www.trolltech.com). The unix version of this toolkit
is released under the GPL. eZ systems will provide binaries for Windows and
Mac OS X.
The linguist is unmodified from the Qt original, so you also can get this from
other sources, such as RPMs. ezlupdate is modified to make it understand
eZ publish files.



1. Building the program
-----------------------

The linguist is not provided with eZ publish 3, as this is distributed in the
Qt library available from Trolltech.

The following assumes that you are building under unix. If you have a
commercial licence of Qt for Windows and/or Mac OS X, you can build it in a
similar way. If you don't have such a licence, you can get binaries from
eZ systems.

First, make sure that you have the Qt library installed. If you use a package
system such as RPM, make sure that you also have the qt-devel package.

If you build ezlupdate against the Qt/X11 library, it will require X11 to run,
even though it is a console program. If you have installed eZ publish on a
server without X11 it is recommended that you build ezlupdate against
Qt/embedded.

Build instructions:

cd support/lupdate-ezpublish3
qmake ezlupdate.pro
make

If everything went ok, the binary will be found in bin/linux under the main
eZ publish directory.



2. Making translations
----------------------
