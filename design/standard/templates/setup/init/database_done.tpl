{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup}

<form method="post" action="{$script}">

<p>
 The database was succesfully initialized, you are now ready for some post configuration of the site.
 Click the <i>Configure</i> to start the configuration process.
</p>

  <div class="buttonblock">
    <input type="hidden" name="ChangeStepAction" value="" />
    <input class="button" type="submit" name="StepButton_9" value="Configure" />
  </div>
  {include uri='design:setup/persistence.tpl'}
</form>
