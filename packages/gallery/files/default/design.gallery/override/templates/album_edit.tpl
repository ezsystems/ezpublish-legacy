{let gallery_node=fetch( content, node, hash( node_id, $main_node_id ) )}
<div id="album">

    {section show=$object.main_node_id}
        <h1>Edit album in gallery {$gallery_node.name|wash}</h1>
    {section-else}
        <h1>Create a new album in gallery {$gallery_node.name|wash}</h1>
    {/section}

<form enctype="multipart/form-data" method="post" action={concat("/content/edit/",$object.id,"/",$edit_version,"/",$edit_language|not|choose(array($edit_language,"/"),''))|ezurl}>

    {include uri="design:content/edit_validation.tpl"}

    <input type="hidden" name="MainNodeID" value="{$main_node_id}" />

    {let name_attribute=$content_attributes_data_map.name}
    <div id="name">
        <label>Name of your album</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$name_attribute.id}" />
        {attribute_edit_gui attribute_base=$attribute_base attribute=$name_attribute}
    </div>
    {/let}

    {let description_attribute=$content_attributes_data_map.description}
    <div id="description">
        <label>Description</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$description_attribute.id}" />
        {attribute_edit_gui attribute_base=$attribute_base attribute=$description_attribute}
    </div>
    {/let}

    {let column_attribute=$content_attributes_data_map.column}
    <div id="description">
        <label>Number of columns</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$column_attribute.id}" />
        {attribute_edit_gui attribute_base=$attribute_base attribute=$column_attribute}
    </div>
    {/let}

    {let image_attribute=$content_attributes_data_map.image}
    <div id="description">
        <label>Album image (optional)</label><div class="labelbreak"></div>
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
