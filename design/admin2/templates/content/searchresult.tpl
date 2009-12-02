{let use_url_translation=ezini( 'URLTranslator', 'Translation' )|eq( 'enabled' )}

{section show=$search_result}
<table class="list" cellspacing="0">
<tr>
    <th>{'Name'|i18n( 'design/admin/content/search' )}</th>
    <th>{'Type'|i18n( 'design/admin/content/search' )}</th>
</tr>

{section var=SearchResult loop=$search_result sequence=array( bglight, bgdark )}
<tr class="{$SearchResult.sequence}">
<td>
{$SearchResult.class_identifier|class_icon( small, $SearchResult.class_name )}&nbsp;<a href={$SearchResult.item.url_alias|ezurl}>{$SearchResult.item.name|wash}</a>
</td>
<td>
{$SearchResult.item.class_name|wash}
</td>
</tr>
{/section}


</table>
{/section}

{/let}
