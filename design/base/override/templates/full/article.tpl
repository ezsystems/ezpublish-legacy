{* Article - Full view *}

<div class="content-view-full">
    <div class="class-article">

        <h1>{$node.data_map.title.content|wash()}</h1>
        {if $node.data_map.author.content.is_empty|not()}
        <div class="attribute-byline">
        <p class="author">
             {attribute_view_gui attribute=$node.data_map.author}
        </p>
        <p class="date">
             {$node.object.published|l10n(date)}
        </p>
        </div>
        {/if}

        {if $node.data_map.image.content}
            <div class="attribute-image">
                {attribute_view_gui attribute=$node.data_map.image align=right}
            </div>
        {/if}

        {if $node.data_map.intro.content.is_empty|not}
            <div class="attribute-short">
                {attribute_view_gui attribute=$node.data_map.intro}
            </div>
        {/if}

        {if $node.data_map.body.content.is_empty|not}
            <div class="attribute-long">
                {attribute_view_gui attribute=$node.data_map.body}
            </div>
        {/if}

        <div class="attribute-tipafriend">
          <p>
             <a href={concat('/content/tipafriend/',$node.node_id)|ezurl}>{"Tip a friend"|i18n("design/base")}</a>
          </p>
        </div>

        <div class="attribute-pdf">
          <p>
             <a href={concat('/content/pdf/',$node.node_id)|ezurl}>{'application/pdf'|mimetype_icon( small, "Download PDF"|i18n( "design/base" ) )} {"Download PDF version of this page"|i18n( "design/base" )}</a>
          </p>
        </div>

        {* Should we allow comments? *}
        {section show=is_unset( $versionview_mode )}
        {section show=$node.data_map.enable_comments.content}
            <h2>{"Comments"|i18n("design/base")}</h2>
                <div class="content-view-children">
                    {section name=Child loop=fetch_alias( comments, hash( parent_node_id, $node.node_id ) )}
                        {node_view_gui view='line' content_node=$:item}
                    {/section}
                </div>

                {* Are we allowed to create new object under this node? *}
                {if fetch( content, access,
                                     hash( access, 'create',
                                           contentobject, $node,
                                           contentclass_id, 'comment' ) )}
                    <form method="post" action={"content/action"|ezurl}>
                    <input type="hidden" name="ClassIdentifier" value="comment" />
                    <input type="hidden" name="NodeID" value="{$node.node_id}" />
                    <input class="button new_comment" type="submit" name="NewButton" value="{'New Comment'|i18n( 'design/base' )}" />
                    </form>
                {else}
                    <h3>{"You are not allowed to create comments."|i18n("design/base")}</h3>
                {/if}
        {/section}
        {/section}

    </div>
</div>


