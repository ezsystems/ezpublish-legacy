<h1>Install eZ publish 3 using installers</h1>

<ul>

	<li><a href="#Installers">Install eZ publish 3 using installers</a></li>
	<ul>
		<li><a href="#Windows">Windows installer</a></li>
		<li><a href="#Linux">Linux installer</a></li>
		<li><a href="#FreeBSD">FreeBSD installer</a></li>
	</ul>
	<li><a href="#Security">Security</a></li>
</ul>

<p>
	There are basically four ways of installing eZ publish 3. You can use one of the
	supplied installer packages, which will install Apache, PHP, MySQL, ImageMagick and set up
	everything needed to get eZ publish up and running. Another option is to use the
	eZ publish sources and follow the setup wizard which will guide you through the
	steps of configuring your system for eZ publish.

	If you are little more experienced you can disable the setup wizard and
	configure eZ publish 3 from scratch following the installation manual. If you
	don't want to install eZ publish 3 yourself you can always hire eZ systems to
	install the software for you. You can also buy a ready hosted eZ publish 3 solution.
</p>

<p>
	The installers are designed as a quick way to get eZ publish up and running for testing purposes.
    If you plan on using eZ publish for more than just testing, we recommend you install it manually,
    either by letting the setup guide do the configuration or by configuring eZ publish by hand.
</p>


<h2 id="Installers">Install eZ publish 3 using installers</h2>
<p>
	The installers will install the required software for running eZ publish 3 on
	your platform. You will get Apache, PHP, MySQL and ImageMagick. If you don't want this
	software installed automatically, we recommend that you do not use the installers, and
    install eZ publish 3 manually.
</p>

<h3 id="Windows">Windows installer</h3>

<ol>
	<li>Go to <a href="http://ez.no/developer/download">http://ez.no/developer/download</a></li>
	<li>Find the Windows installer matching your Windows version.</li>
	<li>Download the installer to your hard drive.</li>
	<li>Double click on the downloaded file and follow the instructions. Choose demo data if you want to see an example of how to use eZ publish.</li>
	<li>Now the installation will start. This can take a few minutes.</li>
	<li>When the installation is done, you need to start eZ publish by clicking on the eZ publish icon
	on the desktop. This will open eZ publish in your default browser. eZ publish will open at the
    User site.
	To start the Admin site simply write /admin at the end of the URL. Log in with <i>admin</i> as
    user name, and <i>publish</i> as password.</li>
	<li>You are now ready to start using eZ publish.</li>
</ol>

<p class="important">
    <b>Note:</b> If you have anything listening on the same port as Apache (80) or MySQL (3306) you
    need to shut those services down. Apache and MySQL won't start if port 80 or 3306 are in use.
</p>

<h3 id="Linux">Linux installer</h3>

<ol>
	<li>Open your web browser and go to <a href="http://ez.no/download">http://ez.no/download</a></li>
	<li>Find the Linux installer matching your distribution. These installers are tested on Redhat Linux, but will probably work fine on several other distributions as well.</li>
	<li>Become root by running <pre class="example"> $ su - </pre>
        <p class="important"> <b>Note:</b> it is important that you run "su -", and not "su". The installer needs to have system variables for the root user.</p></pre></li>
	<li>Unpack the downloaded file <pre class="example"> # tar xvfz ezpublish-3.0-xxx-x-Linux-xxx.i386.tar.gz</pre> Replace ezpublish-3.0-xxx-x-Linux-xxx.i386.tar.gz with your current version.</li>
	<li>Enter the extracted directory <pre class="example"> # cd ezpublish-3.0-xxx-x-Linux-xxx.i386 </pre></li>
	<li>Run the installation program <pre class="example"> # ./install.sh</pre></li>
	<li>Choose "install eZ publish" and press Enter</li>
	<li>If you only have one network card press enter on the question on "What is your internal network card". If you have more than one card enter the name of the network device connected to your network. </li>
	<li>Enter port number for the user demo site, press enter for default port (1337)</li>
	<li>Enter port number for the admin site, press enter for default port (1338). Check for installation of demo data if you want this. If not you will get a clean setup. Clean setup is useful when starting to develop a new site. The demo data is useful for testing the system.</li>
	<li>You will get a report of what's installed.</li>
	<li>"OK" to quit</li>
</ol>

<p>
	Installation of eZ publish 3 is now done. You can access the demo site at:
	http://localhost:1337 and the default admin site at http://localhost:1338
</p>

<h3 id="FreeBSD">FreeBSD installer</h3>

<ol>
	<li>Open your web browser and go to <a href="http://ez.no/download">http://ez.no/download</a></li>
	<li>Find the FreeBSD installer. This installer is tested on FreeBSD 4.6.2, but should work on other versions of FreeBSD as well. </li>
	<li>Become root by running: <pre class="example"> $ su - </pre> <p class="important"> <b>Note:</b> it is important that you run "su -", and not "su". The installer needs to have system variables for the root user.</p></li>
	<li>Unpack the downloaded file <pre class="example"> # tar xvfz ezpublish-3.0-xxx-x-FreeBSD-STABLE.i386.tar.gz</pre> Replace ezpublish-3.0-xxx-x-FreeBSD-STABLE.i386.tar.gz with your current version.</li>
	<li>Enter the extracted directory <pre class="example"> # cd ezpublish-3.0-xxx-x-FreeBSD-STABLE.i386</pre></li>
	<li>Run the installation program: <pre class="example"> # ./install.sh</pre><p class="important"><b>Note:</b> The FreeBSD Installer requires bash to be located under /usr/local/bin. If you do not have bash installed please refer to the <a href="http://www.freebsd.org/doc/en_US.ISO8859-1/books/handbook/index.html">FreeBSD Handbook</a> for more help on installing bash.</li>
	<li>Choose "install eZ publish 3" and press Enter</li>
	<li>Enter the device name of your network card. You can find the name of your network card by running "ldconfig". </li>
	<li>Enter port number for the user demo site, press enter for default port (1337).</li>
	<li>Enter port number for the admin site, press enter for default port (1338). Check for installation of demo data if you want this. If not you will get a clean setup. Clean setup is useful when starting to develop a new site. The demo data is useful for testing the system.</li>
	<li>You will get a report of what's installed.</li>
	<li>"OK" to quit</li>
</ol>

<p>
	Installation of eZ publish 3 is now done. You can access the demo site at:
	http://localhost:1337 and the default admin site at http://localhost:1338
</p>

<h2 id="Security">Security</h2>
<p>It's important that steps are taken to secure the site, more information on this can be read at <a href={"/manual/security/securing"|ezurl}>Securing the site</a>
