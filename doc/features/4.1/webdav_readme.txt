eZ Publish WebDAV engine based on eZ Components
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. contents::

Introduction
============

New in eZ Publish 4.1 is the porting of the WebDAV engine to eZ Components.
Feature-wise the WebDAV engine stays the same, but it is capitalizing on the
solid base of eZ Components with its unit tests, documentation and
extensibility.

Being based on eZ Components will allow for easier maintenance and addition
of new features, such as locking.


New features
============

Delete files behaviour
----------------------

By default, the WebDAV files are sent to Trash when deleted. The administrator
of the site is responsible to empty the Trash. If you want to remove the files
directly (without using the Trash), this setting must be changed in content.ini
::

  [RemoveSettings]
  # delete or trash
  DefaultRemoveAction=delete 


Copy
----

The previous eZ Publish WebDAV engine did not support copying of files and
folders. The new WebDAV implementation based on eZ Components supports copying
files and folders.

Copying and moving files and folders between siteaccesses is not supported.


Supported WebDAV clients
========================

The Webdav component from eZ Components was designed with compatibility with
as many WebDAV clients in mind. The Webdav component contains thousands of unit
tests which tests this compatibility.

Unfortunately the number of supported WebDAV clients for the eZ Publish WebDAV
engine is smaller, due to missing locking support in particular, and due to
missing Digest authentication mechanisms implemented for WebDAV (as opposed to
exising Basic authentication). These issues will be addressed in a future
iteration of the WebDAV engine and will allow for a higher number of
supported clients.

Below is a list of WebDAV clients and know issues for them. For each client
it is explained how to open a WebDAV connection to an eZ Publish installation.
In the examples it is assumed that the WebDAV host name is **webdav.ezp**.


Bitkinex
--------

Supported.


Configuration
'''''''''''''

This WebDAV client has a know issue with uploading of files with spaces in the
file names or with uppercase extensions: since eZ Publish converts file names
to clean URL aliases, Bitkinex cannot detect correctly that the file was
uploaded and it will try to upload it again, resulting in an infinite loop.

Solution: disable checking of successful upload. On the WebDAV connection,
right-click and select Properties, go to the Transfers section, uncheck
'Inherit properties...' and then uncheck 'Upload: destination file exists'
from the 'Post-transfer data integrity checks' list.


Create a connection
'''''''''''''''''''

In the main window of the application, go to the menu option Data Source->
New->Http/WebDAV, give a name to the connection, then type **webdav.ezp**
as the Server address, and type your username and password in the
Authorization box.


cadaver
-------

Supported. This command-line client has a very good implementation of the
WebDAV RFC and it is known to work without problems with eZ Publish.


Create a connection
'''''''''''''''''''

To open a connection, type on the command prompt::

  cadaver webdav.ezp

Or run cadaver and type::

  open webdav.ezp

Cadaver uses Unix-like commands to interact with WebDAV resources. Type 'help'
to see a list of possible commands, and type 'help <command>' for more
information about a command, example 'help get'.


Finder
------

Reading content is supported, but creating content (upload, create folder) is
not supported yet due to missing locking support in the WebDAV engine. Locking
is planned for a future iteration of the WebDAV engine and Finder will
become supported at that time.

Create a connection
'''''''''''''''''''

In the Finder menu, use the option Go->Connect to Server..., where you enter
**http://webdav.ezp** as the Server Address.


Internet Explorer
-----------------

Partially supported. Uploading a file to an empty directory by dragging the
file to the opened directory does not work, instead you must drag the file to
the directory while being in the directory structure one level lower (eg. do
not open /plain_site_user/Content/folder, but instead open
/plain_site_user/Content and drag the file to the "folder" directory. In some
versions of Windows (eg. Windows 7) it is not possible to see the contents of
folders with Windows Explorer, but with using other file management software
(so-called Explorer-replacements) this is possible.


Configuration
'''''''''''''

Several configuration steps are required to get Internet Explorer to be able
to open a WebDAV connection to an eZ Publish installation.

1. Enable Basic authentication by going to the Registry (Start->Run...,
regedit.exe) and then to the key:

HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Services\WebClient\Parameters

Create (if it doesn't exist) a DWORD value with the name UseBasicAuth, with
the value 1 (1 = enable Basic authentication, 0 = disable Basic
authentication).

2. Install the Software Update for Web Folders (KB907306) from Microsoft:

http://www.microsoft.com/downloads/details.aspx?familyid=17C36612-632E-4C04-9382-987622ED1D64


Create a connection
'''''''''''''''''''

To create a WebDAV connection in Internet Explorer, go to the menu File->Open
(or press Ctrl-O). In the dialog windows that appears, type
**http://webdav.ezp/** and check the option Open as Web Folder.

In Internet Explorer 8 this option is not available anymore, so the only way
to open a WebDAV connection without using another WebDAV client is to create
a network connection for it (see below). Using Internet Explorer is not
actually required in order to open a WebDAV connection, so the following
can be done in any version of Microsoft Windows.

To create a network connection, right-click on the My Computer icon and choose
Map Network Drive (or go to My Network Places and click on Add a network place).
In the Add Network Place Wizard click on "Choose another network location" (on
Windows Vista and Windows 7 there is an extra step in which you need to click
on the link "Connect to a Web site that you can use to store your documents and
pictures"), then enter **http://webdav.ezp:port/siteaccess**, where
*siteaccess* is a siteaccess that you will use (eg. **plain_site_user**) and
*port* is the port on which you connect to WebDAV (**80** by default). You will
be requested to enter the username and password for the connection. The
connection will appear as a shortcut in My Computer.

WebDAV operations are somehow limited in Internet Explorer, in such ways that
you will not be able to click on a file to open it, but must instead drag it
to a local folder and then open it.


Konqueror
---------

Partially supported. Uploading files does not work, due to an apparent issue
in Konqueror (upload a new file will not work, but uploading a file which
already exists will overwrite the existing file). Creating directories works.
This is being investigated and a fix will be provided soon if possible.


Create a connection
'''''''''''''''''''

In Konqueror write the name of the WebDAV server using the protocol
**webdav://** in the address bar::

  webdav://webdav.ezp


Nautilus
--------

Partially supported. Uploading a file to an empty directory by dragging the
file to the opened directory does not work, instead you must drag the file to
the directory while being in the directory structure one level lower (eg. do
not open /plain_site_user/Content/folder, but instead open
/plain_site_user/Content and drag the file to the "folder" directory.


Create a connection
'''''''''''''''''''

In Nautilus, use the menu option File->Connect to Server..., select
WebDAV (HTTP) from the Service Type drop-down menu, and enter **webdav.ezp**
in the Server text box. The connection will appear under Places in the main
program window, under the name "webdav.ezp".


Transmit
--------

Supported.


Configuration
'''''''''''''

Disable uploading of **.DS_Store** files. Use the menu option Transmit->
Advanced Preferences..., click Advanced Preferences, click the plus button
to add a new setting, type **webdav.ezp** and uncheck Transfer .DS_Store
files.


Create a connection
'''''''''''''''''''

In Transmit, click on the Connect tab next to Favorites, type **webdav.ezp**
in the Server text box, type your username and password in the respective
boxes, select WebDAV from the Protocol drop-down menu, then click on the
Connect button. The connection will appear as a tab in the main window of the
program. You can add the connection to favorites by going to the menu
Favorites->Add to Favorites...


Planned features
================

Locking
-------

At the moment locking is not yet implemented in eZ Publish WebDAV. Locking
will be added in a future release, based on the new object states feature of
eZ Publish.


Better WebDAV client support
----------------------------

At the moment the number of supported WebDAV clients is limited. A higher
number of clients will be supported in a future iteration of the WebDAV,
made possible especially with the addition of locking support.

