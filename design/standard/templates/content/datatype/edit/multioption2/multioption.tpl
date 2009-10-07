{section show=$group.multioption_list}
    <table class="multioption" >
    {section var=MultiOptionList loop=$group.multioption_list sequence=array( bglight, bgdark )}
        <tr>
            <th class="tight"><input type="checkbox" name="{$attribute_base}_data_multioption_remove_{$attribute.id}_{$group.group_id}[]" value="{$MultiOptionList.id}" title="{'Select multioption for removal.'|i18n( 'design/standard/content/datatype' )}" /></th>
            <td>
            <label>{'Multioption:'|i18n( 'design/standard/content/datatype' )}</label>
            <input id="ezcoa-{if ne( $attribute_base, 'ContentObjectAttribute' )}{$attribute_base}-{/if}{$attribute.contentclassattribute_id}_{$attribute.contentclass_attribute_identifier}" class="box ezcc-{$attribute.object.content_class.identifier} ezcca-{$attribute.object.content_class.identifier}_{$attribute.contentclass_attribute_identifier}" type="text" name = "{$attribute_base}_data_multioption_name_{$attribute.id}_{$group.group_id}_{$MultiOptionList.multioption_id}" value="{$MultiOptionList.item.name}" />
            <input type="hidden" name="{$attribute_base}_data_multioption_id_{$attribute.id}_{$group.group_id}[]" value="{$MultiOptionList.multioption_id}" />

            {section show=$MultiOptionList.item.optionlist}

                <table cellspacing="0">
                <tr>
                    <th class="tight">&nbsp;</th>
                    <th>{'Option'|i18n( 'design/standard/content/datatype' )}</th>
                    <th>{'Additional price'|i18n( 'design/standard/content/datatype' )}</th>
                    <th >{'Image'|i18n( 'design/standard/content/datatype' )}</th>
                    <th class="tight">{'Def'|i18n( 'design/standard/content/datatype' )}</th>
                    <th class="tight">{'Dis'|i18n( 'design/standard/content/datatype' )}</th>
                </tr>


                {section var=OptionList loop=$MultiOptionList.item.optionlist sequence=array( bglight, bgdark )}
                    <tr>
                        {* Remove. *}
                        <td><input type="checkbox" name="{$attribute_base}_data_option_remove_{$attribute.id}_{$group.group_id}_{$MultiOptionList.id}[]" value="{$OptionList.id}" title="{'Select option for removal.'|i18n('design/standard/content/datatype')}" /></td>
                        {* Option. *}
                        <td><input class="box" type="text" name="{$attribute_base}_data_option_value_{$attribute.id}_{$group.group_id}_{$MultiOptionList.multioption_id}[]" value="{$OptionList.value}" /></td>
                        {* Value. *}
                        <td><input class="box" type="text" name="{$attribute_base}_data_option_additional_price_{$attribute.id}_{$group.group_id}_{$MultiOptionList.multioption_id}[]" value="{$OptionList.additional_price}" /></td>
                        <td>
                        {if is_set($OptionList.item.object)}
                            {let imgobj=fetch('content','object',hash(object_id,$OptionList.item.object))}
                            <input type="hidden" name="{$attribute_base}_data_option_object_{$attribute.id}_{$group.group_id}_{$MultiOptionList.multioption_id}_{$OptionList.option_id}" value="{$OptionList.item.object}" />
                            {content_view_gui content_object=$imgobj view='tiny' object_parameters=hash(size,"tiny") link_parameters=hash("href",$imgobj.main_node.url_alias, 'target', "_blank")}
                            {/let}
                            <input type="image" src={'trash.png'|ezimage()} name="CustomActionButton[{$attribute.id}_remove-object_{$group.group_id}_{$MultiOptionList.multioption_id}_{$OptionList.index}]" />
                            {else}
                            <input type="image" src={'add.png'|ezimage()} name="CustomActionButton[{$attribute.id}_browse-object_{$group.group_id}_{$MultiOptionList.multioption_id}_{$OptionList.index}]" />
                        {/if}
                        </td>
                        {* Default. *}
                        <td>
                        {if eq( sum( $OptionList.index, 1 ), $MultiOptionList.default_option_id )}
                            <input type="radio" name="{$attribute_base}_data_default_option_{$attribute.id}_{$group.group_id}_{$MultiOptionList.multioption_id}"  value="{$OptionList.id}" title="{'Use the radio buttons to set the default option.'|i18n( 'design/standard/content/datatype' )}" checked="checked" />
                            {else}
                            <input type="radio" name="{$attribute_base}_data_default_option_{$attribute.id}_{$group.group_id}_{$MultiOptionList.multioption_id}"  value="{$OptionList.id}" title="{'Use the radio buttons to set the default option.'|i18n( 'design/standard/content/datatype' )}" />
                        {/if}
                        <input type="hidden" name="{$attribute_base}_data_option_id_{$attribute.id}_{$group.group_id}_{$MultiOptionList.multioption_id}[]" value="{$OptionList.id}" />
                        <input type="hidden" name="{$attribute_base}_data_option_option_id_{$attribute.id}_{$group.group_id}_{$MultiOptionList.multioption_id}[]" value="{$OptionList.option_id}" />
                        </td>
                        {*Not selectable*}
                        <td><input type="checkbox"
                             name="{$attribute_base}_data_option_is_selectable_{$attribute.id}_{$group.group_id}_{$MultiOptionList.multioption_id}_{$OptionList.option_id}"
                             value="{$OptionList.option_id}" title="{'Select if you want to disallow this option choice'|i18n('design/standard/content/datatype')}"
                             {cond( $OptionList.is_selectable|not(), 'checked="checked"', true(),'' )} /></td>
                    </tr>
                {/section}
                </table>
                {section-else}
                <p>{'There are no options.'|i18n( 'design/standard/content/datatype' )}</p>
            {/section}
            <div class="toolbar">
                {if $MultiOptionList.item.optionlist}
                    <input class="button" type="submit" name="CustomActionButton[{$attribute.id}_remove-selected-option_{$group.group_id}_{$MultiOptionList.id}]" value="{'Remove selected'|i18n('design/standard/content/datatype')}" title="{'Remove selected options.'|i18n( 'design/standard/content/datatype' )}" />
                    {else}
                    <input class="button-disabled" type="submit" name="CustomActionButton[{$attribute.id}_remove-selected-option_{$group.id}_{$MultiOptionList.id}]" value="{'Remove selected'|i18n('design/standard/content/datatype')}" disabled="disabled" />
                {/if}
                <input class="button" type="submit" name="CustomActionButton[{$attribute.id}_new-option_{$group.group_id}_{$MultiOptionList.multioption_id}]" value="{'Add option'|i18n('design/standard/content/datatype')}" title="{'Add a new option.'|i18n( 'design/standard/content/datatype' )}" />
            </div>

            {if not(is_set($MultiOptionList.item.child_group))}
                <div class="toolbar">
                    <input class="button" type="submit" name="CustomActionButton[{$attribute.id}_new-sublevel_{$group.group_id}_{$MultiOptionList.id}]" value="{'Add multioption sub level'|i18n('design/standard/content/datatype')}" title="{'Add a new multioption sub level.'|i18n( 'design/standard/content/datatype' )}" />
                </div>
                {else}
                {include uri='design:content/datatype/edit/multioption2/multioption2.tpl' name=ChildGroup attribute=$attribute group=$MultiOptionList.item.child_group parent_group_id=$group.group_id parent_multioption_id=$MultiOptionList.item.id depth=sum($depth,1)}
            {/if}
            </td>
        </tr>
    {/section}
    </table>
    {section-else}
    {if $depth|gt(0)}
        <p>{'There are no multioptions.'|i18n( 'design/standard/content/datatype' )}</p>
    {/if}
{/section}

     <div class="toolbar">
         {if $group.multioption_list}
             <input class="button" type="submit" name="CustomActionButton[{$attribute.id}_remove-selected-multioption_{$group.group_id}]" value="{'Remove multioption'|i18n('design/standard/content/datatype')}" title="{'Remove selected multioptions.'|i18n( 'design/standard/content/datatype' )}" />
             {else}
             <input class="button-disabled" type="submit" name="CustomActionButton[{$attribute.id}_remove-selected-multioption_{$group.group_id}]" value="{'Remove selected'|i18n('design/standard/content/datatype')}" disabled="disabled" />
         {/if}

         <input class="button" type="submit" name="CustomActionButton[{$attribute.id}_new-multioption_{$group.group_id}]" value="{'Add multioption'|i18n('design/standard/content/datatype')}" title="{'Add a new multioption.'|i18n('design/standard/content/datatype')}" />
     </div>
