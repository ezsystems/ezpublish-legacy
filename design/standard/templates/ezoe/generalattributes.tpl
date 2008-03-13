{default tag_name    = ''
         attributes  = false()
         description = hash()
         classes     = hash()
         attribute_mapping  = hash()
         attribute_defaults = hash()
         attribute_content_prepend = hash()
         attribute_content_append  = hash()}
{if and( $:tag_name, $:attributes )}
    {def $attribute_default = ''
         $attribute_id      = ''
         $xml_attribute     = ''}
    {if ezini_hasvariable( $:tag_name, 'Defaults', 'content.ini' )}
        {set $attribute_defaults   = $attribute_defaults|merge( ezini( $:tag_name, 'Defaults', 'content.ini' ) )}
    {/if}

    <table class="properties general_attributes" id="{$:tag_name}_attributes">
    {foreach $attributes as $attribute => $attribute_value}
        {set $xml_attribute       = first_set( $attribute_mapping[$attribute], $attribute )}
        {set $attribute_id        = concat( $:tag_name, '_', $attribute )|wash}
        {set $attribute_default   = first_set( $attribute_defaults[$xml_attribute], '' )}
        {if $attribute_value|eq('hidden')}
        <tr id="{$attribute_id}">
            <td colspan="2"><input type="hidden" name="{$attribute}" id="{$attribute_id}_source" value="{$attribute_default|wash}" /></td>
        </tr>
        {else}
        <tr id="{$attribute_id}">
            <td class="column1"><label for="{$attribute_id}_source">
            {if is_set( $description[ $attribute ] )}
                {$description[ $attribute ]|wash}
            {else}
                {$attribute|upfirst|wash|i18n('design/standard/ezoe')}
            {/if}
            </label></td>
            <td>
            {if is_set( $attribute_content_prepend[$xml_attribute ] )}
                {$attribute_content_prepend[$xml_attribute ]}
            {/if}
            {if $attribute_value|is_array()}
                <select name="{$attribute}" id="{$attribute_id}_source" class="{first_set( $classes[$xml_attribute], '' )}">
                {foreach $attribute_value as $value => $name}
                    <option value="{if and( $value, $value|ne('0') )}{$value|wash}{/if}"{if $value|eq( $attribute_default )} selected="selected"{/if}>{$name|wash}</option>
                {/foreach}
                </select>
            {else}
                <input type="text" name="{$attribute}" id="{$attribute_id}_source" value="{$attribute_default|wash}" class="{first_set( $classes[$xml_attribute], '' )}" />
            {/if}
            {if is_set( $attribute_content_append[$xml_attribute ] )}
                {$attribute_content_append[$xml_attribute ]}
            {/if}
            </td>
        </tr>
        {/if}
    {/foreach}
    </table>
{/if}
{/default}