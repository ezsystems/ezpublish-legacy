<form action={"/content/advancedsearch/"|ezurl} method="get">
<div class="maincontentheader">
<h1>{"Advanced search"|i18n("design/standard/content/search")}</h1>
</div>
{section show=$search_text}
{switch name=Sw match=$search_count}
  {case match=0}
<div class="warning">
<h2>{"No results were found when searching for:"|i18n("design/standard/content/search")} "{$search_text}"</h2>
</div>
  {/case}
  {case}
<div class="feedback">
<h2>{"Search for:"|i18n("design/standard/content/search")} "{$search_text}" {"returned"|i18n("design/standard/content/search")} {$search_count} {"matches"|i18n("design/standard/content/search")}</h2>
</div>
  {/case}
{/switch}
<table class="list" width="100%" border="0" cellspacing="0" cellpadding="0">
  {section name=SearchResult loop=$search_result show=$search_result sequence=array(bglight,bgdark)}
<tr>
	<td class="{$SearchResult:sequence}">
	<a href={concat("/content/view/full/",$SearchResult:item.main_node_id)|ezurl}>{$SearchResult:item.name}</a>
	</td>
</tr>
  {/section}
</table>
{/section}


<div class="block">
<label>{"Search all the words:"|i18n("design/standard/content/search")}</label><div class="labelbreak"></div>
<input class="box" type="text" size="40" name="SearchText" value="{$full_search_text}" />
</div>
<div class="block">
<label>{"Search the exact phrase:"|i18n("design/standard/content/search")}</label><div class="labelbreak"></div>
<input class="box" type="text" size="40" name="PhraseSearchText" value="{$phrase_search_text}" />
</div>
<div class="block">
<label>{"Search with at least one of the words:"|i18n("design/standard/content/search")}</label><div class="labelbreak"></div>
<input class="box" type="text" size="40" name="AnyWordSearchText" value="" />
</div>
<div class="block">

<div class="element">
<label>{"Class:"|i18n("design/standard/content/search")}</label><div class="labelbreak"></div>
<select name="SearchContentClassID">
<option value="-1">{"Any class"|i18n("design/standard/content/search")}</option>
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

</div>
<div class="element">

<label>{"Class attribute:"|i18n("design/standard/content/search")}</label><div class="labelbreak"></div>

{section name=Attribute show=$search_contentclass_id|gt(0)}

<select name="SearchContentClassAttributeID">
<option value="-1">Any attribute</option>
{section name=ClassAttribute loop=$search_content_class_attribute_array}
<option value="{$Attribute:ClassAttribute:item.id}">{$Attribute:ClassAttribute:item.name}</option>
{/section}
</select>

{/section}
<input class="smallbutton" type="submit" name="SelectClass" value="{'Update attributes'|i18n('design/standard/content/search')}"/>
</div>

<div class="break"></div>
</div>
<div class="block">
<div class="element">

<label>{"In:"|i18n("design/standard/content/search")}</label><div class="labelbreak"></div>
<select name="SearchSectionID">
<option value="-1">{"Any section"|i18n("design/standard/content/search")}</option>
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

</div>
<div class="element">

<label>{"Published:"|i18n("design/standard/content/search")}</label><div class="labelbreak"></div>
<select name="SearchDate">
<option value="-1" {section show=eq($search_date,-1)}selected{/section}>{"Any time"|i18n("design/standard/content/search")}</option>
<option value="1" {section show=eq($search_date,1)}selected{/section}>{"Last day"|i18n("design/standard/content/search")}</option>
<option value="2" {section show=eq($search_date,2)}selected{/section}>{"Last week"|i18n("design/standard/content/search")}</option>
<option value="3" {section show=eq($search_date,3)}selected{/section}>{"Last month"|i18n("design/standard/content/search")}</option>
<option value="4" {section show=eq($search_date,4)}selected{/section}>{"Last three months"|i18n("design/standard/content/search")}</option>
<option value="5" {section show=eq($search_date,5)}selected{/section}>{"Last year"|i18n("design/standard/content/search")}</option>
</select>
</div>

<div class="break"></div>
</div>

<div class="buttonblock'">
<input class="button" type="submit" name="SearchButton" value="{'Search'|i18n('design/standard/content/search')}" />
</div>

</form>

