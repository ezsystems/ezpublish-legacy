<div class="maincontentheader">
<h1>{"Search"|i18n("design/standard/content/search")}</h1>
</div>

<div class="block">
{let adv_url='/content/advancedsearch/'|ezurl}
<label>{"For more options try the %1advanced search%2"|i18n("design/standard/content/search","The parameters are link start and end tags.",array(concat("<a href=",$adv_url,">"),"</a>"))}</label>
{/let}
</div>

{switch name=Sw match=$search_count}
  {case match=0}
  <div class="warning">
  <h2>{'No results were found when searching for "%1"'|i18n("design/standard/content/search",,array($search_text))}</h2>
  </div>
  {/case}
  {case}
  <div class="feedback">
  <h2>{'Search for "%1" returned %2 matches'|i18n("design/standard/content/search",,array($search_text,$search_count))}</h2>
  </div>
  {/case}
{/switch}
<table class="list" width="100%" border="0" cellspacing="0" cellpadding="0">
{section show=$search_result}
<th>{"Object name"|i18n("design/standard/content/search")}</th>
<th>{"Class name"|i18n("design/standard/content/search")}</th>
<tr>
  {section name=SearchResult loop=$search_result show=$search_result sequence=array(bglight,bgdark)}
    <td class="{$SearchResult:sequence}">
    <a href={concat("/content/view/full/",$SearchResult:item.main_node_id)|ezurl}>{$SearchResult:item.name}</a>
       
    </td>
    <td class="{$SearchResult:sequence}">
      {$SearchResult:item.object.class_name}
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
