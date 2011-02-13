<form method="post" action={concat( '/design/templateview', $template_settings.template )|ezurl}>

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'Overrides for <%template_name> template in <%current_siteaccess> siteaccess [%override_count]'|i18n( 'design/standard/design/templateview',, hash( '%template_name', $template_settings.template, '%current_siteaccess', $current_siteaccess, '%override_count', $template_settings.custom_match|count ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">
<div class="block">
<label>{'Default template resource'|i18n( 'design/standard/design/templateview' )}:</label>
{$template_settings.base_dir}
</div>


<div class="block">
<label>{'Siteaccess'|i18n( 'design/standard/design/templateview' )}:</label>

<select name="CurrentSiteAccess">
{section name=SiteAccess loop=ezini('SiteAccessSettings','RelatedSiteAccessList')}
    {if eq($current_siteaccess,$:item)}
        <option value="{$SiteAccess:item}" selected="selected">{$:item}</option>
    {else}
        <option value="{$SiteAccess:item}">{$:item}</option>
    {/if}
{/section}
</select>

<input class="button" type="submit" name="SelectCurrentSiteAccessButton" value="{'Set'|i18n( 'design/standard/design/templateview' )}" />

</div>

</div>



{section show=$custom_match}

<table class="list" cellspacing="0">
<tr>
    <th class="tight">&nbsp;</th>
    <th>{'Name'|i18n( 'design/standard/design/templateview' )}</th>
    <th>{'File'|i18n( 'design/standard/design/templateview' )}</th>
    <th>{'Match conditions'|i18n( 'design/standard/design/templateview' )}</th>
    <th class="tight">{'Priority'|i18n( 'design/standard/design/templateview' )}</th>
    <th class="tight">&nbsp;</th>
</tr>
{section var=CustomMatch loop=$template_settings.custom_match sequence=array( bglight, bgdark )}
<tr class="{$CustomMatch.sequence}">
    <td><input type="checkbox" name="RemoveOverrideArray[]" value="{$CustomMatch.item.override_name}" /></td>
    <td>{$CustomMatch.item.override_name}</td>
    <td>{$CustomMatch.item.match_file}</td>
    <td>
        {section show=is_set( $CustomMatch.item.conditions )}
            {section name=Condition  loop=$CustomMatch.item.conditions}
            {$:key} : {$:item}
            {delimiter}
            <br />
            {/delimiter}
            {/section}
    {/section}
    </td>
    <td><input type="text" name="PriorityArray[{$CustomMatch.item.override_name}]" size="2" value="{$CustomMatch.number}" /></td>
    <td><a href={concat( '/design/templateedit/', $CustomMatch.item.match_file)|ezurl}><img src={'edit.gif'|ezimage} alt="Edit" /></a></td>
</tr>
{/section}
</table>

{/section}

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
    <div class="block">
        <input class="button" type="submit" name="RemoveOverrideButton" value="{"Remove selected"|i18n( 'design/standard/design/templateview' )}" {if $template_settings.custom_match|not}disabled="disabled"{/if} />
        <input class="button" type="submit" name="NewOverrideButton" value="{"New override"|i18n( 'design/standard/design/templateview' )}" />
        <div class="right">
            <input class="button" type="submit" name="UpdateOverrideButton" value="{"Update priorities"|i18n( 'design/standard/design/templateview' )}" {if $template_settings.custom_match|not}disabled="disabled"{/if} />
        </div>
    </div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
