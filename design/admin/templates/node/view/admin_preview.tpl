<i>Default admin preview template</i>

<h1>{$node.name}</h1>

{section var=Attributes loop=$node.object.contentobject_attributes}
    <div class="block">
        <label>{$Attributes.item.contentclass_attribute.name|wash}</label>
        <p class="box">{attribute_view_gui attribute=$Attributes.item}</p>
    </div>
{/section}

