{let search=false()}
{section show=$use_template_search}
    {set page_limit=10}
    {set search=fetch(content,search,
                      hash(text,$search_text,
                           section_id,$search_section_id,
                           subtree_array,$search_subtree_array,
                           sort_by,array('modified',false()),
                           offset,$view_parameters.offset,
                           limit,$page_limit))}
    {set search_result=$search['SearchResult']}
    {set search_count=$search['SearchCount']}
    {set stop_word_array=$search['StopWordArray']}
    {set search_data=$search}
{/section}

<form action={"/content/search/"|ezurl} method="get">

<h1>{"Search"|i18n("design/standard/content/search")}</h1>

<div class="searchbox">
    <input class="halfbox" type="text" size="20" name="SearchText" id="Search" value="{$search_text|wash}" />
    <input class="button" name="SearchButton" type="submit" value="{'Search'|i18n('design/standard/layout')}" />
</div>

<div class="advancedsearchbox">
    {let adv_url=concat('/content/advancedsearch/',$search_text|gt(0)|choose('',concat('?SearchText=',$search_text|urlencode)))|ezurl}
    <label>{"For more options try the %1Advanced search%2"|i18n("design/standard/content/search","The parameters are link start and end tags.",array(concat("<a href=",$adv_url,">"),"</a>"))}</label>
    {/let}
</div>

{section show=$stop_word_array}
<p>
{"The following words were excluded from the search:"|i18n("design/standard/content/search")} 
{section name=StopWord loop=$stop_word_array}
{$StopWord:item.word|wash}
{delimiter}, {/delimiter}

{/section}
</p>
{/section}

{switch name=Sw match=$search_count}
  {case match=0}
  <div class="warning">
  <h2>{'No results were found when searching for "%1"'|i18n("design/standard/content/search",,array($search_text|wash))}</h2>
  </div>
    <p>{'Search tips'|i18n('design/standard/content/search')}</p>
    <ul>
        <li>{'Check spelling of keywords.'|i18n('design/standard/content/search')}</li>
        <li>{'Try changing some keywords eg. car instead of cars.'|i18n('design/standard/content/search')}</li>
        <li>{'Try more general keywords.'|i18n('design/standard/content/search')}</li>
        <li>{'Fewer keywords gives more results, try reducing keywords until you get a result.'|i18n('design/standard/content/search')}</li>
    </ul>
  {/case}
  {case}
  <div class="feedback">
  <h2>{'Search for "%1" returned %2 matches'|i18n("design/standard/content/search",,array($search_text|wash,$search_count))}</h2>
  </div>
  {/case}
{/switch}

{* Default search results lister *}
{let use_url_translation=ezini('URLTranslator','Translation')|eq('enabled')}

{section show=$search_result}
  {section name=SearchResult loop=$search_result show=$search_result sequence=array(bglight,bgdark)}
      {node_view_gui view=line content_node=$:item}
  {/section}
{/section}

{/let}

{include name=Navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/content/search')
         page_uri_suffix=concat('?SearchText=',$search_text|urlencode,$search_timestamp|gt(0)|choose('',concat('&SearchTimestamp=',$search_timestamp)))
         item_count=$search_count
         view_parameters=$view_parameters
         item_limit=$page_limit}


</form>
{/let}
