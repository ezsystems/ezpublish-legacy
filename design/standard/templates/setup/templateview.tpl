<form method="post" action={concat('/setup/templateview',$template_settings.template)|ezurl}>

<h1>Template view: {$template_settings.template}</h1>

<p>
Default template resource: <b>{$template_settings.base_dir}</b>
</p>

<select name="CurrentSiteAccess">
{section name=SiteAccess loop=ezini('SiteAccessSettings','AvailableSiteAccessList')}
    {section show=eq($current_siteaccess,$:item)}
        <option value="{$SiteAccess:item}" selected="selected">{$:item}</option>
    {section-else}
        <option value="{$SiteAccess:item}">{$:item}</option>
    {/section}
{/section}    
</select>
<input type="submit" value="Set" name="SelectCurrentSiteAccessButton" />

{section show=$custom_match}

<table class="list" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>
    Override
    </th>
    <th>
    File
    </th>
    <th>
    Match conditions
    </th>
    <th>
    Edit
    </th>
    <th>
    Remove
    </th>
</tr>
{section name=CustomMatch loop=$template_settings.custom_match sequence=array(bglight,bgdark)}
<tr class="{$:sequence}">
    <td valign="top">
        {$CustomMatch:item.override_name} 
    </td>
    <td valign="top">
        {$CustomMatch:item.match_file} 
    </td>
    <td valign="top">
        {section name=Condition loop=$CustomMatch:item.conditions}
        {$:key} : {$:item}
        {delimiter}
            <br />
        {/delimiter}
        {/section}
    </td>
    <td valign="top">
        <a href={concat('/setup/templateedit/',$CustomMatch:item.match_file)|ezurl}>[ edit ]</a>
    </td>
    <td valign="top">
        <input type="checkbox" name="RemoveOverrideArray[]" value="{$CustomMatch:item.override_name}" />
    </td>
</tr>
{/section}
</table>

{/section}

<div class="buttonblock">
<input class="button" type="submit" value="Create new" name="NewOverrideButton" />
<input class="button" type="submit" value="Remove" name="RemoveOverrideButton" />
</div>

</form>