{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup}

<form method="post" action="{$script}">

{section show=$security.virtualhost_mode}
<p>
 {"Your site is running in virtualhost mode and is considered secure. You may safely continue."|i18n("design/standard/setup/init")}
</p>
<input type="hidden" name="security_InstallHtaccess" value="0" />
{section-else}
<p>
{"Your site is running in non-virtualhost mode which is considered an unsecure mode. It's recommended to run eZ publish in virtualhost mode.
If you do not have the possiblity to use virtualhost mode you should follow the instructions below on howto install a .htaccess file, the file tells the webserver to only give access to certain files."|i18n("design/standard/setup/init")}
</p>

<p>If you have shell access to the site you can run the following commmand to install the file.</p>
<pre class="example">cd {$path}
cp .htaccess_root .htaccess</pre>
<p>If you do not have shell access you will have to copy the file using the ftp client or ask your hosting provider to do this for you.</p>

{*<div class="input_highlight">
<label>Install .htaccess</label>
<input type="checkbox" name="eZSetupInstallHTAccess" value="1" checked="checked" />
</div>*}

<input type="hidden" name="security_InstallHtaccess" value="0" />
{/section}

  <div class="buttonblock">
    <input type="hidden" name="ChangeStepAction" value="" />
    <input class="defaultbutton" type="submit" name="StepButton_12" value="{'Register Site'|i18n('design/standard/setup/init')} >>" />
  </div>
  {include uri='design:setup/persistence.tpl'}
</form>
