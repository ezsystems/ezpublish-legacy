{let product_node=fetch( content, node, hash( node_id, $main_node_id ) )}
<div id="review">

    <div class="maincontentheader">
        <h1>Write your own review</h1>
    </div>

<div class="product">
    <h2>{$product_node.name}</h2>
    <label>Product number</label> {attribute_view_gui attribute=$product_node.object.data_map.product_number}

    {attribute_view_gui attribute=$product_node.object.data_map.image}

    <div class="reviewinfo"><p>Write a review and share your opinion. Please make sure your comments are devoted to the product.</p></div>
</div>

<form enctype="multipart/form-data" method="post" action={concat("/content/edit/",$object.id,"/",$edit_version,"/",$edit_language|not|choose(array($edit_language,"/"),''))|ezurl}>

    {include uri="design:content/edit_validation.tpl"}

    <input type="hidden" name="MainNodeID" value="{$main_node_id}" />

    <div id="rating">
        <label>How do you rate the product</label>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$content_attributes.2.id}" />
        I dislike it
        <input type="radio" name="{$attribute_base}_data_integer_{$content_attributes.2.id}" value="1"
               {section show=eq( $content_attributes.2.data_int, 1 )}checked="checked"{/section} />
        <input type="radio" name="{$attribute_base}_data_integer_{$content_attributes.2.id}" value="2"
               {section show=eq( $content_attributes.2.data_int, 2 )}checked="checked"{/section} />
        <input type="radio" name="{$attribute_base}_data_integer_{$content_attributes.2.id}" value="3"
               {section show=eq( $content_attributes.2.data_int, 3 )}checked="checked"{/section} />
        <input type="radio" name="{$attribute_base}_data_integer_{$content_attributes.2.id}" value="4"
               {section show=eq( $content_attributes.2.data_int, 4 )}checked="checked"{/section} />
        <input type="radio" name="{$attribute_base}_data_integer_{$content_attributes.2.id}" value="5"
               {section show=eq( $content_attributes.2.data_int, 5 )}checked="checked"{/section} />
         I love it
    </div>

    <div id="topic">
        <label>Title of your review</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$content_attributes.0.id}" />
        {attribute_edit_gui attribute_base=$attribute_base attribute=$content_attributes.0}
    </div>

    <div id="description">
        <label>Your review</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$content_attributes.1.id}" />
        {attribute_edit_gui attribute_base=$attribute_base attribute=$content_attributes.1}
    </div>

    <br/>

    <input type="hidden" name="DiscardConfirm" value="0" />

    <div class="buttonblock">
        <input class="button" type="submit" name="DiscardButton" value="Back" />
        <input class="button" type="submit" name="PreviewButton" value="Preview" />
        <input class="defaultbutton" type="submit" name="PublishButton" value="Continue" />
    </div>

</div>
{/let}
