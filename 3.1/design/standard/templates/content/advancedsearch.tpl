<form action={"/content/advancedsearch/"|ezurl} method="get">
<div class="maincontentheader">
<h1>{"Advanced search"|i18n("design/standard/content/search")}</h1>
</div>

<div class="block">
<label>{"Search all the words"|i18n("design/standard/content/search")}</label><div class="labelbreak"></div>
<input class="box" type="text" size="40" name="SearchText" value="{$full_search_text|wash}" />
</div>
<div class="block">
<label>{"Search the exact phrase"|i18n("design/standard/content/search")}</label><div class="labelbreak"></div>
<input class="box" type="text" size="40" name="PhraseSearchText" value="{$phrase_search_text|wash}" />
</div>
{*<div class="block">
<label>{"Search with at least one of the words"|i18n("design/standard/content/search")}</label><div class="labelbreak"></div>
<input class="box" type="text" size="40" name="AnyWordSearchText" value="" />
</div>*}
<div class="block">

<div class="element">
<label>{"Class"|i18n("design/standard/content/search")}</label><div class="labelbreak"></div>
<select name="SearchContentClassID">
<option value="-1">{"Any class"|i18n("design/standard/content/search")}</option>
{section name=ContentClass loop=$content_class_array }

<option {switch name=sw match=$search_contentclass_id}
{case match=$ContentClass:item.id}
selected="selected"
{/case}
{case}
{/case}
{/switch} value="{$ContentClass:item.id}">{$ContentClass:item.name|wash}</option>

{/section}
</select>

</div>
<div class="element">

<label>{"Class attribute"|i18n("design/standard/content/search")}</label><div class="labelbreak"></div>

{section name=Attribute show=$search_contentclass_id|gt(0)}

<select name="SearchContentClassAttributeID">
<option value="-1">Any attribute</option>
{section name=ClassAttribute loop=$search_content_class_attribute_array}
<option value="{$Attribute:ClassAttribute:item.id}">{$Attribute:ClassAttribute:item.name|wash}</option>
{/section}
</select>

{/section}
<input class="smallbutton" type="submit" name="SelectClass" value="{'Update attributes'|i18n('design/standard/content/search')}"/>
</div>

<div class="break"></div>
</div>
<div class="block">
<div class="element">

<label>{"In"|i18n("design/standard/content/search")}</label><div class="labelbreak"></div>
<select name="SearchSectionID">
<option value="-1">{"Any section"|i18n("design/standard/content/search")}</option>
{section name=Section loop=$section_array }
<option {switch name=sw match=$search_section_id}
     {case match=$Section:item.id}
selected="selected"
{/case}
{case}
{/case}
{/switch} value="{$Section:item.id}">{$Section:item.name|wash}</option>
{/section}
</select>

</div>
<div class="element">

<label>{"Published"|i18n("design/standard/content/search")}</label><div class="labelbreak"></div>
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


{section show=$search_text}
<br/>
{switch name=Sw match=$search_count}
  {case match=0}
<div class="warning">
<h2>{'No results were found when searching for "%1"'|i18n("design/standard/content/search",,array($search_text|wash))}</h2>
</div>
  {/case}
  {case}
<div class="feedback">
<h2>{'Search for "%1" returned %2 matches'|i18n("design/standard/content/search",,array($search_text|wash,$search_count))}</h2>
</div>
  {/case}
{/switch}
<table class="list" width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
  <th>{"Name"|i18n("design/standard/content/search")}</th>
  <th>{"Class"|i18n("design/standard/content/search")}</th>
</tr>

  {section name=SearchResult loop=$search_result show=$search_result sequence=array(bglight,bgdark)}
<tr>
  <td class="{$SearchResult:sequence}">
    <a href={concat("/content/view/full/",$SearchResult:item.main_node_id)|ezurl}>{$SearchResult:item.name|wash}</a>
  </td>
  <td class="{$SearchResult:sequence}">
    {$SearchResult:item.object.class_name|wash}
  </td>
</tr>
  {/section}
</table>
{/section}

{let item_previous=sub($offset,$page_limit) item_next=sum($offset,$page_limit)
     page_count=int(ceil(div($search_count,$page_limit))) current_page=int(ceil(div($offset,$page_limit)))}
<div class="selectbar">
<table class="selectbar" width="100%" cellpadding="0" cellspacing="2" border="0">
<tr>
     {switch match=$item_previous|lt(0) }
       {case match=0}
         <td class="selectbar" width="1%">
         <a class="selectbar" href={concat('/content/advancedsearch/',$item_previous|gt(0)|choose('',concat('offset/',$item_previous)),'?SearchText=',$search_text_enc)|ezurl}><<&nbsp;{"Previous"|i18n("design/standard/navigator")}</a>
         </td>
       {/case}
       {case match=1}
       {/case}
     {/switch}

    <td width="35%">
    &nbsp;
    </td>

    <td width="10%">
    {section name=Quick loop=$page_count max=10 show=$page_count|gt(1)}
    {switch match=$Quick:index}
      {case match=$current_page}
        <b>{$Quick:number}</b>
      {/case}
      {case}
        {let page_offset=mul($Quick:index,$page_limit)}
          <a href={concat('/content/advancedsearch/',$Quick:page_offset|gt(0)|choose('',concat('offset/',$Quick:page_offset)),'?SearchText=',$search_text_enc)|ezurl}>{$Quick:number}</a>
        {/let}
      {/case}
    {/switch}
    {/section}
    </td>
    <td width="35%">
    &nbsp;
    </td>

    {switch match=$item_next|lt($search_count) }
      {case match=1}
        <td class="selectbar" width="1%">
         <a class="selectbar" href={concat('/content/advancedsearch/',$item_next|gt(0)|choose('',concat('offset/',$item_next)),'?SearchText=',$search_text_enc)|ezurl}>{"Next"|i18n("design/standard/navigator")}&nbsp;&gt;&gt;</a>
        </td>
      {/case}
      {case}
      {/case}
    {/switch}
</tr>
</table>
</div>
{/let}


</form>
