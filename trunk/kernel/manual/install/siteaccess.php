<h1>Using site access</h1>

<ul>
	<li><a href="#WhatIs">What is site access?</a></li>
	<li><a href="#Siteaccess_configuration">Site access configuration</a></li>
	<ul>
		<li><a href="#Host">Host</a></li>
		<ul>
			<li><a href="#HostMatchMap">Host Match Map</a></li>
		</ul>
		<li><a href="#URI">URI</a></li>
		<li><a href="#Port">Port</a></li>
		<li><a href="#Debug">Debug</a></li>
	</ul>
</ul>

<h2 id="WhatIs">What is site access?</h2>
<p>
	Once you've gotten eZ publish up and running on a server you need to configure the system.
	You can have several different sites running on the same eZ publish installation. To distinguish
	between these sites you need to set up something called site access. The site access defines
	how eZ publish will recognize which site you're accessing. eZ publish will then know which database
	to use, which design to show etc.
</p>

<h2 id="Siteaccess configuration">Site access configuration</h2>

<h3 id="Host">Host</h3>

<p>
	In this example we will name our site 'www.mybookstore.com' and we will use 'admin.mybookstore.com'
	as the administration interface. To make eZ publish fetch site access from host names you need to
	configure a DNS server and point the domains to your web server. When your DNS is up and running
	and the names resolve to your web server and your eZ publish installation you need to make eZ publish
	recognize the names and use the correct configuration. To do this you open the configuration file
	found in 'settings/site.ini' in the root of your eZ publish installation. In site.ini browse down to
	the section [SiteAccessSettings] and alter the configuration as shown below. Only the settings
	you need to change are shown below.
</p>

<pre class="example">
file: settings/site.ini

[SiteAccessSettings]
MatchOrder=host
HostMatchRegexp=^(.+)\.mybookstore\.com$
HostMatchSubtextPost=\.mybookstore\.com
</pre>

<p>
	Here we told eZ publish to take the part before .mybookstore.com of the url and map it to a directory in settings/siteaccess.
	If we enter 'www.mybookstore.com' in our browser eZ publish will look a directory called 'www' in settings/siteacces.

	Now that eZ publish knows how to distinguish between the two domains we need to create a configuration
	file for each site. This is done by creating two folders under 'settings/siteaccess/' which
	corresponds to our matches ( 'www' and 'admin' ). In both these folder you need to create a file
	called site.ini.append. This is the configuration file which will be used to override any of
	the standard settings in eZ publish. We will keep our example simple and have just made a few
	settings to distinguish between the two sites. You can see the two configuration files below.
</p>

<pre class="example">
file: settings/siteaccess/admin/site.ini.append

[SiteSettings]
LoginPage=custom

[SiteAccessSettings]
RequireUserLogin=true
</pre>

<p>
	The configuration LoginPage=custom means that eZ publish will use a separate template for
	the login page of the administration site. RequireUserLogin=true tells eZ publish not to
	let anyone inside eZ publish unless they're logged into the system.
</p>

<pre class="example">
file: settings/siteaccess/www/site.ini.append

[DesignSettings]
SiteDesign=mybookstore

[SiteAccessSettings]
RequireUserLogin=false
</pre>

<p>
The settings above applies to the website. SiteDesign=mybookstore means that eZ publish
will prefer to use the design for the site found in design/mybookstore, RequireUserLogin=false
is set so that users do not have to log into eZ publish to browse the website.
</p>

<h4 id="HostMatch">Host Match Map</h3>
<p>
In the previous example we used a regexp to map part of the url to different sites. This is a very
powerful way of matching, but if you do not have experience or do not understand regexp,
this approach might be difficult. Instead of regexp we can make a list of URL's and tell eZ publish 3
to match them to a site. See the configuration example below.
</p>
<pre class="example">file: settings/site.ini

[SiteAccessSettings]
MatchOrder=host
HostMatchType=map
HostMatchMapItems[]=mybookstore.com;user
HostMatchMapItems[]=www.mybookstore.com;user
HostMatchMapItems[]=admin.mybookstore.com;admin
</pre>

<p>
As you might see from the example we mapped 'mybookstore.com' and 'www.mybookstore.com' to the user site
and 'admin.mybookstore.com' to the admin site. To make individual settings for the
two sites edit 'settings/siteaccess/user/site.ini.append' and 'settings/siteaccess/admin/site.ini.append'.
The site.ini.append will override settings from 'settings/site.ini'
</p>


<h3 id="URI">URI</h3>
<p>
	We will stick with the www.mybookstore.com example, but now we will use URI to recognize the different sites.
	http://www.mybookstore.com/index.php/admin will be our URL to the admin site and http://www.mybookstore/index.php
	the URL to our user site. Here we only need the 'www.mybookstore.com' domain to point to our web server.
	In settings/site.ini set these settings:
</p>

<pre class="example">
file: settings/site.ini

[SiteSettings]
DefaultAccess=user

[SiteAccessSettings]
MatchOrder=uri
</pre>

<p>
	With the DefaultAccess variable we told eZ publish to use the 'user' site if it is unable to match a site.
	Now eZ publish will take the last part of the entered URL and map it do a directory under settings/siteaccess/.
	If we now enter http://www.mybookstore.com/index.php/admin, eZ publish will look for a directory called 'admin'.
	Using URI is useful when you want multiple sites but don't have a domain for each site or don't have the ability
	to setup virtual hosts for each site. This is the configuration the eZ publish Windows installer uses.
</p>


<h3 id="Port">Port</h3>
<p>
	By setting up site access to use ports we can let eZ publish distinguish different sites by mapping a port to a site.
	In this example we will use port 80 for the user site and port 81 for the admin site. When the configuration is done
	we should be able to access the user site on http://www.mybookstore.com:80 and the admin site
	with http://www.mybookstore.com:81. The settings in settings/site.ini required for this setup is shown below.
</p>
<pre class="example">
file: settings/site.ini

[SiteAccessSettings]
MatchOrder=port

[PortAccessSettings]
80=user
81=admin
</pre>


<h3 id="Debug">Debug</h3>
<p>
If you have problems getting your site access configuration right, you can turn on site access debugging.
There are two settings for this. DebugAccess prints out the site access that was chosen, and
DebugExtraAccess prints some extra information on access matching.
</p>
<pre class="example">
file: settings/site.ini

[SiteAccessSettings]

DebugAccess=enabled
DebugExtraAccess=enabled

</pre>
