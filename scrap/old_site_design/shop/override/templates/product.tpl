{* Configuration of product view
  review_limit: The number of reviews to display under the product
*}
{let review_limit=4}

<div class="product">
<form method="post" action={"content/action"|ezurl}> 

<h1>{$node.name|wash}</h1>

{attribute_view_gui attribute=$node.object.data_map.image}

<div class="productnumber">
<p>{attribute_view_gui attribute=$node.object.data_map.product_number}</p>
</div>

{attribute_view_gui attribute=$node.object.data_map.description}

<div class="price">
<p>{attribute_view_gui attribute=$node.object.data_map.price}</p>
</div>

{let related_objects=$node.object.related_contentobject_array}
    {section show=$related_objects} 
       <h2>{"Related products"|i18n("design/shop/layout")}</h2>  
           {section name=ContentObject  loop=$related_objects show=$related_objects} 
              {content_view_gui view=text_linked content_object=$ContentObject:item}
           {/section}
    {/section}
{/let}

<div class="controls">
    <input type="submit" class="defaultbutton" name="ActionAddToBasket" value="{"Add to basket"|i18n("design/shop/layout")}" />

    <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
    <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
    <input type="hidden" name="ViewMode" value="full" /> 

    <input class="button" type="submit" name="ActionAddToNotification" value="{"Notify me about updates"|i18n("design/shop/layout")}{*to {$node.name|wash}*}" />

    <div class="rightobject"><p><a href={concat( '/layout/set/print/', $node.url_alias )|ezurl}>{"Printerfriendly version"|i18n("design/shop/layout")}</a></p></div>
</div>

</form>

<h2>{"People who bought this also bought"|i18n("design/shop/layout")}</h2>

<div class="relatedorders">
    {let related_purchase=fetch( shop, related_purchase, hash( contentobject_id, $node.contentobject_id,
                                                               limit, 10 ) )}
    {section var=product loop=$related_purchase}
       {content_view_gui view=text_linked content_object=$product.item}
    {/section}
    {/let}
</div>

{let review_list=fetch( content, list, hash( parent_node_id, $node.node_id,
                                             class_filter_type, include, class_filter_array, array( 25 ),
                                             sort_by, array( published, false() ),
                                             limit, $review_limit ) )}

<div class="reviews">
    <h2>{"Reviews"|i18n("design/shop/layout")}</h2>

    <form method="post" action={"content/action"|ezurl}> 
        <input type="hidden" name="ClassID" value="25" />
        <input type="hidden" name="NodeID" value="{$node.node_id}" />
        <input class="reviewbutton" type="submit" name="NewButton" value="{"Write a review"|i18n("design/shop/layout")}" />
    </form>

    {section show=$review_list}
        {section var=review loop=$review_list}
            {node_view_gui view=line content_node=$review.item}
        {/section}
    {/section}
</div>

{/let}

</div>
{/let}
