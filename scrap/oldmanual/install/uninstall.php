<h1>Uninstall eZ publish 3</h1>

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
		<li><a href="#Macos_without_installer">Without installer</a></li>
	</ul>
</ul>

<h2 id="Windows">Windows</h2>
<h3 id="Windows_installer">Installer</h3>
<p>
	Click Start - Settings - Control panel. Open <b>Add/Remove programs</b> and find eZ
	publish on the list. Highlight <b>eZ publish</b> and click the <b>Change/Remove</b>
	button. This will delete eZ publish 3.
</p>

<p>
	In addition you might want to remove the eZ systems directory because files created after the installation of eZ publish (e.g. Apache log files) are not deleted by the usual uninstall.
</p>

<h3 id="Windows_without_installer">Without Installer</h3>
<p>
	If you did not use the installer, simply remove your eZ publish directory.
</p>

<h2 id="Linux_FreeBSD">Linux and FreeBSD</h2>
<h3 id="Linux_FreeBSD_installer">Installer</h3>
<ol>
	<li>To uninstall eZ publish you must be root. Become root by typing <pre class="example"> # su -</pre> and enter your root password when prompted for. </li>
	<li>Go to your extracted ezpublish-xxx-xxx-i386 directory and run <pre class="example"> # ./install.sh</pre></li>
	<li>The installer automatically searches for an existing eZ publish 3 installation and highlights choice number two on the menu. Make sure choice number 2 "Uninstall eZ publish" is selected and press Enter.</li>
</ol>
<h3 id="Linux_FreeBSD_without_installer">Without installer</h3>
<ol>
	<li>To uninstall eZ publish you must be root. Become root by typing <pre class="example"> # su - </pre>and enter your root password when prompted for. </li>
	<li>Now to remove all the eZ publish files just run <pre class="example"> # rm -fr &lt;httproot&gt;/ezpublish-xxx</pre></li>
</ol>

<p>
	If you installed eZ publish using the installer and you deleted the extracted
	installer directory afterwards, don't panic. It's easy to manual uninstall eZ
	publish.
</p>

<ol>
	<li>To uninstall eZ publish you must be root. Become root by typing <pre class="example"> # su - </pre>and enter your root password when prompted for. </li>
	<li>Before we can remove any files, we need to stop both Apache and MySQL. First go to the bin directory located in /opt/ezpublish
    <pre class="example"># cd /opt/ezpublish/bin</pre>
    To stop Apache and MySQL run
    <pre class="example"> # ./ezpublish stop</pre></li>
	<li>Both Apache and MySQL should now be stopped. To remove all the eZ publish files run
    <pre class="example"> # rm -fr /opt/ezpublish</pre>
    and remove the ezpublish lock file
    <pre class="example"># rm -f /var/state/ezpublish/ezpublish.lock</pre></li>
</ol>
<p>
	All traces of eZ publish should now be gone.
</p>

<h2 id="Macos">Mac OS X</h2>
<h3 id="Macos_without_installer">Without installer</h3>
<ol>
	<li>To uninstall eZ publish you must be root. Become root by typing
    <pre class="example"> # su - </pre>
    and enter your root password when prompted for it.</li>
	<li>Now to remove all the eZ publish files just run
    <pre class="example"> # rm -fr &lt;httproot&gt;/ezpublish-xxx</pre></li>
</ol>
<p>
	All traces of eZ publish should now be gone.
</p>
