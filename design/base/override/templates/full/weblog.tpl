{* Weblog - Full view *}

<div class="content-view-full">
    <div class="class-weblog">

        <h1>{$node.name|wash()}</h1>

        <div class="attribute-byline">
           <p class="author">{$node.object.owner.name|wash(xhtml)}</p>
           <p class="date">{$node.object.published|l10n(date)}</p>
           <div class="break"></div>
        </div>

        <div class="attribute-message">
           {attribute_view_gui attribute=$node.object.data_map.message}
        </div>

        {section show=$node.object.data_map.enable_comments.content}
            <h2>{"Comments"|i18n("design/weblog/layout")}</h2>

            <div class="view-children">
               {section var=comment loop=fetch_alias( comments, hash( parent_node_id, $node.node_id ) )}
                   {node_view_gui view='line' content_node=$comment}
               {/section}
            </div>

            {section show=fetch( content, access, hash( access, 'can_create',
                                                        contentobject, $node,
                                                        contentclass_id, 'comment',
                                                        parent_contentclass_id, $node.object.class_identifier ) )}
            <div class="content-action">
                <form method="post" action={"content/action"|ezurl}>
                   <input type="hidden" name="ClassIdentifier" value="comment" />
                   <input type="hidden" name="NodeID" value="{$node.node_id}" />
                   <input class="button" type="submit" name="NewButton" value={"New comment"|i18n("design/weblog/layout")} />
                </form>
            </div>
            {section-else}
                <div class="message-warning">
                    <h3>You are not allowed to create comments.</h3>
                </div>
            {/section}
        {/section}
   </div>
</div>