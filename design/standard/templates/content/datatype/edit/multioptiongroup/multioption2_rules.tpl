{default attribute_base=ContentObjectAttribute
         parent_group_id=0
         parent_multioption_id=-1
         parent_multioption=null}
{section show=$depth|lt(2)}
    <legend>{$group.name}</legend>
    {section-else}
{/section}
    {include uri='design:content/datatype/edit/multioption2/multioption_rule.tpl' name=ChildOption attribute=$attribute group=$group parent_group_id=$parent_group_id parent_multioption=$parent_multioption depth=$depth attribute_base=$attribute_base rules=$rules}
{/default}
