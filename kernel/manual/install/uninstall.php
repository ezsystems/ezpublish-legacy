<h1>Uninstalling eZ publish</h1>

<ul>
	<li><a href="#Windows">Uninstalling on Windows</a></li>
	<ul>
		<li><a href="#Windows_installer">Installer</a></li>
		<li><a href="#Windows_without_installer">Without installer</a></li>
	</ul>
	<li><a href="#Linux_FreeBSD">Uninstalling on Linux and FreeBSD</a></li>
	<ul>
		<li><a href="#Linux_FreeBSD_installer">Installer</a></li>
		<li><a href="#Linux_FreeBSD_without_installer">Without installer</a></li>
	</ul>
	<li><a href="#Macos">Uninstalling on Mac OS X</a></li>
	<ul>
		<li><a href="#Macos_installer">Installer</a></li>
		<li><a href="#Macos_without_installer">Without installer</a></li>
	</ul>
</ul>

<h2 id="Windows">Windows</h2>
<h3 id="Windows_installer">Installer</h3>
<p>
Uninstalling eZ publish should be very easy. In windows you simply
Click Start ? Settings ? Control panel. Open ?Add/Remove programs? and find eZ
publish on the list. Highlight ?eZ publish? and click the ?Change/Remove?
button. This will delete eZ publish 3.
</p>
In addition you can delete the eZ systems files. You will normally find those
here: C:\Program Files\eZ systems or where you installed eZ publish the first time.

<h3 id="Windows_without_installer">Without Installer</h3>
<p>
If you did not use the installer, simply remove the ezpublish directory.
</p>

<h2 id="Linux_FreeBSD">Linux and FreeBSD</h2>
<p>
<h3 id="Linux_FreeBSD_installer">Installer</h3>
<ol>
<li>To uninstall eZ publish you must be root. Become root by typing <pre class="example"> # su -</pre> and enter your root password when prompted for. </li>
<li>Go to your extracted ezpublish-xxx-xxx-i386 directory and run <pre class="example"> # ./install.sh</pre></li>
<li>The installer automatically look for an existing eZ publish 3 installation and highlight choice number two on the menu. Make sure choice number 2 "Uninstall eZ
publish" is selected and press Enter. </li>
</ol>
<h3 id="Linux_FreeBSD_without_installer">Without installer</h3>
<p>
If you installed eZ publish using the installer and you deleted the extracted
installer directory afterwards, don't panic. It's easy to manual uninstall eZ
publish.
</p>

<ol>
<li>To uninstall eZ publish you must be root. Become root by typing <pre class="example"> # su - </pre>and enter your root password when prompted for. </li>
<li>Before we can remove any files, we need to stop both Apache and MySQL. First go to the bin directory located in /opt/ezpublish <pre class="example"># cd /opt/ezpublish/bin</pre> now to stop Apache and MySQL run <pre class="example"> # ./ezpublish stop</pre></li>
<li> Both Apache and MySQL should now be stopped. To remove all the eZ publish files just run <pre class="example"> # rm -fr /opt/ezpublish</pre> and remove the ezpublish lock file <pre class="example"># rm -f /var/state/ezpublish/ezpublish.lock</pre></li>
</ol>
<p>
All traces of eZ publish should now be gone.
</p>

<h2 id="Macos">Mac OS X</h2>
<p>
Not finished
</p>
<h3 id="Macos_installer">Installer</h3>
<h3 id="Macos_without_installer">Without installer</h3>


