
<h1>Search for: "{$search_text}" returned {$search_count} objects</h1>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
{section show=$search_text}
<tr>
  {section name=SearchResult loop=$search_result show=$search_result max=2 sequence=array(bglight,bgdark)}

	<td class="{$SearchResult:sequence}" valign="top">
	{content_view_gui view=searchline content_object=$SearchResult:item} 
	</td>

{delimiter modulo=2}
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

<h1>BANNER</h1>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
{section show=$search_text}
<tr>
  {section name=SearchResult loop=$search_result show=$search_result offset=2 sequence=array(bglight,bgdark)}

	<td class="{$SearchResult:sequence}" valign="top">
	{content_view_gui view=searchline content_object=$SearchResult:item} 
	</td>

{delimiter modulo=3}
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

