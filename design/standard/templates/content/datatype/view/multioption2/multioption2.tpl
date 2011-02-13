{default attribute_base=ContentObjectAttribute
         parent_group_id=0
         parent_multioption_id=-1}
{if $depth|lt(2)}
<label>{'Option group name'|i18n( 'design/standard/content/datatype' )}:</label>
{$group.name}
{/if}
    {include uri='design:content/datatype/view/multioption2/multioption.tpl' name=ChildOption attribute=$attribute group=$group parent_group_id=$parent_group_id depth=$depth attribute_base=$attribute_base}
{/default}



