<form method="post" action={concat( '/setup/templateview', $template_settings.template )|ezurl}>

<div class="context-block">
<h2 class="context-title">{'Overrides for <%template_name> template in <%current_siteaccess> siteaccess [%override_count]'|i18n( 'design/standard/setup/templateview',, hash( '%template_name', $template_settings.template, '%current_siteaccess', $current_siteaccess, '%override_count', $template_settings.custom_match|count ) )|wash}</h2>

<div class="context-attributes">
<div class="block">
<label>{'Default template resource:'|i18n( 'design/standard/setup/templateview' )}</label>
{$template_settings.base_dir}
</div>


<div class="block">
<label>{'Siteaccess'|i18n( 'design/admin/setup/templateview' )}:</label>

<select name="CurrentSiteAccess">
{section name=SiteAccess loop=ezini('SiteAccessSettings','AvailableSiteAccessList')}
    {section show=eq($current_siteaccess,$:item)}
        <option value="{$SiteAccess:item}" selected="selected">{$:item}</option>
    {section-else}
        <option value="{$SiteAccess:item}">{$:item}</option>
    {/section}
{/section}
</select>

<input class="button" type="submit" name="SelectCurrentSiteAccessButton" value="{'Set'|i18n( 'design/standard/setup/templateview' )}" />

</div>

</div>






{section show=$custom_match}

<table class="list" cellspacing="0">
<tr>
    <th class="tight">&nbsp;</th>
    <th>{'Name'|i18n( 'design/standard/setup/templateview' )}</th>
    <th>{'File'|i18n( 'design/standard/setup/templateview' )}</th>
    <th>{'Match conditions'|i18n( 'design/standard/setup/templateview' )}</th>
    <th class="tight">{'Priority'|i18n( 'design/standard/setup/templateview' )}</th>
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
    <td><a href={concat( '/setup/templateedit/', $CustomMatch.item.match_file)|ezurl}><img src={'edit.png'|ezimage} alt="Edit" /></a></td>
</tr>
{/section}
</table>

{/section}

<div class="controlbar">
    <div class="block">
        <input class="button" type="submit" name="RemoveOverrideButton" value="{"Remove selected"|i18n( 'design/standard/setup/templateview' )}" {section show=$template_settings.custom_match|not}disabled="disabled"{/section} />
        <input class="button" type="submit" name="NewOverrideButton" value="{"New override"|i18n( 'design/standard/setup/templateview' )}" />
        <div class="right">
            <input class="button" type="submit" name="UpdateOverrideButton" value="{"Update priorities"|i18n( 'design/standard/setup/templateview' )}" {section show=$template_settings.custom_match|not}disabled="disabled"{/section} />
        </div>
    </div>
</div>

</div>
</form>