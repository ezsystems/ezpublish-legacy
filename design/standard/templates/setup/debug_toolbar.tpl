<table>
<tr>
<td style="margin: 10 px; padding: 10px;">
<h3>Clear cache:</h3>
{include uri='design:setup/clear_cache.tpl'}
</td>
<td style="margin: 10 px; padding: 10px;">
<h3>Quick settings:</h3>
{let siteaccess=$access_type.name
     select_siteaccess=false}
{include uri='design:setup/quick_settings.tpl'}
{/let}
</td>
</tr>
</table>
