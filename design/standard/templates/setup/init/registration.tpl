{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup}

<form method="post" action="{$script}">

<p>
 {"If you wish you can register your installation by sending some information to eZ systems. No confidential data will be transmitted and eZ systems will not use or sell your personal details for unsolicited emails. This data will help to improve eZ publish for future releases."|i18n("design/standard/setup/init")}
</p>

<div class="highlight">
<p>{"The following data will be sent to eZ systems:"|i18n("design/standard/setup/init")}</p>
<ul>
  <li>{"Details of your system, like OS type etc."|i18n("design/standard/setup/init")}</li>
  <li>{"The test results for your system"|i18n("design/standard/setup/init")}</li>
  <li>{"The database type you are using"|i18n("design/standard/setup/init")}</li>
  <li>{"The name of your site"|i18n("design/standard/setup/init")}</li>
  <li>{"The url of your site"|i18n("design/standard/setup/init")}</li>
  <li>{"The languages you chose"|i18n("design/standard/setup/init")}</li>
</ul>
</div>

<p>
 {"If you wish you can also add some comments which will be included in the registration."|i18n("design/standard/setup/init")}
</p>

<div class="highlight">
<table cellpadding="0" cellspacing="0" border="0">
<tr><th class="normal">{"Comments"|i18n("design/standard/setup/init")}</th></tr>
<tr><td class="normal"><textarea class="box" name="eZSetupRegistrationComment" cols="60" rows="6"></textarea></td></tr>
</table>
</div>


  <div class="buttonblock">
    <input type="hidden" name="ChangeStepAction" value="" />
    <input class="defaultbutton" type="submit" name="StepButton_12" value="{'Send Registration'|i18n('design/standard/setup/init')} >" />
    <input class="button" type="submit" name="StepButton_13" value="{'Skip Registration'|i18n('design/standard/setup/init')} >>" />
  </div>
  {include uri='design:setup/persistence.tpl'}
</form>
