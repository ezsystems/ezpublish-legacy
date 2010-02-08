{def $pending_count = fetch( 'content', 'pending_count' )}

{cache-block keys=array( $user.contentobject_id, $pending_count )}

<h2>{'Your pending items'|i18n( 'design/admin/dashboard/pending_list' )}</h2>

{if $pending_count}

<table class="list" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <th>{'Name'|i18n( 'design/admin/dashboard/pending_list' )}</th>
        <th>{'Type'|i18n( 'design/admin/dashboard/pending_list' )}</th>
        <th>{'Modified'|i18n( 'design/admin/dashboard/pending_list' )}</th>
    </tr>
    {foreach fetch( 'content', 'pending_list', hash( 'limit', $block.number_of_items ) ) as $pending_item sequence array( 'bglight', 'bgdark' ) as $style}
        <tr class="{$style}">
            <td>
                <a href="{concat( '/content/versionview/', $pending_item.contentobject.id, '/', $pending_item.version, '/' )|ezurl('no')}">
                    {$pending_item.version_name|wash()}
                </a>
            </td>
            <td>
                {$pending_item.contentobject.content_class.name|wash()}
            </td>
            <td>
                {$pending_item.modified|l10n('shortdatetime')}
            </td>
        </tr>
    {/foreach}
</table>

{else}

<p>{'Currently you do not have any pending items available.'|i18n( 'design/admin/dashboard/pending_list' )}</p>

{/if}

{/cache-block}
