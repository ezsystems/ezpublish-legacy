{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup}

<form method="post" action="{$script}">

<p>
 If you wish you can register your installation by sending some information to eZ systems.
 No confidential data will be transmitted and eZ systems will not use or sell your personal details for unsolicited emails.
 This data will help to improve eZ publish for future releases.
</p>

<div class="highlight">
<p>The following data will be send to eZ systems:</p>
<ul>
  <li>The test results for your system</li>
  <li>The database type you are using</li>
  <li>The name of your site</li>
  <li>The url of your site</li>
  <li>The languages you chose</li>
</ul>
</div>

  <div class="buttonblock">
    <input type="hidden" name="ChangeStepAction" value="" />
    <input class="button" type="submit" name="StepButton_12" value="Send Registration" />
    <input class="button" type="submit" name="StepButton_13" value="Skip Registration" />
  </div>
  {include uri='design:setup/persistence.tpl'}
</form>
