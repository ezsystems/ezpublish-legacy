<form method="post" action={"content/action"|ezurl}> 

<h1>{$node.name}</h1>

{attribute_view_gui attribute=$node.object.data_map.image}

{attribute_view_gui attribute=$node.object.data_map.product_number}

{attribute_view_gui attribute=$node.object.data_map.description}

{attribute_view_gui attribute=$node.object.data_map.price}

{let related_objects=$node.object.related_contentobject_array}
    {section show=$related_objects} 
       <h2>Related products</h2>  
           {section name=ContentObject  loop=$related_objects show=$related_objects} 
              {content_view_gui view=text_linked content_object=$ContentObject:item}
           {/section}
    {/section}
{/let}

<a href={concat( '/layout/set/print/', $node.url_alias )|ezurl}><img src={"print.gif"|ezimage} alt="Printer version" /></a>
<input type="submit" class="defaultbutton" name="ActionAddToBasket" value="Add to basket" />

<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
<input type="hidden" name="ViewMode" value="full" /> 


<input class="button" type="submit" name="ActionAddToNotification" value="Notify me about updates to {$node.name}" />


<h3>People who bought this also bought</h3>
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
<h3>Reviews</h3>
    {section var=review loop=$review_list}
        {node_view_gui view=line content_node=$review.item}
    {/section}
{/section}
{/let}

</form>