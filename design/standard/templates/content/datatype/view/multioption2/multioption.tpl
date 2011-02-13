<ul>
{section var=MultiOptionList loop=$group.multioption_list}
    <li>
        <label for="{$attribute.id}_{$group.group_id}_{$MultiOptionList.item.multioption_id}">{$MultiOptionList.item.name}:</label>
{def $default_option_id=0}
{section show=$MultiOptionList.item.default_option_id|gt(0)}
    {set $default_option_id=$MultiOptionList.item.default_option_id}
{section-else}
    {section var=Option loop=$MultiOptionList.item.optionlist}
         {if eq($default_option_id,0)|and(eq($Option.item.is_selectable,1))}
             {set $default_option_id=sum( $Option.index, 1 )}
         {/if}
    {/section}
{/section}
        {section show=$MultiOptionList.item.imageoption|not()}
            <select name="eZOption[{$attribute.id}][{$MultiOptionList.item.multioption_id}]" id="{$attribute.id}_{$group.group_id}_{$MultiOptionList.item.multioption_id}" onchange="ezmultioption_check_option( this, rules{$attribute.id}, {$attribute.id} );">
            {section var=Option loop=$MultiOptionList.item.optionlist}
                <option value="{$Option.item.option_id}" id="{$attribute.id}_{$Option.item.option_id}"
                {cond(eq( sum( $Option.index, 1 ), $default_option_id), 'selected="selected"',true(),'')}
                {cond(not(eq($Option.item.is_selectable, 1 )),'disabled="disabled"', true(),'')} >
                {$Option.item.value}{cond(ne( $Option.item.additional_price, '' ),$Option.item.additional_price|l10n( currency )|prepend('-'), true(),'')}</option>
            {/section}
            </select>
         {section-else}
          <table>
           {section var=Option loop=$MultiOptionList.item.optionlist}
            <tr>
              <td> <input type="radio" value="{$Option.item.option_id}" name="eZOption[{$attribute.id}][{$MultiOptionList.item.multioption_id}]"
              id="{$attribute.id}_{$group.group_id}_{$MultiOptionList.item.multioption_id}"
              {cond(eq( sum( $Option.index, 1 ), $default_option_id ), 'checked="checked"',true(),'')}
              onchange="ezmultioption_check_option( this, rules{$attribute.id}, {$attribute.id} );"
              {cond(not(eq($Option.item.is_selectable, 1 )),'disabled="disabled"', true(),'')}  />
              </td>
              <td  id="td-{$attribute.id}_{$Option.item.option_id}" >{$Option.item.value}{cond(ne( $Option.item.additional_price, '' ),$Option.item.additional_price|l10n( currency )|prepend('-'), true(),'')}</td>
              <td>
              {if is_set($Option.item.object)}
                {let imgobj=fetch('content','object',hash(object_id,$Option.item.object))}
                {content_view_gui content_object=$imgobj view='tiny' object_parameters=hash(size,"tiny") link_parameters=hash("href",$imgobj.main_node.url_alias, 'target', "_blank")}
                {/let}
              {/if}
            </td>
          </tr>
         {/section}
       </table>
{/section}

{if is_set($MultiOptionList.item.child_group)}
    {include uri='design:content/datatype/view/multioption2/multioption2.tpl' name=ChildGroup attribute=$attribute group=$MultiOptionList.item.child_group parent_group_id=$group.group_id parent_multioption_id=$MultiOptionList.item.id depth=sum($depth,1)}
{/if}
</li>
{undef $default_option_id}
{/section}
</ul>