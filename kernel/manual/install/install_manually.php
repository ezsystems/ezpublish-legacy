<h1>Install eZ publish 3 manually</h1>

<ul>
	<li><a href="#Requirements">Requirements</a></li>
	<li><a href="#Linux_FreeBSD_Macos">Installation on Linux, FreeBSD and Mac OS X</a></li>
	<ul>
		<li><a href="#Linux_FreeBSD_Macos_Database">Database setup</a></li>

		<li><a href="#Linux_FreeBSD_Macos_Demodata">Demo data</a></li>
		<li><a href="#Linux_FreeBSD_Macos_Configure">Configure</a></li>
		<li><a href="#Linux_FreeBSD_Macos_VirtualHost">VirtualHost</a></li>
		<li><a href="#Linux_FreeBSD_Macos_SiteAccess">Siteaccess</a></li>
	</ul>

	<li><a href="#Windows_Installation">Installation on Windows</a></li>
	<ul>
		<li><a href="#Windows_Database">Database setup</a></li>
		<li><a href="#Windows_Demodata">Demo data</a></li>
		<li><a href="#Windows_Configure">Configure</a></li>
		<li><a href="#Windows_VirtualHost">VirtualHost</a></li>
		<li><a href="#Windows_SiteAccess">Siteaccess</a></li>
	</ul>
</ul>

<h2 id="Requirements"> eZ publish 3 requirements</h2>
<p>
	You need to have Apache, MySQL, ImageMagick and PHP installed to run eZ publish 3.
	PHP needs to have compiled-in support for either MySQL or PostgreSQL.
	For image conversion support you need to have GD compiled in PHP or
	ImageMagick installed on your system.
</p>

<ul>
	<li>PHP >= 4.1.x <br /><a href="http://www.php.net">http://www.php.net </a> </li>
	<li>Apache 1.3 (apache 2.0 might/might not work) <br /><a href="http://www.apache.org">http://www.apache.org</a></li>
	<li>Either MySQL or PostgreSQL <br /><a href="http://www.mysql.org">http://www.mysql.org</a><br /><a href="http://www.postgresql.org">http://www.postgresql.org</a></li>
	<li> ImageMagick and/or GD compiled in PHP for image conversion (not required) <br /><a href="http://www.imagemagick.org">http://www.imagemagick.org</a></li>
</ul>

<p>
	The installation process for the above programs are very well documented on
	their homepage, so we will not include that here.
</p>


<h2 id="Linux_FreeBSD_Macos">Installation on Linux, FreeBSD and Mac OS X</h2>
<ol>
	<li>Unpack ezpublish-xxx.tar.gz into the <httproot> folder<pre class="example"> $ tar xvfz ezpublish-xxx.tar.gz -C &lt;httproot&gt;</pre></li>
	<li>Now go to the extracted eZ publish directory <pre class="example"> $ cd &lt;httproot&gt;/ezpublish-xxx/</pre></li>
	<li>Run the modfix.sh script <pre class="example"> $ bin/modfix.sh</pre></li>
</ol>

<h3 id="Linux_FreeBSD_Macos_Database">Database setup</h3>

<h4>MySQL</h4>
<p>
	We need to login, create a new database, grant permissions to a user and insert a database.
</p>

<ol>
	<li><pre class="example"> $ mysql -u root -p &lt;password&gt;</pre></li>
	<li>You should now have a "mysql&gt;" prompt, create a new database <pre class="example"> mysql&gt; create database &lt;name_of_database&gt;</pre></li>
	<li>Grant permissions <pre class="example"> mysql&gt; grant all on &lt;name_of_database&gt;.* to &lt;user&gt;@localhost
	identified by '&lt;password&gt;';</pre></li>
	<li>If you don't want to install demodata <pre class="example"> $ mysql -u &lt;user&gt; -p&lt;password&gt; &lt;name_of_database&gt;
	&lt; &lt;httproot&gt;/ezpublish-xxx/kernel/sql/mysql/kernel_clean.sql</pre>
	If you do want the demodata <pre class="example"> $ mysql -u &lt;user&gt; -p&lt;password&gt; &lt;name_of_database&gt;
	&lt; &lt;httproot&gt;/ezpublish-xxx/kernel/sql/mysql/demokernel.sql </pre></li>
</ol>


<h4 id="">PosgreSQL</h4>
<p>
	We need to login, create a new database, grant permissions to a user and insert a database.
</p>

<ol>
	<li>Become the PostgreSQL super user (normally called postgres)<pre class="example">$ su &lt;postgres_super_user&gt;</pre></li>
	<li>Create a postgresql user<pre class="example">$ createuser &lt;user&gt;</pre></li>
	<li>Create a database <pre class="example">$ createdb &lt;name_of_database&gt;</pre></li>
	<li>Demodata is not available for PostgreSQL at the moment, so we have to install the kernel_clean.sql file<pre class="example">$ psql -U &lt;ezpublish_user&gt;  &lt;name_of_database&gt;
	&lt; &lt;httproot&gt;/ezpublish-xxx/kernel/sql/postgresql/kernel_clean.sql</pre></li>
</ol>

<h3 id="Linux_FreeBSD_Macos_Demodata">Demo data</h3>
<p>
	Demodata is only available for MySQL. Before you proceed make sure you
	installed the demokernel.sql file and NOT the kernel_clean.sql file.
</p>
<p>
	To install the demodata all you have to do is unpack the var.tgz file
</p>
<ol>
	<li>Go to &lt;httproot&gt;\ezpublish-xxx <pre class="example"> $ cd &lt;httproot&gt;/ezpublish-xxx</pre></li>
	<li>Unpack var.tgz <pre class="example"> $ tar xvfz var.tgz</pre></li>
</ol>

<h3 id="Linux_FreeBSD_Macos_Configure">Configure eZ publish</h3>
<p>
	Open &lt;httproot&gt;/ezpublish-xxx/settings/site.ini with your favourite editor
	and set the correct setting in the [Database Settings] section.
	You need to select what database implementation you would like to use,
	hostname of database server to connect to, username, password and database name.
</p>

<pre class="example">[Database Settings]
# Use either ezmysql or ezpostgresql
Database Implementation=ezpostgresql
# Name of server to connect to
Server=localhost
# DB user name
User=&lt;user&gt;
# DB Password
Password=&lt;password&gt;
# database name you have created on previous step
Database=&lt;name_of_database&gt;
</pre>





<h3 id="Linux_FreeBSD_Macos_VirtualHost">Virtualhost setup</h3>
<p>
	You can use eZ publish with a virtualhost setup. When using a virtualhost you
	don't need to specify the index.php in the URL.
	Below is a sample configuration for virtualhost setup. Include this in your apache config file and restart apache when you are done.
</p>
<pre class="example">&lt;Virtualhost &lt;you_ip_address&gt;&gt;
  &lt;Directory&gt; &lt;httproot&gt;/ezpublish-xxx/&gt;
    Options FollowSymLinks Indexes ExecCGI
    AllowOverride None
  &lt;/Directory&gt;

  RewriteEngine On
  RewriteRule !\.(gif|css|jpg|png)$ &lt;httproot&gt;/ezpublish-xxx/index.php

  ServerAdmin root@localhost
  DocumentRoot &lt;httproot&gt;/ezpublish-xxx/
  ServerName &lt;you_ip_address&gt;
&lt;/VirtualHost&gt;
</pre>


<h3 id="Linux_FreeBSD_Macos_SiteAccess">Siteaccess settings</h3>
<p>
	The site access defines how eZ publish will recognize which site you're accessing.
	eZ publish will then know which database to use, which design to show etc.
	There are four ways of letting eZ publish recognize a site access, by URI, host name, port or file name.
	The most common way is to use the host name.

<h4>Host name</h4>
<p>
	In this example we will setup a site called mysite.com. And we want to access the admin interface
	with this url: admin.mysite.com. Open settings/site.ini and set these settings:
</p>

<pre class="example">[SiteAccessSettings]
MatchOrder=host
HostMatchRegexp=^(.+)\.mysite\.com$
HostMatchSubtextPost=\.mybookstore\.com
</pre>

<p>
	Here we told eZ publish to take the part before .mysite.com of the url and map it to a directory in settings/siteaccess.
	If we enter 'www.mysite.com' in our browser eZ publish will look for the directory 'www' in settings/siteacces.
	The next step is then to create a directory in settings/siteaccess called 'www'
</p>

<pre class="example">$ mkdir settings/siteaccess/www</pre>

<p>
	Now in settings/siteaccess/www/site.ini.append you can set your own settings that will override the site.ini.
</p>

<h4>URI</h4>
<p>
	We will stick with the mysite.com example, but now we will use URI to recognize different sites.
	http://localhost/index.php/admin will be our URL to the admin site and http://localhost/index.php the URL to our user site.
	In settings/site.ini set these settings:
</p>

<pre class="example">[SiteSettings]
DefaultAccess=user
[SiteAccessSettings]
MatchOrder=uri
</pre>

<p>
	With the DefaultAccess variable we told eZ publish to use the 'user' site if it is unable to match a site.
	This is the same way the eZ publish Windows installer is configured.
</p>

<p>
	For more indepth information on site access go to the <a href="siteaccess">site access section</a>
</p>

<h2 id="Windows_Installation">Installation on Windows</h2>
<ol>
	<li>Unpack ezpublish-xxx.tar.gz into the &lt;httproot&gt; folder. Use a program that supports .tar.gz files, like WinZip.</li>
	<li>Go to &lt;httproot&gt;\ezpublish-xxx</li>
	<li>Make sure the directory var\cache\ini exists, if not, create it.</li>
</ol>

<h3 id="Windows_Database">Database setup</h3>

<h4>MySQL</h4>
<p>
	We need to login, create a new database, grant permissions to a user and insert a database.
</p>

<ol>
	<li>Open a console window (start->run->cmd.exe or start->run->command.exe depending on the Windows version)</li>
	<li>Go to your the location of mysql and find the mysql.exe file (should be under bin\)</li>
	<li>Run <pre class="example">mysql.exe -u root -p &lt;your_mysql_password&gt;</pre></li>
	<li>You should now have have a mysql&gt; prompt. Type these mysql statements <pre class="example">mysql&gt; create database &lt;name_of_database&gt;;</pre></li>
	<li>Grant permissions <pre class="example"> mysql&gt; grant all on <name_of_database>.* to &lt;user&gt;@localhost identified
	by '&lt;password&gt;';</pre>
	<li>If you don't want demodata <pre class="example">$ mysql.exe -u &lt;user&gt; -p&lt;password&gt; &lt;name_of_database&gt;
	&lt; &lt;httproot&gt;\ezpublish-xxx\kernel\sql\mysql\kernel_clean.sql</pre></li>
	If you do want demodata <pre class="example"> $ mysql.exe -u&lt;user&gt; -p&lt;password&gt; &lt;name_of_database&gt;
	&lt; &lt;httproot&gt;\ezpublish-xxx\kernel\sql/mysql\demokernel.sql</pre></li>
</ol>

<h3 id="Windows_Demodata">Demo data</h3>
<p>
	Demodata is only available for MySQL. Before you proceed make sure you
	installed the demokernel.sql file and NOT the kernel_clean.sql file.
</p>
<p>
	To install the demodata all you have to do is unpack the var.tgz file
</p>
<ol>
	<li>Go to &lt;httproot&gt;\ezpublish-xxx</li>
	<li>Unpack var.tgz into &lt;httproot&gt;\ezpublish-xxx</li>
</ol>


<h3 id="Windows_Configure">Configure eZ publish</h3>
<p>
	Open &lt;httproot&gt/ezpublish-xxx/settings/site.ini in notepad and set the correct settings in the [Database Settings] section.
	You need to set what database implementation you use, hostname of database
	server to connect to, user name, password, database name.
</p>
<pre class="example">[Database Settings]
# Use either ezmysql or ezpostgresql
DatabaseImplementation=ezpostgresql
# Name of server to connect to
Server=localhost
# DB user name
User=&lt;user&gt;
# DB Password
Password=&lt;password&gt;
# database name you have created on previous step
Database=&lt;name_of_database&gt;
</pre>

<h3 id="Windows_VirtualHost">Virtualhost setup</h3>
<p>
	You can use eZ publish with a virtualhost setup. When using a virtualhost you don't need to specify the index.php in the URL.
</p>
<p>
	Make sure these lines exists in your apache configfile and is not commented out
</p>
<pre class="example">LoadModule rewrite_module modules/mod_rewrite.so
AddModule mod_rewrite.c</pre>
<p>
	Below is a sample configuration for virtualhost setup. Include this in your apache config file and restart apache when you are done.
</p>

<pre class="example">&lt;VirtualHost &lt;your_ip_adress&gt;>
  &lt;Directory &lt;httproot&gt;/ezpublish-xxx/&gt;
    Options FollowSymLinks Indexes ExecCGI
    AllowOverride None
  &lt;/Directory&gt;

  RewriteEngine On
  RewriteRule !\.(gif|css|jpg|png|jar)$ /index.php

  ServerAdmin root@localhost
  DocumentRoot &lt;httproot&gt;/ezpublish-xxx
  ServerName &lt;your_ip_adress&gt;
&lt;/VirtualHost&gt;
</pre>

<p class="important"> <b>Note:</b>The rewrite rule is releative to the http root and we must use '/' (slash) not '\' (backslash)</p>

<h3 id="Windows_SiteAccess">Siteaccess settings</h3>
<p>
	The site access defines how eZ publish will recognize which site you're accessing.
	eZ publish will then know which database to use, which design to show etc.
	There are four ways of letting eZ publish recognize a site access, by URI, host name, port or file name.
	The most common way is to use the host name.

<h4>Host name</h4>
<p>
	In this example we will setup a site called mysite.com. And we want to access the admin interface
	with this url: admin.mysite.com. Open settings/site.ini and set these settings:
</p>

<pre class="example">[SiteAccessSettings]
MatchOrder=host
HostMatchRegexp=^(.+)\.mysite\.com$
HostMatchSubtextPost=\.mybookstore\.com
</pre>

<p>
	Here we told eZ publish to take the part before .mysite.com of the url and map it to a directory in settings/siteaccess.
	If we enter 'www.mysite.com' in our browser eZ publish will look for the directory 'www' in settings/siteacces.
	The next step is then to create a directory in settings/siteaccess called 'www'
</p>

<pre class="example">$ mkdir settings/siteaccess/www</pre>

<p>
	Now in settings/siteaccess/www/site.ini.append you can set your own settings that will override the site.ini.
</p>

<h4>URI</h4>
<p>
	We will stick with the mysite.com example, but now we will use URI to recognize different sites.
	http://localhost/index.php/admin will be our URL to the admin site and http://localhost/index.php the URL to our user site.
	In settings/site.ini set these settings:
</p>

<pre class="example">[SiteSettings]
DefaultAccess=user
[SiteAccessSettings]
MatchOrder=uri
</pre>

<p>
	With the DefaultAccess variable we told eZ publish to use the 'user' site if it is unable to match a site.
	This is the same way the eZ publish Windows installer is configured.
</p>

<p>
	For more indepth information on site access goto the <a href="siteaccess">site access section</a>
</p>



