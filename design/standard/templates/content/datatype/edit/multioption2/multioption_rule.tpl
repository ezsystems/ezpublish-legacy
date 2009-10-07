
{section show=$group.multioption_list}
{section var=MultiOptionList loop=$group.multioption_list sequence=array( bglight, bgdark )}
    <label>{$MultiOptionList.item.name}</label>
<table class="multioption" cellspacing="0">
<tr>
    <th class="tight">#</th>
    <th>Option</th>
    {section show=$depth|gt(1)}
        {section var=OptionList loop=$parent_multioption.optionlist}
            <th class="tight">{$depth|sub(1)}.{$OptionList.item.id} -{$OptionList.item.value}</th>
        {/section}
    {/section}
</tr>

{section var=Options loop=$MultiOptionList.item.optionlist}
    <tr>
        <th>{$depth}.{$Options.item.id}</th>
        <td>{$Options.item.value}
        {if $depth|gt(1)}
            <input type="hidden" name="{$attribute_base}_data_multioption_rule_for[]" value="{$Options.item.option_id}">
            <input type="hidden" name="{$attribute_base}_data_rule_parent_multioption_id_{$Options.item.option_id}[]" value="{$parent_multioption.multioption_id}">
        {/if}
        </td>
        {section show=$depth|gt(1)}
            {section var=OptionList loop=$parent_multioption.optionlist}
                <td>
{if and($OptionList.item.is_selectable,$Options.item.is_selectable)}
                <input type="checkbox"
                   name="{$attribute_base}_data_multioption_rule_{$Options.item.option_id}_{$parent_multioption.multioption_id}[]"
                      value="{$OptionList.item.option_id}"
                {cond( not(is_set( $rules[$Options.item.option_id] )) ,'checked="checked"',$rules[$Options.item.option_id][$parent_multioption.multioption_id]|contains($OptionList.item.option_id ),'checked="checked"', true(),'')}/>
{else}
                <input type="checkbox"
                     name="{$attribute_base}_data_multioption_rule_{$Options.item.option_id}_{$parent_multioption.multioption_id}[]" value="{$OptionList.item.option_id}" checked="checked" disabled="disabled" />
                <input type="hidden" name="{$attribute_base}_data_multioption_rule_{$Options.item.option_id}_{$parent_multioption.multioption_id}[]" value="{$OptionList.item.option_id}" >

{/if}
                </td>
            {/section}
        {/section}

    </tr>
{/section}

{if is_set($MultiOptionList.item.child_group)}
    <tr>
        <th>&nbsp;</th>
        <td  {if $MultiOptionList.item.optionlist} colspan="{count($MultiOptionList.item.optionlist)|sum(1)}"{/if} >
        {include uri='design:content/datatype/edit/multioption2/multioption2_rules.tpl' name=ChildGroup attribute=$attribute group=$MultiOptionList.item.child_group parent_group_id=$group.group_id parent_multioption_id=$MultiOptionList.item.id parent_multioption=$MultiOptionList.item depth=sum($depth,1) rules=$rules}
        </td>
    </tr>
{/if}

</table>

{/section}
{/section}