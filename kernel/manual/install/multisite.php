<h1>Multi site configuration</h1>

<ul>
	<li><a href="#Requirements">Requirements</a></li>
	<ul>
		<li><a href="#Linux_FreeBSD_Macos_SiteAccess">Siteaccess</a></li>
	</ul>

	<li><a href="#Windows_Installation">Installation on Windows</a></li>
	<ul>
		<li><a href="#Windows_SiteAccess">Siteaccess</a></li>
	</ul>
</ul>

<p>
When setting up serveral sites with only one eZ publish 3 installation using site access things you might want to
have seperate cache directories and different directories for storing pictures, files and other things you store.
The default settings in eZ publish 3 stores cache and files in a common directory for all sites. This can be changed
by setting some variables in your site.ini.append for each of your sites.
</p>

<p>
In this example we will seperate storage files and cache for myfirst.ezpsite.com, admin.myfirst.ezpsite.com, mysecond.ezpsite.com
and admin.mysecond.ezpsite.com. After setting up site access (which you can read more about <a href="siteaccess">here</a>)
we need to set some additional settings in the site.ini.append files.
</p>

<pre class="example">
file: settings/siteaccess/myfirst/site.ini.append
[FileSettings]
StorageDir=myfirst/storage
CacheDir=myfirst/cache
</pre>
<br />

<pre class="example">
file: settings/siteaccess/admin.myfirst/site.ini.append
[FileSettings]
StorageDir=myfirst/storage
CacheDir=myfirst/cache
</pre>
<br />

<pre class="example">
file: settings/siteaccess/mysecond/site.ini.append
[FileSettings]
StorageDir=mysecond/storage
CacheDir=mysecond/cache
</pre>
<br />

<pre class="example">
file: settings/siteaccess/admin.mysecond/site.ini.append
[FileSettings]
StorageDir=mysecond/storage
CacheDir=mysecond/cache
</pre>

<p>
Now we told eZ publish to store myfirsts cache in var/myfirst/cache and myfirsts files in /var/mysecond/storage. Same goes
for the mysecond site.

</p>
<p class="important"><b>Important:</b> It is important that both the user and admin site has the same storage directory, or else
if you e.g upload a new file using the admin site the user site wont be able to locate the file because it is looking for the file
in a different directory.
</p>
