{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup}

  <form method="post" action="{$script}">

{section show=$test.result|eq(1)}
  <p>{"No problems was found with your system, you can continue by clicking the"|i18n("design/standard/setup/init")} <i>{"Next"|i18n("design/standard/setup/init")}</i> {"button."|i18n("design/standard/setup/init")}</p>
  <p>{"However if you wish to finetune your system you should click the"|i18n("design/standard/setup/init")} <i>{"Finetune System"|i18n("design/standard/setup/init")}</i> {"button."|i18n("design/standard/setup/init")}</p>

  <form method="post" action="{$script}">
{*                {section name=handover loop=$handover}
                <input type="hidden" name="{$:item.name}" value="{$:item.value}" />
                {/section} *}
{*                <input type="hidden" name="currentStep" value="{$nextStep}" />*}
    <div class="buttonblock">
      <input type="hidden" name="ChangeStepAction" value="" />
      <input class="defaultbutton" type="submit" name="StepButton_4" value="{'Next'|i18n('design/standard/setup/init')} >>" />
      <input class="button" type="submit" name="StepButton_3" value="{'Finetune System'|i18n('design/standard/setup/init')} >" />
    </div>
    {include uri='design:setup/persistence.tpl'}
  </form>

{section-else}

  <p>
  {"The system check found some issues that needs to be resolve before the setup can continue."|i18n("design/standard/setup/init")}
  {"Please have a look through the results below for more information on what the problems are."|i18n("design/standard/setup/init")}
  {"Each problem will give you instructions on how to fix the problem."|i18n("design/standard/setup/init")}
  </p>
  <p>{"After you have fixed the problems click the"|i18n("design/standard/setup/init")} <i>{"Check Again"|i18n("design/standard/setup/init")}</i> {"button to re-run the system checking."|i18n("design/standard/setup/init")}</p>

  <h1>{"Issues"|i18n("design/standard/setup/init")}</h1>
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
  {section name=Result loop=$test.results}
  {section-exclude match=$:item[0]|ne(2)}
  <tr>
    <td>{include uri=concat('design:setup/tests/',$:item[1],'_error.tpl') test_result=$:item result_number=$:number}</td>
  </tr>

  {delimiter}
  <tr><td>&nbsp;</td></tr>
  {/delimiter}

  {/section}
  </table>

    <div class="buttonblock">
      <input type="hidden" name="ChangeStepAction" value="" />
      <input class="defaultbutton" type="submit" name="StepButton_2" value="{'Check Again'|i18n('design/standard/setup/init')}" />
    </div>
    {include uri='design:setup/persistence.tpl'}
  </form>

{/section}
