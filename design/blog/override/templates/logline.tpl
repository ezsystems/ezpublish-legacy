<div class="log">

<h2>{$node.object.published|datetime('custom', '%l | %j %F %Y')}</h2>
 <div class="logentry">
    <h3>{$node.name}</h3>
    {attribute_view_gui attribute=$node.object.data_map.log}
    <div class="byline">
       <p>
          {$node.object.published|datetime('custom', '%g:%i%a')} in {$node.parent.name} | 
          {section show=$node.object.data_map.enable_comments.content}
              <a href={$node.url_alias|ezurl}>{fetch('content','list_count',hash(parent_node_id,$node.node_id))} comments</a>
          {section-else}
              Comments disabled
          {/section}  
       </p>
    </div>
  </div>
</div>