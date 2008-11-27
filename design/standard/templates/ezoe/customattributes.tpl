{default tag_name = ''
         hide     = false()
         extra_attribute = false()
         i18n = hash('align',     'Align'|i18n('design/standard/ezoe'),
                     'alignment', 'Alignment'|i18n('design/standard/ezoe'),
                     'size',      'Size'|i18n('design/standard/ezoe'),
                     'view',      'View'|i18n('design/standard/ezoe'),
                     'inline',    'Inline'|i18n('design/standard/ezoe'),
                     'class',     'Class'|i18n('design/standard/ezoe'),
                     'name',      'Name'|i18n('design/standard/ezoe'),
                     'author',    'Author'|i18n('design/standard/ezoe'),
                     'title',     'Title'|i18n('design/standard/ezoe'),
                     'offset',    'Offset'|i18n('design/standard/ezoe'),
                     'limit',     'Limit'|i18n('design/standard/ezoe'),
                     'id',        'ID'|i18n('design/standard/ezoe'),
                     'href',      'Href'|i18n('design/standard/ezoe'),
                     'target',    'Target'|i18n('design/standard/ezoe')
         )}
{if $:tag_name}
    {def $custom_attribute_default    = ''
         $custom_attribute_settings   = ''
         $custom_attribute_id         = ''
         $custom_attribute_name       = ''
         $custom_attribute_type       = ''
         $custom_attribute_disabled   = false()
         $custom_attribute_classes    = 0
         $shown_attributes            = array()}
    {if and( is_unset( $custom_attributes ), ezini_hasvariable( $:tag_name, 'CustomAttributes', 'content.ini' ))}
        {def $custom_attributes = ezini( $:tag_name, 'CustomAttributes', 'content.ini' )}
    {/if}
    {if and( is_unset( $custom_attributes_defaults ), ezini_hasvariable( $:tag_name, 'CustomAttributesDefaults', 'content.ini' ))}
        {def $custom_attributes_defaults = ezini( $:tag_name, 'CustomAttributesDefaults', 'content.ini' )}
    {/if}

    <table class="properties custom_attributes" id="{$:tag_name}_customattributes"{if $:hide} style="display: none;"{/if}>
    {foreach $custom_attributes as $custom_attribute}
        {if $shown_attributes|contains( $custom_attribute )}{continue}{/if}

        {set $shown_attributes           = $shown_attributes|append( $custom_attribute )}
        {set $custom_attribute_id        = concat( $:tag_name, '_', $custom_attribute)|wash}
        {set $custom_attribute_classes    = array()}

        {if ezoe_ini_section( concat('CustomAttribute_', $:tag_name, '_', $custom_attribute), 'ezoe_customattributes.ini' )}
            {set $custom_attribute_settings = concat('CustomAttribute_', $:tag_name, '_', $custom_attribute)}
        {else}
            {set $custom_attribute_settings = concat('CustomAttribute_', $custom_attribute)}
        {/if}

        {if ezini_hasvariable( $custom_attribute_settings, 'Disabled', 'ezoe_customattributes.ini' )}
            {set $custom_attribute_disabled = ezini( $custom_attribute_settings, 'Disabled', 'ezoe_customattributes.ini' )|eq('true')}
        {else}
            {set $custom_attribute_disabled = false()}
        {/if}

        {if ezini_hasvariable( $custom_attribute_settings, 'Type', 'ezoe_customattributes.ini' )}
            {set $custom_attribute_type = ezini( $custom_attribute_settings, 'Type', 'ezoe_customattributes.ini' )}
        {else}
            {set $custom_attribute_type = 'text'}
        {/if}
        
        {if ezini_hasvariable( $custom_attribute_settings, 'Default', 'ezoe_customattributes.ini' )}
            {set $custom_attribute_default = ezini( $custom_attribute_settings, 'Default', 'ezoe_customattributes.ini' )}
        {else}
            {set $custom_attribute_default = first_set( $custom_attributes_defaults[$custom_attribute], '' )}
        {/if}

        {if ezini_hasvariable( $custom_attribute_settings, 'Name', 'ezoe_customattributes.ini' )}
            {set $custom_attribute_name = ezini( $custom_attribute_settings, 'Name', 'ezoe_customattributes.ini' )}
        {elseif is_set( $i18n[ $custom_attribute ] )}
            {set $custom_attribute_name = $i18n[ $custom_attribute ]}
        {else}
            {set $custom_attribute_name = $custom_attribute|upfirst}
        {/if}

		{if ezini_hasvariable( $custom_attribute_settings, 'Required', 'ezoe_customattributes.ini' )}
		    {if ezini( $custom_attribute_settings, 'Required', 'ezoe_customattributes.ini' )|eq('true')}
		        {set $custom_attribute_classes = $custom_attribute_classes|append( 'required' )}
		    {/if}
		{/if}

        {if ezini_hasvariable( $custom_attribute_settings, 'AllowEmpty', 'ezoe_customattributes.ini' )}
            {if ezini( $custom_attribute_settings, 'AllowEmpty', 'ezoe_customattributes.ini' )|eq('true')}
                {set $custom_attribute_classes = $custom_attribute_classes|append( 'allow_empty' )}
            {/if}
        {/if}

        <tr id="{$custom_attribute_id}" class="custom_attribute_type_{$custom_attribute_type}">
            <td class="column1"><label for="{$custom_attribute_id}_source">
                {$custom_attribute_name|wash}
            </label></td>
            <td>
                {include uri=concat('design:ezoe/customattributes/', $custom_attribute_type, '.tpl')}
            </td>
        </tr>
    {/foreach}
    {if $extra_attribute}
        {set $custom_attribute_id  = concat( $:tag_name, '_', $extra_attribute.0)|wash}
        <tr id="{$custom_attribute_id}">
            <td class="column1">
                <label for="{$custom_attribute_id}_source">{$extra_attribute.0|upfirst|wash}</label>
            </td>
            <td><input type="checkbox" class="input_noborder" name="{$extra_attribute.0}" id="{$custom_attribute_id}_source" value="{$extra_attribute.1|wash}"{if $extra_attribute.2} checked="checked"{/if} disabled="disabled" class="mceItemSkip" /></td>
        </tr>
    {/if}
    </table>
{/if}
{/default}