{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup}

{section show=and($email_info.sent,$email_info.result|not)}
<div class="error">
<p>
  <h2>{"Email sending failed"|i18n("design/standard/setup/init")}</h2>
  <ul>
    <li>{"Failed sending registration email using"|i18n("design/standard/setup/init")} {section show=eq($email_info.type,1)}{"sendmail"|i18n("design/standard/setup/init")}{section-else}{"SMTP"|i18n("design/standard/setup/init")}{/section}.</li>
  </ul>
</p>
</div>
{/section}

<h2>{"Congratulations, eZ publish should now run on your system."|i18n("design/standard/setup/init")}</h2>
<p>
{"If you need help with eZ publish, you can go to the"|i18n("design/standard/setup/init")} <a target="_other" href="http://developer.ez.no">{"eZ publish website"|i18n("design/standard/setup/init")}</a>.
{"If you find a bug (error), please go to"|i18n("design/standard/setup/init")} <a target="_other" href="http://developer.ez.no/developer/bugreports/">{"eZ publish bug reports"|i18n("design/standard/setup/init")}</a> {"and report it."|i18n("design/standard/setup/init")}
{"With your help we can fix the errors eZ publish might have and implement new features."|i18n("design/standard/setup/init")}
</p>
<p>
{"If you ever want to restart this setup, edit the file"|i18n("design/standard/setup/init")} <i>settings/site.ini.php</i> {"and look for a line that says:"|i18n("design/standard/setup/init")}
</p>
<pre class="example">[SiteAccessSettings]
CheckValidity=false</pre>
<p>
 {"Change the second line from"|i18n("design/standard/setup/init")} <i>false</i> {"to"|i18n("design/standard/setup/init")} <i>true</i>.
</p>
<pre class="example">[SiteAccessSettings]
CheckValidity=true</pre>
</p>
<p>
{"Click on the URL to access your new"|i18n("design/standard/setup/init")} <a href="{$site_info.url}">{"eZ publish website"|i18n("design/standard/setup/init")}</a> {"or click the"|i18n("design/standard/setup/init")} <i>{"Done"|i18n("design/standard/setup/init")}</i> {"button. Enjoy one of the most successful web content management systems!"|i18n("design/standard/setup/init")}
</p>

<form method="post" action="{$script}">
  <div class="buttonblock">
    <input class="defaultbutton" type="submit" name="Refresh" value="{'Done'|i18n('design/standard/setup/init')}" />
  </div>
</form>
