
<h1>Search for: {$search_text} returned {$search_count} objects</h1>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
{section show=$search_text}
  {section name=SearchResult loop=$search_result show=$search_result sequence=array(bglight,bgdark)}
<tr>
	<td class="{$SearchResult:sequence}">
	{content_view_gui view=line content_object=$SearchResult:item} 
	</td>
</tr>
  {section-else}
<tr>
	<td>
	<h3>Your search returned no result</h3>
	</td>
</tr>
  {/section}
{/section}

</table>