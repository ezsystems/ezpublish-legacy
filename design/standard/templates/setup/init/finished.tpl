{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup}

<h2>Congratulations!</h2>
<h2>eZ publish should now run on your system.</h2>
<p>
If you need help with eZ publish, you can go to the <a href="http://developer.ez.no">eZ publish website</a>.
If you find a bug (error), please go to <a href="http://developer.ez.no/developer/bugreports/">eZ publish bug reports</a> and report it.
Only with your help can we fix the errors eZ publish might have and implement new features.
</p>
<p>
If you ever want to restart this setup, edit the file <i>settings/site.ini.php</i> and look for a line that say.
</p>
<pre class="example">[SiteAccessSettings]
CheckValidity=true</pre>
<p>
 Change the second line from <i>true</i> to <i>false</i>.
</p>
<pre class="example">[SiteAccessSettings]
CheckValidity=false</pre>
</p>
<p>
You can find your new eZ publish website <a href="{$site_info.url}">here</a>. Enjoy one of the most successful web content management systems!
</p>
