{* Product - Full view *}

<div class="view-full">
    <div class="class-product">

        <h2>{$node.name|wash()}</h2>

        <div class="content-short">
           {attribute_view_gui attribute=$node.object.data_map.intro}
        </div>

        <div class="content-long">
           {attribute_view_gui attribute=$node.object.data_map.body}
        </div>

        <div class="content-price">
           {attribute_view_gui attribute=$node.object.data_map.price}
        </div>

        <div class="content-action">
        <form method="post" action={"content/action"|ezurl}>
            <input type="submit" class="defaultbutton" name="ActionAddToBasket" value="{"Add to basket"|i18n("design/shop/layout")}" />
            <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
            <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
            <input type="hidden" name="ViewMode" value="full" />
            <input class="button" type="submit" name="ActionAddToNotification" value="{"Notify me about updates"|i18n("design/shop/layout")}{*to {$node.name|wash}*}" />
        </form>
        </div>

        {let related_purchase=fetch( shop, related_purchase, hash( contentobject_id, $node.contentobject_id,
                                                               limit, 10 ) )}
        {section show=$related_purchase}
            <h2>{"People who bought this also bought"|i18n("design/shop/layout")}</h2>

            <div class="relatedorders">
            {section var=product loop=$related_purchase}
                {content_view_gui view=text_linked content_object=$product.item}
            {/section}
            </div>
       {/section}
       {/let}
</div>

   </div>
</div>
