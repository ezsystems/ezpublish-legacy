<h1>{$node.name|wash}</h1>

<form method="post" action={"content/action"|ezurl}>

{attribute_view_gui attribute=$node.object.data_map.description}

<p>
<b>Subject:</b><br />
{attribute_view_gui attribute=$node.object.data_map.subject}<br />
<b>E-mail:</b><br />
{attribute_view_gui attribute=$node.object.data_map.email}<br />
<b>Message:</b><br />
{attribute_view_gui attribute=$node.object.data_map.message}<br />
</p>


<div class="block">
        <input type="submit" class="defaultbutton" name="ActionCollectInformation" value="Send" />
</div> 

<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
<input type="hidden" name="ViewMode" value="full" /> 

</form>


