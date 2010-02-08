{def $draft_count = fetch( 'content', 'draft_count' )}

{cache-block keys=array( $user.contentobject_id, $draft_count )}

<h2>{'My drafts'|i18n( 'design/admin/dashboard/drafts' )}</h2>

{if $draft_count}

<table class="list" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <th>{'Name'|i18n( 'design/admin/dashboard/drafts' )}</th>
        <th>{'Type'|i18n( 'design/admin/dashboard/drafts' )}</th>
        <th>{'Version'|i18n( 'design/admin/dashboard/drafts' )}</th>
        <th>{'Modified'|i18n( 'design/admin/dashboard/drafts' )}</th>
        <th class="tight"></th>
    </tr>
    {foreach fetch( 'content', 'draft_version_list', hash( 'limit', $block.number_of_items ) ) as $draft sequence array( 'bglight', 'bgdark' ) as $style}
        <tr class="{$style}">
            <td>
                <a href="{concat( '/content/versionview/', $draft.contentobject.id, '/', $draft.version, '/', $draft.initial_language.locale, '/' )|ezurl('no')}" title="{$draft.name|wash()}">
                    {$draft.name|wash()}
                </a>
            </td>
            <td>
                {$draft.contentobject.class_name|wash()}
            </td>
            <td>
                {$draft.version}
            </td>
            <td>
                {$draft.modified|l10n('shortdatetime')}
            </td>
            <td>
                <a href="{concat( '/content/edit/', $draft.contentobject.id, '/', $draft.version )|ezurl('no')}" title="{'Edit <%draft_name>.'|i18n( 'design/admin/dashboard/drafts',, hash( '%draft_name', $draft.name ) )|wash()}">
                    <img src={'edit.gif'|ezimage} border="0" alt="{'Edit'|i18n( 'design/admin/dashboard/drafts' )}" />
                </a>
            </td>
        </tr>
    {/foreach}
</table>

{else}

{'Currently you do not have any drafts available.'|i18n( 'design/admin/dashboard/drafts' )}

{/if}

{/cache-block}

{undef $draft_count}
