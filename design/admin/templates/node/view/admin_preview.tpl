{* Default preview template for admin interface. *}
{* Will be used if there is no suitable override for a specific class. *}
<h1>{$node.name}</h1>

{* Display all the attributes using their default template. *}
{section var=Attributes loop=$node.object.contentobject_attributes}
    <div class="block">
        <label>{$Attributes.item.contentclass_attribute.name|wash}</label>
        <p class="box">{attribute_view_gui attribute=$Attributes.item}</p>
    </div>
{/section}
