{* Weblog - Full view *}

<div class="view-full">
    <div class="class-weblog">

        <h1>{$node.name|wash()}</h1>

        <div class="content-byline">
           <p class="author">{$node.object.owner.name|wash(xhtml)}</p>
           <p class="date">{$node.object.published|l10n(date)}</p>
           <div class="break"></div>
        </div>

        <div class="content-message">
           {attribute_view_gui attribute=$node.object.data_map.message}
        </div>

        {section show=$node.object.data_map.enable_comments.content}
            <h2>Comments</h2>
            <div class="view-children">
               {section var=comment loop=fetch_alias( comments, hash( parent_node_id, $node.node_id ) )}
                   {node_view_gui view='line' content_node=$comment}
               {/section}
            </div>
        {/section}
   </div>
</div>