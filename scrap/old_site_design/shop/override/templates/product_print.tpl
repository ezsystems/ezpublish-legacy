<h1>{$node.name|wash}</h1>

{attribute_view_gui attribute=$node.object.data_map.image}

{attribute_view_gui attribute=$node.object.data_map.product_number}

{attribute_view_gui attribute=$node.object.data_map.description}

{attribute_view_gui attribute=$node.object.data_map.price}

{let related_objects=$node.object.related_contentobject_array}
    {section show=$related_objects} 
       <h2>{"Related products"|i18n("design/shop/layout")}</h2>  
           {section name=ContentObject  loop=$related_objects show=$related_objects} 
              {content_view_gui view=text_linked content_object=$ContentObject:item}
           {/section}
    {/section}
{/let}

<h3>{"People who bought this also bought"|i18n("design/shop/layout")}</h3>
{let related_purchase=fetch( shop, related_purchase, hash( contentobject_id, $node.contentobject_id,
                                                           limit, 10 ) )}
{section name=Products loop=$related_purchase}
   {content_view_gui view=text_linked content_object=$Products:item}
{/section}
{/let}

{let review_list=fetch( content, list, hash( parent_node_id, $node.node_id,
                                             class_filter_type, include, class_filter_array, array( 25 ),
                                             sort_by, array( published, false() ),
                                             limit, 10 ) )}
{section show=$review_list}
<h3>{"Reviews"|i18n("design/shop/layout")}</h3>
    {section var=review loop=$review_list}
        {node_view_gui view=line content_node=$review.item}
    {/section}
{/section}
{/let}
