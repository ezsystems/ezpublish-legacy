{let product_node=fetch( content, node, hash( node_id, $node.object.parent_nodes.0 ) )}

<div class="product">
    <h1>{$product_node.name}</h1>
    <div class="productnumber">
    <p>{attribute_view_gui attribute=$product_node.data_map.product_number}</p>
    </div>
    

    {attribute_view_gui attribute=$product_node.object.data_map.image}

<form method="post" action={concat("content/versionview/",$object.id,"/",$object_version,"/",$language|not|choose(array($language,"/"),""))|ezurl}>

{node_view_gui view=line content_object=$object node_name=$object.name content_node=$assignment.temp_node node=$node}

<input type="hidden" name="ContentObjectID" value="{$object.id}" />
<input type="hidden" name="ContentObjectVersion" value="{$object_version}" />
<input type="hidden" name="ContentObjectLanguageCode" value="{$object_languagecode}" />
<input type="hidden" name="ContentObjectPlacementID" value="{$placement}" />

<div class="buttonblock">
{section show=and(eq($version.status,0),$is_creator,$object.can_edit)}
    <input class="button" type="submit" name="EditButton" value="Back" />
    <input class="defaultbutton" type="submit" name="PreviewPublishButton" value="Continue" />
{/section}
</div>

</form>
</div>

