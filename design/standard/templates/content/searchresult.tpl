{* Default search results lister *}
{section show=$search_result}
<table class="list" width="100%" border="0" cellspacing="0" cellpadding="0">
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
</tr>
</table>
{/section}
