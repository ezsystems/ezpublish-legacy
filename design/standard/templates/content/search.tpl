<div class="maincontentheader">
<h1>Search</h1>
</div>

{switch name=Sw match=$search_count}
  {case match=0}
  <div class="warning">
  <h2>No results were found for searching: "{$search_text}"</h2>
  </div>
  {/case}
  {case}
  <div class="feedback">
  <h2>Search for: "{$search_text}" returned {$search_count} matches</h2>
  </div>
  {/case}
{/switch}
<table class="list" width="100%" border="0" cellspacing="0" cellpadding="0">
{section show=$search_text}
<tr>
  {section name=SearchResult loop=$search_result show=$search_result sequence=array(bglight,bgdark)}
    <td class="{$SearchResult:sequence}">
    <a href={concat("/content/view/full/",$SearchResult:item.main_node_id)|ezurl}>{$SearchResult:item.name}</a>
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