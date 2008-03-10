{default tag_name = ''
         hide     = false()
         extra_attribute = false()}
{if $:tag_name}
    {def $custom_attributes           = array()
         $custom_attributes_defaults  = array()
         $custom_attributes_names     = array()
         $custom_attribute_default    = 0
         $custom_attribute_list_name  = ''
         $custom_attribute_id         = ''
         $shown_attributes            = array()}
    {if ezini_hasvariable( $:tag_name, 'CustomAttributes', 'content.ini' )}
        {set $custom_attributes = ezini( $:tag_name, 'CustomAttributes', 'content.ini' )}
    {/if}
    {if ezini_hasvariable( $:tag_name, 'CustomAttributesDefaults', 'content.ini' )}
        {set $custom_attributes_defaults = ezini( $:tag_name, 'CustomAttributesDefaults', 'content.ini' )}
    {/if}
    {if ezini_hasvariable( $:tag_name, 'CustomAttributesNames', 'content.ini' )}
        {set $custom_attributes_names = ezini( $:tag_name, 'CustomAttributesNames', 'content.ini' )}
    {/if}
    <table class="properties custom_attributes" id="{$:tag_name}_customattributes"{if $:hide} style="display: none;"{/if}>
    {foreach $custom_attributes as $custom_attribute}
        {if $shown_attributes|contains( $custom_attribute )}{continue}{/if}
        {set $shown_attributes           = $shown_attributes|append( $custom_attribute )}
        {set $custom_attribute_id        = concat( $:tag_name, '_', $custom_attribute)|wash}
        {set $custom_attribute_list_name = concat('CustomAttribute', $custom_attribute|upfirst, 'Selections')}
        {set $custom_attribute_default   = first_set( $custom_attributes_defaults[$custom_attribute], '' )}
        <tr id="{$custom_attribute_id}">
            <td class="column1"><label for="{$custom_attribute_id}_source">
            {if is_set( $custom_attributes_names[$custom_attribute] )}
                {$custom_attributes_names[$custom_attribute]|wash}
            {else}
                {$custom_attribute|upfirst|wash|i18n('design/standard/ezoe')}
            {/if}
            </label></td>
            <td>
            {if ezini_hasvariable( $:tag_name, $custom_attribute_list_name, 'content.ini' )}
                <select name="{$custom_attribute_id}_source" id="{$custom_attribute_id}_source">
                {foreach ezini( $:tag_name, $custom_attribute_list_name, 'content.ini' ) as $custom_value => $custom_name}
                    <option value="{$custom_value|wash}"{if $custom_value|eq( $custom_attribute_default )} selected="selected"{/if}>{$custom_name|wash}</option>
                {/foreach}
                </select>
            {else}
                <input type="text" name="{$custom_attribute_id}_source" id="{$custom_attribute_id}_source" value="{$custom_attribute_default|wash}" />
            {/if}
            </td>
        </tr>
    {/foreach}
    {if $extra_attribute}
        {set $custom_attribute_id  = concat( $:tag_name, '_', $extra_attribute.0)|wash}
        <tr id="{$custom_attribute_id}">
            <td class="column1">
                <label for="{$custom_attribute_id}_source">{$extra_attribute.0|upfirst|wash|i18n('design/standard/ezoe')}</label>
            </td>
            <td><input type="checkbox" name="{$custom_attribute_id}_source" id="{$custom_attribute_id}_source" value="{$extra_attribute.1|wash}"{if $extra_attribute.2} checked="checked"{/if} disabled="disabled" /></td>
        </tr>
    {/if}
    </table>
{/if}
{/default}