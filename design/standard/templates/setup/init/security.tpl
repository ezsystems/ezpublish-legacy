{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup}

<form method="post" action="{$script}">

<div align="center">
  <h1>{"Securing site"|i18n("design/standard/setup/init")}</h1>
</div>

<p>
{"Your site is running in non-virtualhost mode which is considered an unsecure mode. It's recommended to run eZ publish in virtualhost mode.
If you do not have the possibility to use virtualhost mode you should follow the instructions below on howto install a .htaccess file, the file tells the webserver to only give access to certain files."|i18n("design/standard/setup/init")}
</p>

<p>
  {"If you have shell access to the site you can run the following commmand to install the file."|i18n("design/standard/setup/init")}
</p>
<pre class="example">cd {$path}
cp .htaccess_root .htaccess</pre>
<p>
  {"If you do not have shell access you will have to copy the file using the ftp client or ask your hosting provider to do this for you."|i18n("design/standard/setup/init")}
</p>

  {include uri='design:setup/init/navigation.tpl'}
  {include uri="design:setup/persistence.tpl"}

</form>
