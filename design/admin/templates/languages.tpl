<div class="context-block">
<h2 class="context-title">{'Languages [%translations]'|i18n( 'design/admin/node/view/full',, hash( '%translations', $node.object.current.language_list|count ) )}</h2>

<table class="list" cellspacing="0">
<tr>
    <th>{'Language'|i18n( 'design/admin/node/view/full' )}</th>
    <th>{'Locale'|i18n( 'design/admin/node/view/full' )}</th>
{*    <th class="tight">&nbsp;</th> *}
</tr>

{section var=Translations loop=$node.object.current.language_list sequence=array( bglight, bgdark )}
<tr class="{$Translations.sequence}">
<td><a href={concat( '/content/view/full/', $node.node_id, '/language/', $Translations.item.language_code )|ezurl}>{$Translations.item.locale.intl_language_name}</a>
</td>
<td>{$Translations.item.language_code}</td>
{* <td><a href={concat( 'content/edit/', $Nodes.item.contentobject_id, '/', $Translations.item.language_code )|ezurl}><img src={'edit.png'|ezimage} alt="{'Edit'|i18n( 'design/admin/node/view/full' )}" /></a> *}

</tr>
{/section}
</table>
</div>
