{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup}

<form method="post" action="{$script}">

<p>
 If you wish you can register your installation by sending some information to eZ systems.
 No confidential data will be transmitted and eZ systems will not use or sell your personal details for unsolicited emails.
 This data will help to improve eZ publish for future releases.
</p>

<div class="highlight">
<p>The following data will be sent to eZ systems:</p>
<ul>
  <li>Details of your system, like OS type etc.</li>
  <li>The test results for your system</li>
  <li>The database type you are using</li>
  <li>The name of your site</li>
  <li>The url of your site</li>
  <li>The languages you chose</li>
</ul>
</div>

<p>
 If you wish you can also add some comments which will be included in the registration.
</p>

<div class="highlight">
<table cellpadding="0" cellspacing="0" border="0">
<tr><th class="normal">Comments</th></tr>
<tr><td class="normal"><textarea class="box" name="eZSetupRegistrationComment" cols="60" rows="6"></textarea></td></tr>
</table>
</div>


  <div class="buttonblock">
    <input type="hidden" name="ChangeStepAction" value="" />
    <input class="button" type="submit" name="StepButton_12" value="Send Registration" />
    <input class="button" type="submit" name="StepButton_13" value="Skip Registration" />
  </div>
  {include uri='design:setup/persistence.tpl'}
</form>
