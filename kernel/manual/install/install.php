<h1>Install eZ publish 3</h1>

<ul>
	<li><a href="#Requirements">Requirements</a></li>
	<li><a href="#Installers">Install eZ publish 3 with installers</a></li>
	<ul>
		<li><a href="#Windows">Windows installer</a></li>
		<li><a href="#Linux">Linux installer</a></li>
		<li><a href="#FreeBSD">FreeBSD installer</a></li>
	</ul>
</ul>
<br></br>

<p>
	You have basically four ways of installing eZ publish 3. You can use one of the
	supplied installer packages, which will install Apache, PHP, MySQL, ImageMagick and set up
	everything needed to get eZ publish up and running. Another option is to use the
	publish sources and follow the setup wizard which will guide you through the
	steps of configuring your system for eZ publish.

	If you are little more experienced you can disable the setup wizard and
	configure eZ publish 3 from scratch following the installation manual. If you
	don't want to install eZ publish 3 yourself you can always hire eZ systems to
	install the software for you. You can also buy a ready hosted eZ publish 3 solution.
</p>
<br></br>

<h2 id="Requirements"> eZ publish 3 requirements</h2>
<p>
	You need to have apache and php installed to run eZ publish 3.
	PHP needs to have compiled in support for either MySQL or PostgreSQL.
	For image conversion support you need to have GD compiled in PHP or
	ImageMagick installed on your system.
</p>

<ul>
	<li>PHP >= 4.1.x <br><a href="http://www.php.net">http://www.php.net </a> </br> </li>
	<li>Apache 1.3 (apache 2.0 might/might not work) <br><a href="http://www.apache.org">http://www.apache.org</a></br> </li>
	<li>Either MySQL or PostgreSQL <br><a href="http://www.mysql.org">http://www.mysql.org</a></br> <br><a href="http://www.postgresql.org">http://www.postgresql.org</a></br> </li>
	<li> ImageMagick and/or GD compiled in PHP for image conversion (not required) <br><a href="http://www.imagemagick.org">http://www.imagemagick.org</a></br> </li>
</ul>

<p>
	The installation process for the above programs are very well documented on
	their homepage, so we will not include that here.
</p>
<br></br>

<h2 id="Installers">Install eZ publish 3 with installers</h2>
<p>
	With the installers you get the basic requirements you need to run eZ publish 3 on
	your platform. You will get Apache, PHP, MySQL and ImageMagick. If you don't want these
	requirements we recommend that you do not use the installers. Then you should
	install eZ publish 3 without the installers.
</p>

<h3 id="Windows">Windows installer</h3>

<ol>
	<li>Go to ez.no/download</li>
	<li>Find the Windows installer</li>
	<li>Download the installer to your hard disc</li>
	<li>Click the Setup.exe file and follow the instructions ? click ?next?</li>
	<li>Save eZ publish 3 on your computer</li>
	<li>Default will be C:\ eZ systems</li>
	<li>Keep the default or change to where you want to save it</li>
	<li>Click ?next?</li>
	<li>Now the installation will start. This can take a few minutes*</li>
	<li>When the installation is done eZ publish will start in a new window in your
	standard browser. eZ publish will open at the Usersite. To start the Adminsite
	simply write /admin after the rest in the address. Log in with ?admin? and
	?publish?.</li>
	<li>You are ready to start using eZ publish.</li>
</ol>

<p>
	*Choose demo data in the installation if you want to use that as an example on how to use eZ publish.
</p>

<h3 id="Linux">Linux installer</h3>

<ol>
	<li>Open your web browser and go to <a href="http://ez.no/download">http://ez.no/download</a></li>
	<li>Find the Linux installer matching your distribution. These installers are tested on Linux Redhat, but might as well work fine on other distributions.</li>
	<li>Become root by running <pre class="example"> $ su - </pre> <p class="important"> <b>Note:</b> it is important that you run "su -", and not "su". The installer needs to have system variables for root</p></pre></li>
	<li>Unpack the downloaded file <pre class="example"> # tar xvfz ezpublish-3.0-xxx-x-Linux-xxx.i386.tar.gz</pre> Replace ezpublish-3.0-xxx-x-Linux-xxx.i386.tar.gz with your current version.</li>
	<li>Enter the extracted directory <pre class="example"> # cd ezpublish-3.0-xxx-x-Linux-xxx.i386 </pre></li>
	<li>Run the installation program <pre class="example"> # ./install.sh</pre></li>
	<li>Choose "install eZ publish" and press Enter</li>
	<li>If you only have one network card press enter on the question on "What is your internal network card". If you have more than one card enter the name of the network device connected to your network. </li>
	<li>Enter port number for the user demo site, press enter for default port (1337)</li>
	<li>Enter port number for the admin site, press enter for default port (1338). Check of [x] for installation of demo data. If not you will get a clean setup. Clean setup is useful when starting to develop a new site. The demo data is useful for testing out the system.</li>
	<li>You will get a report of what's installed.</li>
	<li>"OK" to quit</li>
</ol>

<p>
	Installation of eZ publish 3 is now done. You can access the demo site at:
	http://localhost:1337 and the default admin site at http://localhost:1338
</p>

<h3 id ="FreeBSD">FreeBSD installer</h3>

<ol>
	<li>Open your web browser and go to <a href="http://ez.no/download">http://ez.no/download</a></li>
	<li>Find the FreeBSD installer. This installer is tested on FreeBSD 4.6.2, but should work on other versions of FreeBSD as well. </li>
	<li>Become root by running: <pre class="example"> $ su - </pre> <p class="important"> <b>Note:</b> it is important that you run "su -", and not "su". The installer needs to have system variables for root.</p></li>
	<li>Unpack the downloaded file <pre class="example"> # tar xvfz ezpublish-3.0-xxx-x-FreeBSD-STABLE.i386.tar.gz</pre> Replace ezpublish-3.0-xxx-x-FreeBSD-STABLE.i386.tar.gz with your current version.</li>
	<li>Enter the extracted directory <pre class="example"> # cd ezpublish-3.0-xxx-x-FreeBSD-STABLE.i386</pre></li>
	<li>Run the installation program: <pre class="example"> # ./install.sh</pre></li>
	<li>Choose "install eZ publish 3" and press Enter</li>
	<li>Enter the device name of your network card. You can find the name of your network card by running "ldconfig". </li>
	<li>Enter port number for the user demo site, press enter for default port (1337).</li>
	<li>Enter port number for the admin site, press enter for default port (1338). Check of [x] for installation of demo data. If not you will get a clean setup. Clean setup is useful when starting to develop a new site. The demo data is useful for testing out the system.</li>
	<li>You will get a report of what's installed.</li>
	<li>"OK" to quit</li>
</ol>

<p>
	Installation of eZ publish 3is now done. You can access the demo site at:
	http://localhost:1337 and the default admin site at http://localhost:1338
</p>