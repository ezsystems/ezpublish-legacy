{* Latest news *}

{default news_node=fetch(content, node, hash( node_id, 50 ) )}
<table class="frontpagelist" width="100%">
<tr>
  <th><p>{"Latest news"|i18n("design/forum/layout")}</p></th>
</tr>
<tr>
  <td>
    <ul>
      {section name=Child loop=fetch('content','tree',hash(parent_node_id,$news_node.main_node_id,limit,$count,sort_by,array(array('modified',false()))))}
        <li>
	  <a href={$Child:item.url_alias|ezurl}>{$Child:item.name|wash}</a>
	</li>
      {/section}
    </ul>
  </td>
</tr>
</table>
{/default}
