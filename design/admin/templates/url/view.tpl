<div class="context-block">
<h2 class="context-title">{'url'|icon( 'normal', 'URL'|i18n( 'design/admin/url/list' ) )}&nbsp;{'URL #%url_id'|i18n( 'design/admin/url/view',, hash( '%url_id', $url_object.id ) )}</h2>

{* Last modified. *}
<div class="context-information">
<p>
    {'Last modified'|i18n( 'design/admin/url/view' )}:
    {section show=$url_object.modified|gt( 0 )}
        {$url_object.modified|l10n(shortdatetime)}
    {section-else}
        {'Unknown'|i18n( 'design/admin/url/view' )}
    {/section}
</p>
</div>

<div class="context-attributes">

{* Address. *}
<div class="block">
    <label>{'Address'|i18n( 'design/admin/url/view' )}</label>
    <a href="{$full_url}">{$full_url}</a>
</div>

{* Address. *}
<div class="block">
    <label>{'Status'|i18n( 'design/admin/url/view' )}</label>
    {section show=$url_object.is_valid}
        {'Valid'|i18n( 'design/admin/url/view' )}
    {section-else}
        {'Invalid'|i18n( 'design/admin/url/view' )}
    {/section}
</div>

{* Last checked. *}
<div class="block">
    <label>{'Last checked'|i18n( 'design/admin/url/view' )}</label>
    {section show=$url_object.last_checked|gt(0)}
        {$url_object.last_checked|l10n( shortdatetime )}
    {section-else}
        {'This URL has not been checked.'|i18n( 'design/admin/url/view' )}
    {/section}
</div>

</div>

{* Buttons. *}
<div class="controlbar">
<div class="block">
<form action={concat( 'url/edit/', $url_object.id )|ezurl} method="post">
    <input class="button "type="submit" name="" value="{'Edit'|i18n( 'design/admin/url/view' )}" />
</form>
</div>
</div>

</div>

<form action={concat( 'url/view/', $url_object.id )|ezurl} method="post">

<div class="context-block">
<h2 class="context-title">{'Objects using URL #%url_id [%url_count]'|i18n( 'design/admin/url/view',, hash( '%url_id', $url_object.id, '%url_count', $object_list|count ) )}</h2>

{section show=$object_list}

<table class="list" cellspacing="0">

<tr>
    <th>{'Name'|i18n( 'design/admin/url/view' )}</th>
    <th>{'Version'|i18n( 'design/admin/url/view' )}</th>
    <th>&nbsp;</th>
</tr>
{section var=Objects loop=$object_list sequence=array( bglight, bgdark )}

<tr class="{$Objects.sequence}">
    <td>{$Objects.item.name}</td>
    <td>{$Objects.item.version}</td>
    <td><a href={concat( 'content/edit/', $Objects.item.contentobject_id )|ezurl}><img src={'edit.png'|ezimage} alt="{'Edit'|i18n( 'design/admin/url/view' )}"></a></td>
</tr>
{/section}

</table>

{section-else}
<p>{'URL #%url_id is not in use by any objects.'|i18n( 'design/admin/url/view',, hash( '%url_id', $url_object.id ) )}</p>
{/section}

</div>

</form>
