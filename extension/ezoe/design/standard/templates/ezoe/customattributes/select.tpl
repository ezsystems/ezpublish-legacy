{* Override to support suppression of custom tags *}
<select name="{$custom_attribute}" id="{$custom_attribute_id}_source"{if $custom_attribute_disabled} disabled="disabled"{/if} title="{$custom_attribute_title|wash}" class="{$custom_attribute_classes|implode(' ')}">
{if ezini_hasvariable( $custom_attribute_settings, 'Selection', 'ezoe_attributes.ini' )}
{foreach ezini( $custom_attribute_settings, 'Selection', 'ezoe_attributes.ini' ) as $custom_value => $custom_name}
    <option value="{if $custom_value|ne('-0-')}{$custom_value|wash}{/if}"{if $custom_value|eq( $custom_attribute_default )} selected="selected"{/if}>{$custom_name|wash}</option>
{/foreach}
{elseif and( is_set($custom_attribute_selection), is_array($custom_attribute_selection), $custom_attribute_id|eq('custom_name') )}
    {def $tag_groups=hash()
         $tags_in_group=array()
         $main_tags=array()
         $custom_name=''}

    {if is_unset( $suppress_custom_tags )}
        {def $suppress_custom_tags = ezini( 'CustomTagSettings', 'SuppressedCustomTags', 'content.ini' )}
    {/if}

    {foreach ezini( 'CustomTagSettings', 'CustomTagGroups', 'content.ini' ) as $tag_group_id => $tag_group_name}
        {set $tag_groups=$tag_groups|merge( hash($tag_group_name,  ezini( 'CustomTagSettings', concat( 'CustomTagGroups_', $tag_group_id ), 'content.ini' ) ) )
             $tags_in_group=$tags_in_group|merge(ezini( 'CustomTagSettings', concat( 'CustomTagGroups_', $tag_group_id ), 'content.ini' ))}
    {/foreach}

    {foreach $custom_attribute_selection as $custom_value => $custom_name}
        {if not( $tags_in_group|contains( $custom_value ) )}
            {set $main_tags=$main_tags|append( $custom_value )}
        {/if}
    {/foreach}

    {foreach hash( 'Main', $main_tags )|merge( $tag_groups ) as $group_name => $group_tags}
        <optgroup label="{$group_name|wash}">
        {foreach $group_tags as $custom_value}
            {set $custom_name=$custom_attribute_selection[$custom_value]}
            {if and( $custom_attribute_id|eq( 'custom_name' ), $suppress_custom_tags|contains( $custom_value ) )}
                {continue}
            {/if}
            <option value="{if $custom_value|ne('-0-')}{$custom_value|wash}{/if}"{if $custom_value|eq( $custom_attribute_default )} selected="selected"{/if}>{$custom_name|wash}</option>
        {/foreach}
        </optgroup>
    {/foreach}
{elseif and( is_set($custom_attribute_selection), is_array($custom_attribute_selection) )}
{foreach $custom_attribute_selection as $custom_value => $custom_name}
    <option value="{if $custom_value|ne('-0-')}{$custom_value|wash}{/if}"{if $custom_value|eq( $custom_attribute_default )} selected="selected"{/if}>{$custom_name|wash}</option>
{/foreach}
{/if}
</select>