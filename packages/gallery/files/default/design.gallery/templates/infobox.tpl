<div id="infobox">
    <div class="design">
        {let gallery_list=fetch( content, tree,
                                 hash( parent_node_id, 2,
                                       limit, 5,
                                       sort_by, array( published, false() ),
                                       class_filter_type, include, 
                                       class_filter_array, array( 'gallery' ) ) )}

        <h3><a href={"/"|ezurl}>{"Gallery list"|i18n("design/gallery/layout")}</a></h3>
        <ul>
            {section name=Gallery loop=$gallery_list}
            <li>
                <a href={$:item.url_alias|ezurl}>{$Gallery:item.name|wash}</a>
            </li>
            {/section}
        </ul>
        {/let}

        {let image_list=fetch( content, tree,
                               hash( parent_node_id, 2,
                                     limit, 3,
                                     sort_by, array( published, false() ),
                                     class_filter_type, include, 
                                     class_filter_array, array( 'image' ) ) )}

        <h3>{"Latest images"|i18n("design/gallery/layout")}</h3>
        <ul>
            {section var=image loop=$image_list}
            <li>
                {attribute_view_gui href=$image.item.url_alias|ezurl image_class=small_v attribute=$image.item.data_map.image}
                {attribute_view_gui attribute=$image.item.data_map.caption}
            </li>
            {/section}
        </ul>
        {/let}

        {let news_list=fetch( content, tree,
                              hash( parent_node_id, 2,
                                    limit, 5,
                                    sort_by, array( published, false() ),
                                    class_filter_type, include, 
                                    class_filter_array, array( 'article' ) ) )}

        <h3>{"Latest news"|i18n("design/gallery/layout")}</h3>
        <ul>
            {section var=news loop=$news_list}
            <li>
                <a href={$news.item.url_alias|ezurl}>{$news.item.name|wash}</a>
                <div class="date">
                    ({$news.item.object.published|l10n( shortdate )})
                </div>
            </li>
            {/section}
        </ul>
        {/let}

        {let comments_list=fetch( content, tree,
                                  hash( parent_node_id, 2,
                                        limit, 5,
                                        sort_by, array( published, false() ),
                                        class_filter_type, include, 
                                        class_filter_array, array( 'comment' ) ) )}

        <h3>{"Latest comments"|i18n("design/gallery/layout")}</h3>
        <ul>
            {section var=comment loop=$comments_list}
            <li>
                <a href={concat( $comment.item.parent.url_alias, "/#commentlist" )|ezurl}>{$comment.item.name|wash}</a>
                <div class="date">
                    ({$comment.item.object.published|l10n( shortdate )})
                </div>  
            </li>
            {/section}
        </ul>
        {/let}
    
    </div>
</div>
