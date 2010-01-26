{def $user_hash = concat( $user.role_id_list|implode( ',' ), ',', $user.limited_assignment_value_list|implode( ',' ) )}

{cache-block keys=array( $user_hash )}

{def $all_latest_content = fetch( 'content', 'tree', hash( 'parent_node_id', ezini( 'NodeSettings', 'RootNode', 'content.ini' ),
                                                           'limit', $block.number_of_items,
                                                           'sort_by', array( 'published', false() ) ) )}

<h2>{'All latest content'|i18n( 'design/admin/dashboard/all_latest_content' )}</h2>

{if $all_latest_content}

<table class="list" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <th>{'Name'|i18n( 'design/admin/dashboard/all_latest_content' )}</th>
        <th>{'Type'|i18n( 'design/admin/dashboard/all_latest_content' )}</th>
        <th>{'Published'|i18n( 'design/admin/dashboard/all_latest_content' )}</th>
        <th>{'Author'|i18n( 'design/admin/dashboard/all_latest_content' )}</th>
        <th class="tight"></th>
    </tr>
    {foreach $all_latest_content as $latest_node sequence array( 'bglight', 'bgdark' ) as $style}
        <tr class="{$style}">
            <td>
                <a href="{$latest_node.url_alias|ezurl('no')}" title="{$latest_node.name|wash()}">{$latest_node.name|wash()|shorten('20')}</a>
            </td>
            <td>
                {$latest_node.class_name|wash()}
            </td>
            <td>
                {$latest_node.object.published|l10n('shortdate')}
            </td>
            <td>
                <a href="{$latest_node.object.owner.main_node.url_alias|ezurl('no')}" title="{$latest_node.object.owner.name|wash()}">
                    {$latest_node.object.owner.name|wash()|shorten('12')}
                </a>
            </td>
            <td>
            {if $latest_node.can_edit}
                <a href="{concat( 'content/edit/', $latest_node.contentobject_id )|ezurl('no')}">
                    <img src={'edit.gif'|ezimage} alt="{'Edit'|i18n( 'design/admin/dashboard/all_latest_content' )}" title="{'Edit <%child_name>.'|i18n( 'design/admin/dashboard/all_latest_content',, hash( '%child_name', $latest_node.name) )|wash}" />
                </a>
            {else}
                <img src="{'edit-disabled.gif'|ezimage('no')}" alt="{'Edit'|i18n( 'design/admin/dashboard/all_latest_content' )}" title="{'You do not have permission to edit <%child_name>.'|i18n( 'design/admin/dashboard/all_latest_content',, hash( '%child_name', $child_name ) )|wash}" />
            {/if}
            </td>
        </tr>
    {/foreach}
</table>

{else}

<p>{'Latest content list is empty.'|i18n( 'design/admin/dashboard/all_latest_content' )}</p>

{/if}

{undef $all_latest_content}

{/cache-block}

{undef $user_hash}