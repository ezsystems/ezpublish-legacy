{let album_node=fetch( content, node, hash( node_id, $main_node_id ) )}
<div id="image">

    {section show=$object.main_node_id}
        <h1>Edit image in album {$album_node.name|wash}</h1>
    {section-else}
        <h1>Create a new image in album {$album_node.name|wash}</h1>
    {/section}

<form enctype="multipart/form-data" method="post" action={concat("/content/edit/",$object.id,"/",$edit_version,"/",$edit_language|not|choose(array($edit_language,"/"),''))|ezurl}>

    {include uri="design:content/edit_validation.tpl"}

    <input type="hidden" name="MainNodeID" value="{$main_node_id}" />

    {let name_attribute=$content_attributes_data_map.name}
    <div id="name">
        <label>Name of your image</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$name_attribute.id}" />
        {attribute_edit_gui attribute_base=$attribute_base attribute=$name_attribute}
    </div>
    {/let}

    {let caption_attribute=$content_attributes_data_map.caption}
    <div id="caption">
        <label>Caption</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$caption_attribute.id}" />
        {attribute_edit_gui attribute_base=$attribute_base attribute=$caption_attribute}
    </div>
    {/let}

    {let image_attribute=$content_attributes_data_map.image}
    <div id="description">
        <label>Image</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$image_attribute.id}" />
        {attribute_edit_gui attribute_base=$attribute_base attribute=$image_attribute}
    </div>
    {/let}

    <br/>

    <input type="hidden" name="DiscardConfirm" value="0" />

    <div class="buttonblock">
        <input class="button" type="submit" name="DiscardButton" value="Back" />
        <input class="button" type="submit" name="PreviewButton" value="Preview" />
        <input class="defaultbutton" type="submit" name="PublishButton" value="Continue" />
    </div>

</div>
{/let}
