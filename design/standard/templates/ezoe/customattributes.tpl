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
    {def $custom_attributes           = array()
         $custom_attributes_defaults  = array()
         $custom_attributes_names     = array()
         $custom_attributes_types     = array()
         $custom_attribute_default    = 0
         $custom_attribute_settings   = ''
         $custom_attribute_id         = ''
         $custom_attribute_type       = ''
         $custom_attribute_disabled   = false()
         $custom_attribute_classes    = array()
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
    {if ezini_hasvariable( $:tag_name, 'CustomAttributesType', 'content.ini' )}
        {set $custom_attributes_types = ezini( $:tag_name, 'CustomAttributesType', 'content.ini' )}
    {/if}

    <table class="properties custom_attributes" id="{$:tag_name}_customattributes"{if $:hide} style="display: none;"{/if}>
    {foreach $custom_attributes as $custom_attribute}
        {if $shown_attributes|contains( $custom_attribute )}{continue}{/if}
        {set $shown_attributes           = $shown_attributes|append( $custom_attribute )}
        {set $custom_attribute_id        = concat( $:tag_name, '_', $custom_attribute)|wash}
        {if ezoe_ini_section( concat('CustomAttribute_', $:tag_name, '_', $custom_attribute), 'content.ini' )}
            {set $custom_attribute_settings = concat('CustomAttribute_', $:tag_name, '_', $custom_attribute)}
        {else}
            {set $custom_attribute_settings = concat('CustomAttribute_', $custom_attribute)}
        {/if}
	    {if ezini_hasvariable( $custom_attribute_settings, 'Disabled', 'content.ini' )}
	        {set $custom_attribute_disabled = ezini( $custom_attribute_settings, 'Disabled', 'content.ini' )|eq('true')}
	    {else}
            {set $custom_attribute_disabled = false()}
	    {/if}

	    {set $custom_attribute_type    = first_set( $custom_attributes_types[$custom_attribute], 'text' )}
        {set $custom_attribute_default = first_set( $custom_attributes_defaults[$custom_attribute], '' )}
        <tr id="{$custom_attribute_id}">
            <td class="column1"><label for="{$custom_attribute_id}_source">
            {if is_set( $custom_attributes_names[$custom_attribute] )}
                {$custom_attributes_names[$custom_attribute]|wash}
            {elseif is_set( $i18n[ $custom_attribute ] )}
                {$i18n[ $custom_attribute ]|wash}
            {else}
                {$custom_attribute|upfirst|wash}
            {/if}
            </label></td>
            <td>

            {if $custom_attribute_type|eq('select')}
                <select name="{$custom_attribute}" id="{$custom_attribute_id}_source"{if $custom_attribute_disabled} disabled="disabled"{/if}>
                {foreach ezini( $custom_attribute_settings, 'Selection', 'content.ini' ) as $custom_value => $custom_name}
                    <option value="{$custom_value|wash}"{if $custom_value|eq( $custom_attribute_default )} selected="selected"{/if}>{$custom_name|wash}</option>
                {/foreach}
                </select>
            {elseif $custom_attribute_type|eq('hidden')}
                <input type="hidden" name="{$custom_attribute}" id="{$custom_attribute_id}_source" value="{$custom_attribute_default|wash}"{if $custom_attribute_disabled} disabled="disabled"{/if} />
			{elseif $custom_attribute_type|eq('checkbox')}
                <input type="checkbox" name="{$custom_attribute}" id="{$custom_attribute_id}_source" value="{$custom_attribute_default|wash}"{if $custom_attribute_disabled} disabled="disabled"{/if} />
            {else}
                {if $custom_attribute_type|eq('email')}
                    {set $custom_attribute_classes = $custom_attribute_classes|append('email')}
                {elseif or( $custom_attribute_type|eq('int'), $custom_attribute_type|eq('number') )}
                    {set $custom_attribute_classes = $custom_attribute_classes|append( $custom_attribute_type )}
                    {if ezini_hasvariable( $custom_attribute_settings, 'Minimum', 'content.ini' )}
                        {set $custom_attribute_classes = $custom_attribute_classes|append( concat('min', ezini($custom_attribute_settings, 'Minimum', 'content.ini') ) )}
                    {/if}
                    {if ezini_hasvariable( $custom_attribute_settings, 'Maximum', 'content.ini' )}
                        {set $custom_attribute_classes = $custom_attribute_classes|append( concat('max', ezini($custom_attribute_settings, 'Maximum', 'content.ini') ) )}
                    {/if}
                {/if}
                {if ezini_hasvariable( $custom_attribute_settings, 'Required', 'content.ini' )}
                    {if ezini( $custom_attribute_settings, 'Required', 'content.ini' )|eq('true')}
                        {set $custom_attribute_classes = $custom_attribute_classes|append( 'required' )}
                    {/if}
                {/if}
                <input type="text" name="{$custom_attribute}" id="{$custom_attribute_id}_source" value="{$custom_attribute_default|wash}"{if $custom_attribute_disabled} disabled="disabled"{/if} class="{$custom_attribute_classes|implode(' ')}" />
            {/if}
            </td>
        </tr>
    {/foreach}
    {if $extra_attribute}
        {set $custom_attribute_id  = concat( $:tag_name, '_', $extra_attribute.0)|wash}
        <tr id="{$custom_attribute_id}">
            <td class="column1">
                <label for="{$custom_attribute_id}_source">{$extra_attribute.0|upfirst|wash}</label>
            </td>
            <td><input type="checkbox" name="{$extra_attribute.0}" id="{$custom_attribute_id}_source" value="{$extra_attribute.1|wash}"{if $extra_attribute.2} checked="checked"{/if} disabled="disabled" class="mceItemSkip" /></td>
        </tr>
    {/if}
    </table>
{/if}
{/default}