<form action={"/content/advancedsearch/"|ezurl} method="get">
{section show=$search_text}
{switch name=Sw match=$search_count}
  {case match=0}
  <h1>No results were found for searching: "{$search_text}"</h1>
  {/case}
  {case}
  <h1>Search for: "{$search_text}" returned {$search_count} matches</h1>
  {/case}
{/switch}
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  {section name=SearchResult loop=$search_result show=$search_result sequence=array(bglight,bgdark)}
<tr>
	<td class="{$SearchResult:sequence}">
	<a href={concat("/content/view/full/",$SearchResult:item.main_node_id)|ezurl}>{$SearchResult:item.name}</a>
	</td>
</tr>
  {/section}
</table>
{/section}
<br />
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
<tr>
	<td class="bglight">
	Search for:<br />
	<select name="SearchContentClassID">
	<option value="-1">Any class</option>
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
	<input type="submit" name="SelectClass" value="Look in"/>

	{section name=Attribute show=$search_contentclass_id|gt(0)}

	<select name="SearchContentClassAttributeID">
	<option value="-1">Any attribute</option>
	{section name=ClassAttribute loop=$search_content_class_attribute_array}
	<option value="{$Attribute:ClassAttribute:item.id}">{$Attribute:ClassAttribute:item.name}</option>
	{/section}
	</select>

	{/section}
	</td>
</tr>
<tr>
	<td class="bglight">
	In:<br />
	<select name="SearchSectionID">
	<option value="-1">Any section</option>
	{section name=Section loop=$section_array }
	<option {switch name=sw match=$search_section_id}
	        {case match=$Section:item.id}
		selected="selected"
		{/case}
		{case}
		{/case}
		{/switch} value="{$Section:item.id}">{$Section:item.name}</option>
	{/section}
	</select>
</tr>
<tr>
	<td class="bglight">
	Published:<br />
	<select name="SearchDate">
	<option value="-1" {section show=eq($search_date,-1)}selected{/section}>Any time</option>
	<option value="1" {section show=eq($search_date,1)}selected{/section}>Last day</option>
	<option value="2" {section show=eq($search_date,2)}selected{/section}>Last week</option>
	<option value="3" {section show=eq($search_date,3)}selected{/section}>Last month</option>
	<option value="4" {section show=eq($search_date,4)}selected{/section}>Last three month</option>
	<option value="5" {section show=eq($search_date,5)}selected{/section}>Last year</option>
	</select>
</tr>
</table>

</form>

