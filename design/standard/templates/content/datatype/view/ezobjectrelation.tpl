{switch name=sw match=$attribute.content}
   {case match=0}
   No relation
   {/case}
   {case}
   <a href="/content/view/{$attribute.content.main_node_id}/">{$attribute.content.name}</a>
   {/case}
{/switch}
