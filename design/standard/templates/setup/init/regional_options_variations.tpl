{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup}

<form method="post" action="{$script}">

{section show=eq($regional_info.language_type,1)}
 <p>
  It's now possible to select a variation for your language. A variation does small adjustments to the language,
  such as adding Euro support or date format changes. Using variations are optional so you may safely skip this step.
  Once your're done click the <i>Summary</i> button.
 </p>
{section-else}
 <p>
  It's now possible to select variations for your languages. Variations do small adjustments to the language,
  such as adding Euro support or date format changes. Using variations are optional so you may safely skip this step.
  Once you are done click the <i>Summary</i> button.
 </p>
{/section}

<div class="highlight">
<table cellspacing="0" cellpadding="0" border="0">
{section name=Language loop=$chosen_languages}
<tr>
  <td colspan="2">
    <b>{$:item.language_name}</b>
  </td>
</tr>
<tr>
  <td>
    Default
  </td>
{let name=Variation has_variation=false()}
{section loop=$language_variatons[$Language:item.locale_code]}
{switch match=$:item.locale_full_code}
  {case in=$regional_info.variations}
    {set has_variation=true()}
  {/case}
  {case/}
{/switch}
{/section}
  <td>
    <input type="radio" name="eZSetupLanguageVariation[{$Language:item.locale_code}]" value="{$Language:item.locale_full_code}" {section show=$:has_variation|not}checked="checked"{/section} />
  </td>
{/let}
</tr>
{section name=Variation loop=$language_variatons[$:item.locale_code]}
<tr>
  <td>
    {$:item.language_comment}
  </td>
  <td>
    <input type="radio" name="eZSetupLanguageVariation[{$:item.locale_code}]" value="{$:item.locale_full_code}" {switch match=$:item.locale_full_code}{case in=$regional_info.variations}checked="checked"{/case}{case/}{/switch} />
  </td>
</tr>
{/section}
{/section}
</table>
</div>

  <div class="buttonblock">
    <input type="hidden" name="ChangeStepAction" value="" />
    <input class="button" type="submit" name="StepButton_7" value="Summary" />
  </div>
  {include uri='design:setup/persistence.tpl'}
</form>
