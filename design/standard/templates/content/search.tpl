<div class="maincontentheader">
<h1>{"Search"|i18n("design/standard/content/search")}</h1>
</div>

<form action={"/content/search/"|ezurl} method="get">

<div class="block">
    <input class="halfbox" type="text" size="20" name="SearchText" id="Search" value="{$search_text|wash}" />
    <input class="button" name="SearchButton" type="submit" value="{'Search'|i18n('design/standard/layout')}" />
</div>

</form>

<div class="block">
{let adv_url='/content/advancedsearch/'|ezurl}
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
    <p>Search tips</p>
    <ul>
        <li>Check spelling of keywords.</li>
        <li>Try changing some keywords eg. car instead of cars.</li>
        <li>Try more general keywords.</li>
        <li>Fewer keywords gives more results, try reducing keywords until you get a result.</li>
    </ul>
  {/case}
  {case}
  <div class="feedback">
  <h2>{'Search for "%1" returned %2 matches'|i18n("design/standard/content/search",,array($search_text|wash,$search_count))}</h2>
  </div>
  {/case}
{/switch}
<table class="list" width="100%" border="0" cellspacing="0" cellpadding="0">
{section show=$search_result}
<th>{"Name"|i18n("design/standard/content/search")}</th>
<th>{"Class"|i18n("design/standard/content/search")}</th>
<tr>
  {section name=SearchResult loop=$search_result show=$search_result sequence=array(bglight,bgdark)}
    <td class="{$SearchResult:sequence}">
    <a href={concat("/content/view/full/",$SearchResult:item.main_node_id)|ezurl}>{$SearchResult:item.name|wash}</a>
       
    </td>
    <td class="{$SearchResult:sequence}">
      {$SearchResult:item.object.class_name|wash}
    </td>
  {delimiter modulo=1}
</tr>
<tr>
  {/delimiter}
  {section-else}
  {/section}
{/section}
</tr>
</table>

{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/content/search')
         page_uri_suffix=concat('?SearchText=',$search_text_enc)
         item_count=$search_count
         view_parameters=$view_parameters
         item_limit=$page_limit}
