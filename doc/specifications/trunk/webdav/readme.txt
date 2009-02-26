WebDAV in eZ Publish
~~~~~~~~~~~~~~~~~~~~

Server Configuration
====================

The eZ Publish installation must be configured to process all WebDAV requests
through webdav.php.

See http://ez.no/doc/ez_publish/technical_manual/4_0/features/webdav for
more information about how to activate WebDAV support on an eZ Publish
installation.

In addition, WebDAV support must be enabled in webdav.ini::

  [GeneralSettings]
  EnableWebDAV=true

For each siteaccess there should be a webdav.ini.append.php which specifies
the start node of that siteaccess::

 [GeneralSettings]
 StartNode=2

WebDAV Logging is enabled by (in webdav.ini or in overwrites)::

 [GeneralSettings]
 Logging=enable

The locations of the WebDAV log files are var/log/webdav.log (from webdav.php)
and var/log/<siteaccess>/log/webdav.log (from ezwebdavcontentbackend.php).

For multiple siteaccesses, the setting PathPrefix and PathPrefixExclude should
be used in site.ini and overrides of site.ini::

  [SiteAccessSettings]

  # Hides this part from the start of the url alias
  PathPrefix=

  # Which URLs to exclude from being affected by PathPrefix setting.
  # URLs containing the specified texts after siteaccess name will not be affected by PathPrefix
  PathPrefixExclude[]
  #PathPrefixExclude[]=media

By default, the WebDAV files are sent to Trash when deleted. The administrator
of the site is responsible to empty the Trash. If you want to remove the files
directly (without using the Trash), this setting must be changed in content.ini
::

  [RemoveSettings]
  # delete or trash
  DefaultRemoveAction=delete

To change the way file names are created when uploading files, use the
TransformationGroup setting in site.ini and its overrides::

  [URLTranslator]
  TransformationGroup=urlalias
  # Uncomment this to get the new-style url aliases with Unicode support
  #TransformationGroup=urlalias_iri
  # Uncomment this to get the old-style url aliases
  #TransformationGroup=urlalias_compat


Client configuration
====================

In the following examples it is assumed that the WebDAV host name is
webdav.ezp.

BitKinex
--------

Disable checking of successful upload: on the WebDAV connection, right-click
and select Properties, go to the Transfers section, uncheck 'Inherit
properties...' and then uncheck 'Upload: destination file exists' from the
'Post-transfer data integrity checks' list.


Cadaver
-------

At the command-line type::

  cadaver webdav.ezp

Or run cadaver and type::

  open webdav.ezp

Cadaver uses Unix-like commands to interact with WebDAV resources. Type 'help'
to see a list of possible commands, and type 'help <command>' for more
information about a command, example 'help get'.


Finder
------

@todo add information how to set up a WebDAV connection in Finder.


Internet Explorer
-----------------

@todo add information how to set up a WebDAV connection in IE.

Enable Basic authentication by going to the Registry (Start->Run...,
regedit.exe) and then to the key:

HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Services\WebClient\Parameters

Create (if it doesn't exist) a DWORD value with the name UseBasicAuth, with
the value 1 (1 = enable Basic authentication, 0 = disable Basic
authentication).


Konqueror
---------

In Konqueror it is sufficient to write the name of the WebDAV server using
the protocol webdav:// ::

  webdav://webdav.ezp


Nautilus
--------

@todo add information how to set up a WebDAV connection in Nautilus.


Current issues
==============

Moving/Copying content between 2 siteaccesses
---------------------------------------------

This is not possible yet (409 Conflict is returned), but some clients like
BitKinex behave strangely by trying moving/copying again, with the source
file disappearing, and the destination file not appearing.




..
   Local Variables:
   mode: rst
   fill-column: 79
   End:
   vim: et syn=rst tw=79
