{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup}

<form method="post" action="{$script}">

{let has_variations=false()}

{section loop=$available_languages}
{section-exclude match=$:item.country_variation}
{set has_variations=true()}
{/section}

{section show=eq($regional_info.language_type,1)}
 <p>
  It's time to select the language this site should support.
  {section show=$has_variations}
   Select your language and click the <i>Summary</i> button, or the <i>Language Details</i> button to select language variations.
  {section-else}
   Select your language and click the <i>Summary</i> button.
  {/section}
 </p>
{section-else}
 <p>
  It's time to select the languages this site should support.
  Select your primary language and check any additional languages.
  {section show=$has_variations}
   Once you're done click the <i>Summary</i> button, or the <i>Language Details</i> button to select language variations.
  {section-else}
   Once you're done click the <i>Summary</i> button.
  {/section}
 </p>
 {section show=eq($regional_info.language_type,3)}
  <p>The languages you choose will help determine the charset to use on the site.</p>
 {/section}
{/section}

<div class="highlight">
<table cellspacing="0" cellpadding="0" border="0">
{section name=Language loop=$available_languages}
{section-exclude match=$:item.country_variation}
<tr>
  <td>
    {$:item.language_name}
  </td>
  <td>
    <input type="checkbox" name="eZSetupLanguages[]" value="{$:item.locale_full_code}" {switch match=$:item.locale_full_code}{case in=$regional_info.languages}checked="checked"{/case}{case/}{/switch} />
  </td>
  <td>
    <input type="radio" name="eZSetupPrimaryLanguage" value="{$:item.locale_full_code}" {section show=eq($regional_info.primary_language,$:item.locale_full_code)}checked="checked"{/section} />
  </td>
</tr>
{/section}
</table>
</div>

  <div class="buttonblock">
    <input type="hidden" name="ChangeStepAction" value="" />
{* {section show=ne($regional_info.language_type,1)} *}
{section show=$has_variations}
    <input type="hidden" name="eZSetupChooseVariations" value="" />
    <input class="button" type="submit" name="StepButton_6" value="Language Details" />
{/section}
    <input class="button" type="submit" name="StepButton_7" value="Summary" />
{* {/section} *}
  </div>
  {include uri='design:setup/persistence.tpl'}
</form>
{/let}
