<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'url'|icon( 'normal', 'URL'|i18n( 'design/admin/url/view' ) )}&nbsp;{'URL #%url_id'|i18n( 'design/admin/url/view',, hash( '%url_id', $url_object.id ) )}</h1>

{let item_type=ezpreference( 'admin_url_view_limit' )
     number_of_items=min( $item_type, 3)|choose( 10, 10, 25, 50 )
     view_filter_type=ezpreference( 'admin_url_view_filter_type' )
     status_draft='Draft'|i18n( 'design/admin/url/view' )
     status_published='Published'|i18n( 'design/admin/url/view' )
     status_pending='Pending'|i18n( 'design/admin/url/view' )
     status_archived='Archived'|i18n( 'design/admin/url/view' )
     status_rejected='Rejected'|i18n( 'design/admin/url/view' )
     status_in_trash=' (in trash)'|i18n( 'design/admin/url/view' )}

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{* Last modified. *}
<div class="context-information">
<p>
    {'Last modified'|i18n( 'design/admin/url/view' )}:
    {if $url_object.modified|gt( 0 )}
        {$url_object.modified|l10n(shortdatetime)}
    {else}
        {'Unknown'|i18n( 'design/admin/url/view' )}
    {/if}
</p>
</div>

<div class="context-attributes">

{* Address. *}
<div class="block">
    <label>{'Address'|i18n( 'design/admin/url/view' )}:</label>
    <a href="{$full_url}">{$full_url}</a>
</div>

{* Address. *}
<div class="block">
    <label>{'Status'|i18n( 'design/admin/url/view' )}:</label>
    {if $url_object.is_valid}
        {'Valid'|i18n( 'design/admin/url/view' )}
    {else}
        {'Invalid'|i18n( 'design/admin/url/view' )}
    {/if}
</div>

{* Last checked. *}
<div class="block">
    <label>{'Last checked'|i18n( 'design/admin/url/view' )}:</label>
    {if $url_object.last_checked|gt(0)}
        {$url_object.last_checked|l10n( shortdatetime )}
    {else}
        {'This URL has not been checked.'|i18n( 'design/admin/url/view' )}
    {/if}
</div>

</div>

{* DESIGN: Content END *}</div></div></div>


{* Buttons. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
<form method="post" action={concat( 'url/edit/', $url_object.id )|ezurl}>
    <input class="button "type="submit" name="" value="{'Edit'|i18n( 'design/admin/url/view' )}" title="{'Edit this URL.'|i18n( 'design/admin/url/view' )}" />
</form>
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>

<form action={concat( 'url/view/', $url_object.id )|ezurl} method="post">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h2 class="context-title">{'Objects using URL #%url_id [%url_count]'|i18n( 'design/admin/url/view',, hash( '%url_id', $url_object.id, '%url_count', $object_list|count ) )}</h2>



{* Items per page and view mode selector. *}
<div class="context-toolbar">
<div class="block">
<div class="left">
    <p>
    {switch match=$number_of_items}
    {case match=25}
        <a href={'/user/preferences/set/admin_url_view_limit/1'|ezurl}>10</a>
        <span class="current">25</span>
        <a href={'/user/preferences/set/admin_url_view_limit/3'|ezurl}>50</a>

        {/case}

        {case match=50}
        <a href={'/user/preferences/set/admin_url_view_limit/1'|ezurl}>10</a>
        <a href={'/user/preferences/set/admin_url_view_limit/2'|ezurl}>25</a>
        <span class="current">50</span>
        {/case}

        {case}
        <span class="current">10</span>
        <a href={'/user/preferences/set/admin_url_view_limit/2'|ezurl}>25</a>
        <a href={'/user/preferences/set/admin_url_view_limit/3'|ezurl}>50</a>
        {/case}

        {/switch}
    </p>
</div>
<div class="right">
<p>
{switch match=$view_filter_type}
{case match='published'}
<a href={'/user/preferences/set/admin_url_view_filter_type/all'|ezurl}>{'All'|i18n( 'design/admin/url/view' )}</a>
<span class="current">{'Published'|i18n( 'design/admin/url/view' )}</span>
{/case}

{case}
<span class="current">{'All'|i18n( 'design/admin/url/view' )}</span>
<a href={'/user/preferences/set/admin_url_view_filter_type/published'|ezurl}>{'Published'|i18n( 'design/admin/url/view' )}</a>
{/case}
{/switch}
</p>
</div>
<div class="break"></div>
</div>
</div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-content">

{section show=$object_list}

<table class="list" cellspacing="0">

<tr>
    <th>{'Name'|i18n( 'design/admin/url/view' )}</th>
    <th>{'Status'|i18n( 'design/admin/url/view' )}</th>
    <th>{'Version'|i18n( 'design/admin/url/view' )}</th>
    <th class="tight">&nbsp;</th>
</tr>
{section var=Objects loop=$object_list sequence=array( bglight, bgdark )}

{let object_version_status=$Objects.item.contentobject.versions[sub($Objects.item.version,1)].status}

{if or(or(eq($view_filter_type,''),eq($view_filter_type,'all')),and(eq($view_filter_type,'published'),eq($object_version_status,1),eq($Objects.item.contentobject.status,1)))}
<tr class="{$Objects.sequence}">
    <td>{$Objects.item.contentobject.class_identifier|class_icon( 'small', $Objects.item.contentobject.class_identifier )}&nbsp;<a href={concat( '/content/versionview/', $Objects.item.contentobject.id, '/', $Objects.item.version )|ezurl} title="{'View the contents of version #%version_number.'|i18n( 'design/admin/url/view',, hash( '%version_number', $Objects.item.version, ) )}">{$Objects.item.name|wash}</a></td>
    {switch match=$Objects.item.contentobject.status}
    {case match=0}
        <td>{$object_version_status|choose( $status_draft, $status_published, $status_pending, $status_archived, $status_rejected )}</td>
    {/case}

    {case match=2}
        <td>{$object_version_status|choose( concat($status_draft,$status_in_trash), concat($status_published,$status_in_trash), concat($status_pending,$status_in_trash), concat($status_archived,$status_in_trash), concat($status_rejected,$status_in_trash) )}</td>
    {/case}

    {case}
        <td>{$object_version_status|choose( $status_draft, $status_published, $status_pending, $status_archived, $status_rejected )}</td>
    {/case}
    {/switch}
    <td>{$Objects.item.version}</td>
    <td><a href={concat( 'content/edit/', $Objects.item.contentobject_id )|ezurl}><img src={'edit.gif'|ezimage} alt="{'Edit'|i18n( 'design/admin/url/view' )}" title="{'Edit <%object_name>.'|i18n( 'design/admin/url/view',, hash( '%object_name', $Objects.item.name ) )|wash}" /></a></td>
</tr>
{/if}

{/let}

{/section}

</table>

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat( '/url/view/',$url_object.id )
         item_count=$url_view_count
         view_parameters=$view_parameters
         item_limit=$number_of_items}
</div>
{section-else}
<p>{'URL #%url_id is not in use by any objects.'|i18n( 'design/admin/url/view',, hash( '%url_id', $url_object.id ) )}</p>
{/section}

{/let}

{* DESIGN: Content END *}</div></div></div>

</div>

</form>
