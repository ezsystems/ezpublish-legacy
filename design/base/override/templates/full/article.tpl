{* Article - Full view *}

<div class="view-full">
    <div class="class-article">

        <h1>{$node.object.data_map.title.content|wash()}</h1>

        <div class="content-image">
        {attribute_view_gui attribute=$node.object.data_map.image align=right}
        </div>

        {section show=$node.object.data_map.intro.content.is_empty|not}
            <div class="content-short">
                {attribute_view_gui attribute=$node.object.data_map.intro}
            </div>
        {/section}

        <div class="content-byline">
        <p class="author">
             {attribute_view_gui attribute=$node.object.data_map.author}
        </p>
        <p class="date">
             {$node.object.published|l10n(date)}
        <p/>
        </div>

        {section show=$node.object.data_map.body.content.is_empty|not}
            <div class="content-long">
                {attribute_view_gui attribute=$node.object.data_map.body}
            </div>
        {/section}

        <div class="tipafriend">
          <a href={concat('/content/tipafriend/',$node.node_id)|ezurl}>{"Tip a friend"|i18n("design/news/layout")}</a>
        </div>

        {* Should we allow comments? *}
        {section show=$node.object.data_map.enable_comments.content}
            <h2>Comments</h2>
                <div class="view-children">
                    {section name=Child loop=fetch_alias( comments, hash( parent_node_id, $node.node_id ) )}
                        {node_view_gui view='line' content_node=$:item}
                    {/section}
                </div>

                {* Are we allowed to create new object under this node? *}
                {section show=$node.object.can_create}
                    <form method="post" action={"content/action"|ezurl}>
                    <input type="hidden" name="ClassIdentifier" value="comment" />
                    <input type="hidden" name="NodeID" value="{$node.node_id}" />
                    <input class="button" type="submit" name="NewButton" value="New Comment" />
                    </form>
                {section-else}
                    <div class="message-warning">
                    <h3>You are not allowed to create comments.</h3>
                    </div>
                {/section}
        {/section}

    </div>
</div>


