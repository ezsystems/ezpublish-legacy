<form method="post" action={concat('/setup/templateview',$template_settings.template)|ezurl}>
<h1>Template view: {$template_settings.template}</h1>


<table width="100%">
<tr>
    <td>
    <p>
    Current resource: <b>{$template_settings.base_dir}</b>
    </p>
    </td>
    <td>
    <select name="CurrentSiteAccess">
    {section name=SiteAccess loop=ezini('SiteAccessSettings','AvailableSiteAccessList')}
    <option value="{$SiteAccess:item}">{$SiteAccess:item}</option>
    {/section}    
    </select>
    <input type="submit" value="Set" name="SelectCurrentSiteAccessButton" />
    </td>
</tr>
</table>

{section show=$template_settings.custom_match}

<table class="example">
<tr>
    <th>
    <p>
    Override file
    </p>
    </th>
    <th>
    <p>
    Match conditions
    </p>
    </th>
</tr>
{section name=CustomMatch loop=$template_settings.custom_match}
<tr>
    <td valign="top">
    {$CustomMatch:item.match_file} 
    <a href={concat('/setup/templateedit/',$CustomMatch:item.match_file)|ezurl}>[ edit ]</a>
    </td>
    <td valign="top">
    {section name=Condition loop=$CustomMatch:item.conditions}
    {$:key} : {$:item}
    {delimiter}
    <br />
    {/delimiter}
    {/section}
    </td>
</tr>
{/section}
</table>

{/section}
<div class="buttonblock">
<input class="button" type="submit" value="Create new override" name="NewOverrideButton" />
</div>

</form>