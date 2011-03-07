{* Image - Full view *}

{def $parent        = $node.parent
     $previous_item = false()
     $next_item     = false()}

<div class="content-view-full">
    <div class="class-image parent-class-{$parent.class_identifier|wash}">

        <h1>{$node.name|wash}</h1>

    {if is_unset( $versionview_mode )}
        {* Generate next and previous links if parent is gallery *}
        {if $parent.class_identifier|eq( 'gallery' )}
             {* Limit fetch to 10000 items to avoid very large memory use.
                If you need larger galleries then that, consider using a index in url, downside
                is that it will break bookmarks / links if somone changes sorting.
              *}
             {def $siblings = fetch( 'content', 'list', hash( 'parent_node_id',    $parent.node_id,
                                                              'as_object',         false(),
                                                              'class_filter_type', 'include',
                                                              'class_filter_array', array( 'image' ),
                                                              'sort_by',            $parent.sort_array,
                                                              'limit',              10000 ) )
                  $index    = 0
                  $node_id  = $node.node_id}
             {while is_set( $siblings[$index] )}
                 {if $siblings[$index]['node_id']|eq( $node_id )}
                     {if $index}
                         {set $previous_item = fetch( 'content', 'node', hash( 'node_id', $siblings[$index|dec]['node_id'] ))}
                     {/if}
                     {if is_set( $siblings[$index|inc] )}
                         {set $next_item = fetch( 'content', 'node', hash( 'node_id', $siblings[$index|inc]['node_id'] ))}
                     {/if}
                     {break}
                 {/if}
                 {set $index = $index|inc}
             {/while}
             {undef $siblings $index $node_id}
        {/if}
        <div class="content-navigator">
            {if $previous_item}
                <div class="content-navigator-previous">
                    <div class="content-navigator-arrow">&laquo;&nbsp;</div><a href={$previous_item.url_alias|ezurl} title="{$previous_item.name|wash}">{'Previous image'|i18n( 'design/base' )}</a>
                </div>
                <div class="content-navigator-separator">|</div>
            {else}
                <div class="content-navigator-previous-disabled">
                    <div class="content-navigator-arrow">&laquo;&nbsp;</div>{'Previous image'|i18n( 'design/base' )}
                </div>
                <div class="content-navigator-separator-disabled">|</div>
            {/if}

            <div class="content-navigator-forum-link"><a href={$parent.url_alias|ezurl}>{$parent.name|wash}</a></div>

            {if $next_item}
                <div class="content-navigator-separator">|</div>
                <div class="content-navigator-next">
                    <a href={$next_item.url_alias|ezurl} title="{$next_item.name|wash}">{'Next image'|i18n( 'design/base' )}</a><div class="content-navigator-arrow">&nbsp;&raquo;</div>
                </div>
            {else}
                <div class="content-navigator-separator-disabled">|</div>
                <div class="content-navigator-next-disabled">
                    {'Next image'|i18n( 'design/base' )}<div class="content-navigator-arrow">&nbsp;&raquo;</div>
                </div>
            {/if}
        </div>
    {/if}

        <div class="attribute-image">
            <p>{attribute_view_gui attribute=$node.data_map.image image_class=large}</p>
        </div>

        <div class="attribute-caption">
            {attribute_view_gui attribute=$node.data_map.caption}
        </div>

    </div>
</div>
{/let}