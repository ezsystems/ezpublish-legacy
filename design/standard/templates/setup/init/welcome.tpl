{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup step_header=false()}

<p>
 {"Welcome to the setup program for"|i18n("design/standard/setup/init")} eZ publish {$#version.text}.
 {"This part of the setup system will guide you trough the necessary steps to make sure"|i18n("design/standard/setup/init")} eZ publish {"is properly initialized"|i18n("design/standard/setup/init")}.
</p>
<p>
 {"Click the button below to proceed to the next step which will start the system check."|i18n("design/standard/setup/init")}
 {"However if you wish to setup the site manually press the"|i18n("design/standard/setup/init")} <i>{"Disable Setup"|i18n("design/standard/setup/init")}</i> {"button."|i18n("design/standard/setup/init")}
</p>

<form method="post" action="{$script}">
  <div class="buttonblock">
    <input type="hidden" name="ChangeStepAction" value="" />
    <input class="defaultbutton" type="submit" name="StepButton_2" value="{'System Check'|i18n('design/standard/setup/init')} >>" />
    <input type="hidden" name="DisableSetup" value="" />
    <input class="button" type="submit" name="StepButton_1" value="{'Disable Setup'|i18n('design/standard/setup/init')}" />
  </div>
</form>
