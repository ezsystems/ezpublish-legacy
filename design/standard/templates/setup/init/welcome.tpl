{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup step_header=false()}

<p>
 Welcome to the setup program for eZ publish {$#version.text}.
 This part of the setup system will guide you trough the necessary steps to make sure eZ publish is properly initialized.
</p>
<p>
 Click the button below to proceed to the next step which will start the system check.
 However if you wish to setup the site manually press the <i>Disable Setup</i> button.
</p>

<form method="post" action="{$script}">
  <div class="buttonblock">
    <input type="hidden" name="ChangeStepAction" value="" />
    <input class="button" type="submit" name="StepButton_2" value="System Check" />
    <input type="hidden" name="DisableSetup" value="" />
    <input class="button" type="submit" name="StepButton_1" value="Disable Setup" />
  </div>
</form>
