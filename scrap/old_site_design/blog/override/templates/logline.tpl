{default archive_view=false()}
<div class="log">

{section show=$archive_view}

    <h2><a href={concat( "content/view/full/", $node.node_id )|ezurl} >{$node.name|wash}</a>
        <em class="byline">{$node.object.published|datetime( custom, "%d %M %Y" )}
        {section show=$node.object.data_map.enable_comments.content}
            <a class="comments" href={$node.url_alias|ezurl}>{fetch('content','list_count',hash(parent_node_id,$node.node_id))} comments</a>
        {section-else}
            {"Comments disabled"|i18n("design/blog/layout")}
        {/section}</em>
    </h2>
    <div class="logentry">
        {attribute_view_gui attribute=$node.data_map.log}
    </div>

{section-else}

  <h2>{$node.object.published|datetime('custom', '%l | %j %F %Y')}</h2>
  <div class="logentry">
    <h3><a href={concat( "content/view/full/", $node.node_id )|ezurl}>{$node.name|wash}</a></h3>
    {attribute_view_gui attribute=$node.object.data_map.log}
    <div class="byline">
       <p>
          {$node.object.published|datetime('custom', '%g:%i%a')} in {$node.parent.name} | 
          {section show=$node.object.data_map.enable_comments.content}
              <a href={$node.url_alias|ezurl}>{fetch('content','list_count',hash(parent_node_id,$node.node_id))} comments</a>
          {section-else}
              {"Comments disabled"|i18n("design/blog/layout")}
          {/section}  
       </p>
    </div>
  </div>

{/section}

</div>
{/default}
