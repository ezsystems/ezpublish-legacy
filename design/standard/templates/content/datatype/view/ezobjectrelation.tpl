{switch name=sw match=$attribute.content}
   {case match=0}
   No relation
   {/case}
   {case}
   <a href={concat("content/view/",$attribute.content.main_node_id,"/")|ezurl}>{$attribute.content.name}</a>
   {/case}
{/switch}
