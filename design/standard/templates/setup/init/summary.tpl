{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup}

<form method="post" action="{$script}">

<p>
 Here you will see a summary of the basic settings for your site. If you are satisfied with the settings
 you can click the <i>Setup Database</i> button.
</p>
<p>However if you want to change your settings click the <i>Start Over</i> button which will restart the collecting of information (Existing settings are kept).</p>

<div class="highlight">
<table cellspacing="0" cellpadding="0">
<tr>
  <td colspan="3">
    <b>Database settings</b>
  </td>
</tr>
<tr>
  <td>
    Database
  </td>
  <td rowspan="4">&nbsp;&nbsp;</td>
  <td>
    {$database_info.info.name}
  </td>
</tr>
<tr>
  <td>
    Server
  </td>
  <td>
    {$database_info.server}
  </td>
</tr>
<tr>
  <td>
    Name
  </td>
  <td>
    {$database_info.name}
  </td>
</tr>
<tr>
  <td>
    Username
  </td>
  <td>
    {$database_info.user}
  </td>
</tr>

<tr>
  <td colspan="3">
    &nbsp;
  </td>
</tr>

<tr>
  <td colspan="3">
    <b>Language settings</b>
  </td>
</tr>
<tr>
  <td>
    Language type
  </td>
  <td rowspan="2">&nbsp;&nbsp;</td>
  <td>
  {switch match=$regional_info.language_type}
  {case match=1}
    Monolingual
  {/case}
  {case match=2}
    Multilingual
  {/case}
  {case match=3}
    Multilingual
  {/case}
  {/switch}
  </td>
</tr>
<tr>
  <td>
    Languages
  </td>
  <td>
    {section name=Language loop=$variation_list}
	 {section show=eq($:item.locale_code,$regional_info.primary_language)}
	   <b>{$:item.language_name}</b>
	 {section-else}
	   {$:item.language_name}
	 {/section}
	 {section show=$:item.country_variation}
	   [{$:item.language_comment}]
	 {/section}
	 {delimiter}, {/delimiter}
	{/section}
  </td>
</tr>
</table>
</div>

  <div class="buttonblock">
    <input type="hidden" name="ChangeStepAction" value="" />
    <input class="button" type="submit" name="StepButton_4" value="Start Over" />
    <input class="button" type="submit" name="StepButton_8" value="Setup Database" />
  </div>
  {include uri='design:setup/persistence.tpl'}
</form>
