{* Product - Admin preview *}

<div class="content-view-full">
    <div class="class-product">

    <h1>{$node.name|wash()}</h1>

    {* Image. *}
    {section show=$node.object.data_map.image.content}
        <div class="attribute-image">
            {attribute_view_gui alignment=right image_class=medium attribute=$node.object.data_map.image.content.data_map.image}
        </div>
    {/section}

        {* Short description. *}
        <div class="attribute-short">
           {attribute_view_gui attribute=$node.object.data_map.short_description}
        </div>

        {* Description. *}
        <div class="attribute-long">
           {attribute_view_gui attribute=$node.object.data_map.description}
        </div>

        {* Price. *}
        <div class="attribute-price">
          <p>
           {attribute_view_gui attribute=$node.object.data_map.price}
          </p>
        </div>

        {* PDF. *}
        <div class="attribute-pdf">
          <p>
             <a href={concat( '/content/pdf/', $node.node_id )|ezurl}>{'Download this product sheet as PDF'|i18n( 'design/admin/preview/product' )}</a>
          </p>
        </div>

       {* Related products. *}
       {let related_purchase=fetch( shop, related_purchase, hash( contentobject_id, $node.contentobject_id, limit, 10 ) )}
       {section show=$related_purchase}
            <h2>{'People who bought this also bought'|i18n( 'design/admin/preview/product' )}</h2>

            <div class="relatedorders">
            {section var=product loop=$related_purchase}
                {content_view_gui view=text_linked content_object=$product.item}
            {/section}
            </div>
       {/section}
       {/let}

   </div>
</div>
