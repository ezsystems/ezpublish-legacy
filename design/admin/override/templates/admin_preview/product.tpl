{* Product - Admin preview *}

<div class="content-view-full">
    <div class="class-product">

        <h1>{$node.name|wash()}</h1>

    {section show=$node.object.data_map.image.content}
        <div class="attribute-image">
            {attribute_view_gui alignment=right image_class=medium attribute=$node.object.data_map.image.content.data_map.image}
        </div>
    {/section}

        <div class="attribute-short">
           {attribute_view_gui attribute=$node.object.data_map.short_description}
        </div>

        <div class="attribute-long">
           {attribute_view_gui attribute=$node.object.data_map.description}
        </div>

        <div class="attribute-price">
          <p>
           {attribute_view_gui attribute=$node.object.data_map.price}
          </p>
        </div>

        {section show=is_unset( $versionview_mode )}
        <div class="content-action">
        <form method="post" action={"content/action"|ezurl}>
            <input type="submit" class="defaultbutton" name="ActionAddToBasket" value="{"Add to basket"|i18n("design/base")}" />
            <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
            <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
            <input type="hidden" name="ViewMode" value="full" />
        </form>
        </div>
        {/section}

        <div class="attribute-pdf">
          <p>
             <a href={concat('/content/pdf/',$node.node_id)|ezurl}>{"Download this product sheet as PDF"|i18n("design/base")}</a>
          </p>
        </div>

        {let related_purchase=fetch( shop, related_purchase, hash( contentobject_id, $node.contentobject_id,
                                                               limit, 10 ) )}
        {section show=$related_purchase}
            <h2>{"People who bought this also bought"|i18n("design/base")}</h2>

            <div class="relatedorders">
            {section var=product loop=$related_purchase}
                {content_view_gui view=text_linked content_object=$product.item}
            {/section}
            </div>
       {/section}
       {/let}

   </div>
</div>
