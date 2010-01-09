{let search=false()}
{section show=$use_template_search}
    {set page_limit=10}
    {switch match=$search_page_limit}
    {case match=1}{set page_limit=5}{/case}
    {case match=2}{set page_limit=10}{/case}
    {case match=3}{set page_limit=20}{/case}
    {case match=4}{set page_limit=30}{/case}
    {case match=5}{set page_limit=50}{/case}
    {case/}
    {/switch}
    {set search=fetch(content,search,
                      hash(text,$search_text,
                           section_id,$search_section_id,
                           subtree_array,$search_sub_tree,
                           class_id,$search_contentclass_id,
                           class_attribute_id,$search_contentclass_attribute_id,
                           offset,$view_parameters.offset,
                           publish_date,$search_date,
                           limit,$page_limit))}
    {set search_result=$search['SearchResult']}
    {set search_count=$search['SearchCount']}
    {set stop_word_array=$search['StopWordArray']}
    {set search_data=$search}
{/section}

<form action={'/content/advancedsearch/'|ezurl} method="get">
<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'Advanced search'|i18n( 'design/admin/content/search' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
<label for="ezcontent_advancesearch_search_text">{'Search for all of the following words'|i18n( 'design/admin/content/search' )}:</label>
<input id="ezcontent_advancesearch_search_text" class="halfbox" type="text" size="40" name="SearchText" value="{$full_search_text|wash}" />
</div>

<div class="block">
<label for="ezcontent_advancesearch_phrasesearch_text">{'Search for an exact phrase'|i18n( 'design/admin/content/search' )}:</label>
<input id="ezcontent_advancesearch_phrasesearch_text" class="halfbox" type="text" size="40" name="PhraseSearchText" value="{$phrase_search_text|wash}" />
</div>

<div class="block">

<div class="element">
<label for="ezcontent_advancesearch_class_id">{'Class'|i18n( 'design/admin/content/search' )}:</label>
<select id="ezcontent_advancesearch_class_id" name="SearchContentClassID">
<option value="-1">{'Any class'|i18n( 'design/admin/content/search' )}</option>
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
<label for="ezcontent_advancesearch_class_attribute_id">{'Class attribute'|i18n( 'design/admin/content/search' )}:</label>

{section name=Attribute show=$search_contentclass_id|gt( 0 )}

<select id="ezcontent_advancesearch_class_attribute_id" name="SearchContentClassAttributeID">
<option value="-1">{'Any attribute'|i18n( 'design/admin/content/search' )}</option>
{section name=ClassAttribute loop=$search_content_class_attribute_array}
<option value="{$Attribute:ClassAttribute:item.id}" 
{if eq( $search_contentclass_attribute_id, $Attribute:ClassAttribute:item.id )}selected="selected"{/if}>
{$Attribute:ClassAttribute:item.name|wash}
</option>
{/section}
</select>

&nbsp;

{/section}
<input class="button" type="submit" name="SelectClass" value="{'Update attributes'|i18n( 'design/admin/content/search' )}"/>
</div>

<div class="break"></div>
</div>
<div class="block">
<div class="element">

<label for="ezcontent_advancesearch_section_id">{'In'|i18n( 'design/admin/content/search' )}:</label>
<select id="ezcontent_advancesearch_section_id" name="SearchSectionID">
<option value="-1">{'Any section'|i18n( 'design/admin/content/search' )}</option>
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

<label for="ezcontent_advancesearch_date">{"Published"|i18n( 'design/admin/content/search' )}:</label>
<select id="ezcontent_advancesearch_date" name="SearchDate">
<option value="-1" {if eq( $search_date, -1 )}selected="selected"{/if}>{'Any time'|i18n( 'design/admin/content/search' )}</option>
<option value="1"  {if eq( $search_date,  1 )}selected="selected"{/if}>{'Last day'|i18n( 'design/admin/content/search' )}</option>
<option value="2"  {if eq( $search_date,  2 )}selected="selected"{/if}>{'Last week'|i18n( 'design/admin/content/search' )}</option>
<option value="3"  {if eq( $search_date,  3 )}selected="selected"{/if}>{'Last month'|i18n( 'design/admin/content/search' )}</option>
<option value="4"  {if eq( $search_date,  4 )}selected="selected"{/if}>{'Last three months'|i18n( 'design/admin/content/search' )}</option>
<option value="5"  {if eq( $search_date,  5 )}selected="selected"{/if}>{'Last year'|i18n( 'design/admin/content/search' )}</option>
</select>
</div>

{if $use_template_search}
<div class="element">
<label for="ezcontent_advancesearch_pagelimit">{'Display per page'|i18n( 'design/admin/content/search' )}:</label>
<select id="ezcontent_advancesearch_pagelimit" name="SearchPageLimit">
<option value="1" {if eq($search_page_limit,1)}selected="selected"{/if}>{"5 items"|i18n( 'design/admin/content/search' )}</option>
<option value="2" {if or(array(1,2,3,4,5)|contains($search_page_limit)|not,eq($search_page_limit,2))}selected="selected"{/if}>{"10 items"|i18n( 'design/admin/content/search' )}</option>
<option value="3" {if eq($search_page_limit,3)}selected="selected"{/if}>{"20 items"|i18n( 'design/admin/content/search' )}</option>
<option value="4" {if eq($search_page_limit,4)}selected="selected"{/if}>{"30 items"|i18n( 'design/admin/content/search' )}</option>
<option value="5" {if eq($search_page_limit,5)}selected="selected"{/if}>{"50 items"|i18n( 'design/admin/content/search' )}</option>
</select>
</div>
{/if}

{section name=SubTree loop=$search_sub_tree}
<input type="hidden" name="SubTreeArray[]" value="{$:item}" />
{/section}


<div class="break"></div>
</div>
{if or($search_text,eq(ezini('SearchSettings','AllowEmptySearch','site.ini'),'enabled') )}
<br/>
{switch name=Sw match=$search_count}
  {case match=0}
<div class="warning">
<h2>{'No results were found when searching for <%1>'|i18n( 'design/admin/content/search',, array( $search_text ) )|wash}</h2>
</div>
  {/case}
  {case}
  {/case}
{/switch}
{/if}
</div>
{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
<input class="button" type="submit" name="SearchButton" value="{'Search'|i18n( 'design/admin/content/search' )}" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>

{section show=ne($search_count,0)}
<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h2 class="context-title">{'Search for <%1> returned %2 matches'|i18n( 'design/admin/content/search',, array( $search_text, $search_count ) )|wash}</h2>



{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-content">

{include name=Result
         uri='design:content/searchresult.tpl'
         search_result=$search_result}

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='/content/advancedsearch'
         page_uri_suffix=concat('?SearchText=',$search_text|urlencode,'&PhraseSearchText=',$phrase_search_text|urlencode,'&SearchContentClassID=',$search_contentclass_id,'&SearchContentClassAttributeID=',$search_contentclass_attribute_id,'&SearchSectionID=',$search_section_id,$search_timestamp|gt(0)|choose('',concat('&SearchTimestamp=',$search_timestamp)),$search_sub_tree|gt(0)|choose( '', concat( '&', 'SubTreeArray[]'|urlencode, '=', $search_sub_tree|implode( concat( '&', 'SubTreeArray[]'|urlencode, '=' ) ) ) ),'&SearchDate=',$search_date,'&SearchPageLimit=',$search_page_limit)
         item_count=$search_count
         view_parameters=$view_parameters
         item_limit=$page_limit}
</div>

{* DESIGN: Content END *}</div></div></div>

</div>
{/section}
</form>
{/let}
