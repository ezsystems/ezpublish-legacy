<form action="/search/advancedsearch/" method="get">
<h1>Search for: {$search_text} returned {$search_count} objects</h1>

<table cellspacing="0">
<tr>
	<td class="bglight">
	Search all the words:<br/>
	<input type="text" size="40" name="SearchText" value="{$full_search_text}"/> &nbsp;
	<input type="submit" name="SearchButton" value="Search"/>
	<br />
	Search the exact phrase:<br/>
	<input type="text" size="40" name="PhraseSearchText" value="{$phrase_search_text}"/>
	<br />
	Search with at least one of the words:<br/>
	<input type="text" size="40" name="AnyWordSearchText" value=""/>
	</td>
</tr>
	<td class="bglight">
	Search only these kind of objects:<br />
	<select name="SearchContentClassID">
	<option value="-1">All</option>
	{section name=ContentClass loop=$content_class_array }

	<option {switch name=sw match=$search_contentclass_id}
	   {case match=$ContentClass:item.id}
	   selected="selected"
	   {/case}
	   {case}
	   {/case}
	{/switch} value="{$ContentClass:item.id}">{$ContentClass:item.name}</option>

	{/section}
	</select>
	<input type="submit" name="SelectClass" value="Select class"/>

	{section name=Attribute show=$search_contentclass_id|gt(0)}

	<select name="SearchContentClassAttributeID">
	<option value="-1">All</option>
	{section name=ClassAttribute loop=$search_content_class_attribute_array}
	<option value="{$Attribute:ClassAttribute:item.id}">{$Attribute:ClassAttribute:item.name}</option>
	{/section}
	</select>

	{/section}
	</td>
</tr>
</table>

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

</form>