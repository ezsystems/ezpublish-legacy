{default tag_name    = ''
         attributes  = false()
         classes     = hash()
         attribute_mapping  = hash()
         attribute_defaults = hash()
         attribute_content_prepend = hash()
         attribute_content_append  = hash()
         i18n = hash('align', 'Align'|i18n('design/standard/ezoe'),
                    'size', 'Size'|i18n('design/standard/ezoe'),
                    'view', 'View'|i18n('design/standard/ezoe'),
                    'inline', 'Inline'|i18n('design/standard/ezoe'),
                    'width', 'Width'|i18n('design/standard/ezoe'),
                    'border', 'Border'|i18n('design/standard/ezoe'),
                    'tag', 'Tag'|i18n('design/standard/ezoe'),
                    'class', 'Class'|i18n('design/standard/ezoe'),
                    'id', 'ID'|i18n('design/standard/ezoe'),
                    'href', 'Href'|i18n('design/standard/ezoe'),
                    'target', 'Target'|i18n('design/standard/ezoe'),
                    'title', 'Title'|i18n('design/standard/ezoe')
         )
         attribute_titles = hash(
                    'href', 'The url the link points to, starts with link type (like http://).'|i18n('design/standard/ezoe'),
                    'class', 'Class are often used to give different design or appearance, either by using a different template, style or both.'|i18n('design/standard/ezoe'),
                    'target', 'Lets you specify the target window for the link, if any.'|i18n('design/standard/ezoe'),
                    'title', 'The title on the (x)html tag, used by screen readers, and to give better explanation like this one.'|i18n('design/standard/ezoe'),
                    'width', 'To set the width of the tag, either as percentage by appending % or as pixel size by just using a number.'|i18n('design/standard/ezoe'),
                    'id', 'The unique identifier used for the element in the (x)html output, used by style sheets and/or anchors.'|i18n('design/standard/ezoe')
                    )}
{if and( $:tag_name, $:attributes )}
    {def $attribute_default = ''
         $attribute_id      = ''
         $xml_attribute     = ''
         $attribute_settings = ''
         $attribute_name = ''
         $attribute_type = ''
         $attribute_title = ''
         $attribute_classes = ''
         $attribute_disabled = false()}
    {if ezini_hasvariable( $:tag_name, 'Defaults', 'content.ini' )}
        {set $attribute_defaults   = $attribute_defaults|merge( ezini( $:tag_name, 'Defaults', 'content.ini' ) )}
    {/if}

    <table class="properties general_attributes" id="{$:tag_name}_attributes">
    {foreach $attributes as $attribute => $attribute_value}
        {set $xml_attribute       = first_set( $attribute_mapping[$attribute], $attribute )}
        {set $attribute_id        = concat( $:tag_name, '_', $xml_attribute )|wash}
        {set $attribute_classes    = array()}

        {if ezoe_ini_section( concat('Attribute_', $:tag_name, '_', $xml_attribute), 'ezoe_customattributes.ini' )}
            {set $attribute_settings = concat('Attribute_', $:tag_name, '_', $xml_attribute)}
        {else}
            {set $attribute_settings = concat('Attribute_', $xml_attribute)}
        {/if}

        {if ezini_hasvariable( $attribute_settings, 'Disabled', 'ezoe_customattributes.ini' )}
            {set $attribute_disabled = ezini( $attribute_settings, 'Disabled', 'ezoe_customattributes.ini' )|eq('true')}
        {else}
            {set $attribute_disabled = false()}
        {/if}

        {if is_set( $classes[$xml_attribute] )}
            {set $attribute_classes = array( $classes[$xml_attribute] )}
        {else}
            {set $attribute_classes = array()}
        {/if}

        {if ezini_hasvariable( $attribute_settings, 'Type', 'ezoe_customattributes.ini' )}
            {set $attribute_type = ezini( $attribute_settings, 'Type', 'ezoe_customattributes.ini' )}
        {elseif $attribute_value|is_array()}
            {set $attribute_type = 'select'}
        {elseif $attribute_value}
            {set $attribute_type = $attribute_value}
        {else}
            {set $attribute_type = 'text'}
        {/if}
        
        {if ezini_hasvariable( $attribute_settings, 'Default', 'ezoe_customattributes.ini' )}
            {set $attribute_default = ezini( $attribute_settings, 'Default', 'ezoe_customattributes.ini' )}
        {else}
            {set $attribute_default = first_set( $attribute_defaults[$xml_attribute], '' )}
        {/if}

        {if ezini_hasvariable( $attribute_settings, 'Name', 'ezoe_customattributes.ini' )}
            {set $attribute_name = ezini( $attribute_settings, 'Name', 'ezoe_customattributes.ini' )}
        {elseif is_set( $i18n[ $xml_attribute ] )}
            {set $attribute_name = $i18n[ $xml_attribute ]}
        {else}
            {set $attribute_name = $xml_attribute|upfirst}
        {/if}

        {if ezini_hasvariable( $attribute_settings, 'Title', 'ezoe_customattributes.ini' )}
            {set $attribute_title = ezini( $attribute_settings, 'Title', 'ezoe_customattributes.ini' )}
        {else}
            {set $attribute_title = first_set( $attribute_titles[$xml_attribute], '' )}
        {/if}

        {if ezini_hasvariable( $attribute_settings, 'Required', 'ezoe_customattributes.ini' )}
            {if ezini( $attribute_settings, 'Required', 'ezoe_customattributes.ini' )|eq('true')}
                {set $attribute_classes = $attribute_classes|append( 'required' )}
            {/if}
        {/if}

        {if ezini_hasvariable( $attribute_settings, 'AllowEmpty', 'ezoe_customattributes.ini' )}
            {if ezini( $attribute_settings, 'AllowEmpty', 'ezoe_customattributes.ini' )|eq('true')}
                {set $attribute_classes = $attribute_classes|append( 'allow_empty' )}
            {/if}
        {/if}

        <tr id="{$attribute_id}" class="attribute_type_{$attribute_type}">
            {if $attribute_type|ne('hidden')}
            <td class="column1"><label for="{$attribute_id}_source">
                {$attribute_name|wash}
            </label></td>
            <td>
            {else}
            <td colspan="2">
            {/if}
                {if is_set( $attribute_content_prepend[ $xml_attribute ] )}
                    {$attribute_content_prepend[ $xml_attribute ]}
                {/if}

                {* Reuse custom attribute type templates *}
                {include uri=concat('design:ezoe/customattributes/', $attribute_type, '.tpl')
                         custom_attribute           = $attribute
                         custom_attribute_id        = $attribute_id
                         custom_attribute_default   = $attribute_default
                         custom_attribute_disabled  = $attribute_disabled
                         custom_attribute_classes   = $attribute_classes
                         custom_attribute_type      = $attribute_type
                         custom_attribute_settings  = $attribute_settings
                         custom_attribute_titles    = $attribute_title
                         custom_attribute_selection = $attribute_value}

                {if is_set( $attribute_content_append[$xml_attribute ] )}
                    {$attribute_content_append[$xml_attribute ]}
                {/if}
            </td>
        </tr>
    {/foreach}
    </table>
{/if}
{/default}