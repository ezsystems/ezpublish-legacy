{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup}

<div align="center">
  <h1>{"Language selection"|i18n("design/standard/setup/init")}</h1>
</div>
<p>

<form method="post" action="{$script}">

<table border="0" cellspacing="0" cellpadding="0">

  <tr>
    <th class="normal" colspan="3">{"Languages"|i18n("design/standard/setup/init")}:</th>
  </tr>

  {section name=Language loop=$language_list}
    <tr>    
      <td class="normal">
        <input type="checkbox" name="eZSetupLanguages[]" value="{$:key}" {section show=eq($:number,1)}checked="checked" {/section}/>
	<input type="radio" name="eZSetupDefaultLanguage" value="{$:key}" {section show=eq($:number,1)}checked="checked" {/section}/>
	{$:item}
      </td>
    </tr>
  {/section}

</table>
</p>
<p>
{"You can choose more than one language to use with eZ publish."|i18n("design/standard/setup/init")}
{"You will later be asked to choose the languages for the sites you would like to set up."|i18n("design/standard/setup/init")}
</p>

{include uri="design:setup/init/steps.tpl"}
{include uri="design:setup/persistence.tpl"}

<div class="buttonblock">
  <input class="defaultbutton" type="submit" name="StepButton" value="&gt;&gt;" />
</div>


</form>
</p>

