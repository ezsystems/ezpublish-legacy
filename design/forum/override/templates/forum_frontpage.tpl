<div id="folder">


{section show=$node.object.can_edit}
<div class="editbutton">
   <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/node/view')}" />
</div>
{/section}

<h1>{$node.name}</h1>


<div class="object_content">
{attribute_view_gui attribute=$node.object.data_map.description}
</div>

</div>