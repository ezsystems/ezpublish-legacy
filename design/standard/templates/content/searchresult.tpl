{* Default search results lister *}
{let use_url_translation=ezini('URLTranslator','Translation')|eq('enabled')}

{section show=$search_result}
<table class="list" width="100%" border="0" cellspacing="0" cellpadding="0">
<th>{"Name"|i18n("design/standard/content/search")}</th>
<th>{"Class"|i18n("design/standard/content/search")}</th>
<tr>
  {section name=SearchResult loop=$search_result show=$search_result sequence=array(bglight,bgdark)}
    <td class="{$SearchResult:sequence}">
    <a href={cond( $use_url_translation, $:item.url_alias|ezurl,
                   true(), concat( "/content/view/full/", $:item.main_node_id )|ezurl )}>{$SearchResult:item.name|wash}</a>
       
    </td>
    <td class="{$SearchResult:sequence}">
      <nobr>{$SearchResult:item.object.class_name|wash}</nobr>
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

{/let}
