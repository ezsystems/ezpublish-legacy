<div class="objectheader">
<h2>Toolbar list</h2>
</div>
<div class="object">
{section show=$current_siteaccess}
<p>Current siteaccess: <strong>{$current_siteaccess}</strong></p>
{/section}
<br />
<form method="post" action={"setup/toolbarlist"|ezurl}>
<div class="block">
    <div class="element">
        <label>Select siteaccess</label><div class="labelbreak"></div>
        <select name="CurrentSiteAccess">
        {section name=SiteAccess loop=$siteaccess_list}
            {section show=eq( $current_siteaccess, $:item )}
                <option value="{$:item}" selected="selected">{$:item}</option>
            {section-else}
                <option value="{$:item}">{$:item}</option>
            {/section}
        {/section}
        </select>
    </div>
</div>

<div class="block">
    <input type="submit" name="ChangeINIFile" value="Select" />
</div>
</form>

</div>

<form method="post" action={concat("setup/toolbarlist/",$current_siteaccess)|ezurl}>
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
{section var=Toolbar loop=$toolbar_list}
<tr>
<td>
<a href={concat( "setup/toolbar/",$current_siteaccess,"/",$Toolbar)|ezurl}>{$Toolbar}</a>
</td>
</tr>
{/section}
</table>
</form>