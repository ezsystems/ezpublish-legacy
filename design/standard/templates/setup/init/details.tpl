{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup}

{section show=$test.result|eq(1)}
  <p>No finetuning is required on your system, you can continue by clicking the <i>Next</i> button.</p>

  <form method="post" action="{$script}">
    <div class="buttonblock">
      <input type="hidden" name="ChangeStepAction" value="" />
      <input class="button" type="submit" name="StepButton_4" value="Next" />
    </div>
    {include uri='design:setup/persistence.tpl'}
  </form>

{section-else}

  <p>
  The system check found some issues that, when resolve, may give improved performance or more features.
  Please have a look through the results below for more information on what might be done.
  Each issue will give you instructions on how to do the finetuning.
  </p>
  <p>
   After you have fixed the problems click the <i>System Check</i> button to re-run the system checking which is recommended after system changes
   to check for critical failures,
   you can also click the <i>Check Again</i> button to rerun the finetuning checks.
   However if you wish you can skip straight to the next step by clicking the <i>Next</i> button.
  </p>

  <h1>Issues</h1>

  <form method="post" action="{$script}">

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
      <input class="button" type="submit" name="StepButton_3" value="Check Again" />
      <input class="button" type="submit" name="StepButton_2" value="System Check" />
      <input class="button" type="submit" name="StepButton_4" value="Next" />
    </div>
    {include uri='design:setup/persistence.tpl'}
  </form>

{/section}
  
