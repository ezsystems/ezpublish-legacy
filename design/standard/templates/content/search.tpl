
<h1>Search for: "{$search_text}" returned {$search_count} objects</h1>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
{section show=$search_text}
<tr>
  {section name=SearchResult loop=$search_result show=$search_result sequence=array(bglight,bgdark)}

	<td class="{$SearchResult:sequence}" valign="top">
<a href={concat("/content/view/full/",$SearchResult:item.main_node_id)|ezurl}>{$SearchResult:item.name}</a>
        
	</td>
{delimiter modulo=1}
</tr>
<tr>
{/delimiter}


  {section-else}
	<td>
	<h3>Your search returned no result</h3>
	</td>
  {/section}
{/section}
</tr>

</table>
