
<h1>Search for: {$search_text} returned {$search_count} objects</h1>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
{section show=$search_text}
  {section name=SearchResult loop=$search_result show=$search_result sequence=array(bglight,bgdark)}
<tr>
	<td class="{$SearchResult:sequence}">
	<a href="/content/view/full/{$SearchResult:item.main_node_id}"><img src={"class_2.png"|ezimage} border="0"> {$SearchResult:item.name}</a>

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