{default attribute_base=ContentObjectAttribute}
{let class_content=$attribute.contentclass_attribute.content}

{switch match=$class_content.selection_type}

{case match=0} {* Browse *}

    {section show=$attribute.content}
        <p class="box">{content_view_gui view=text_linked content_object=$attribute.content}</p>
    {section-else}
        <p class="box">{"No relation"|i18n("design/standard/content/datatype")}</p>
    {/section}

    <input type="hidden" name="{$attribute_base}_data_object_relation_id_{$attribute.id}" value="{$attribute.data_int}" />
    {section show=$attribute.content}
        <input class="button" type="submit" name="BrowseObjectButton_{$attribute.id}" value="{'Replace object'|i18n('design/standard/content/datatype')}" />
        <input class="button" type="submit" name="RemoveObjectButton_{$attribute.id}" value="{'Remove object'|i18n('design/standard/content/datatype')}" />
    {section-else}
        <input class="button" type="submit" name="BrowseObjectButton_{$attribute.id}" value="{'Find object'|i18n('design/standard/content/datatype')}" />
    {/section}
    <input type="hidden" name="CustomActionButton[{$attribute.id}_set_object_relation]" value="{$attribute.id}" />

{/case}

{case match=1} {* Dropdown list *}
    <div class="buttonblock">
    <select name="{$attribute_base}_data_object_relation_id_{$attribute.id}">
        {section show=$attribute.contentclass_attribute.is_required|not}
            <option value="" {section show=eq( $attribute.data_int, '' )}selected="selected"{/section}>{'No relation'|i18n( 'design/standard/content/datatype' )}</option>
        {/section}
        {let parent_node=fetch( content, node, hash( node_id, $class_content.default_selection_node ) )}
        {section var=node loop=fetch( content, list,
                                      hash( parent_node_id, $parent_node.node_id,
                                            sort_by, $parent_node.sort_array ) )}
            <option value="{$node.contentobject_id}" {section show=eq( $attribute.data_int, $node.contentobject_id )}selected="selected"{/section}>{$node.name|wash}</option>
        {/section}
        {/let}
    </select>

    {section show=$class_content.fuzzy_match}
        <input type="text" name="{$attribute_base}_data_object_relation_fuzzy_match_{$attribute.id}" value="" />
    {/section}
    </div>
{/case}

{case match=2} {* Dropdown tree *}
{/case}

{case/}

{/switch}

{/let}
{/default}
