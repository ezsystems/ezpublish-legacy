<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'url'|icon( 'normal', 'URL'|i18n( 'design/admin/url/view' ) )}&nbsp;{'URL #%url_id'|i18n( 'design/admin/url/view',, hash( '%url_id', $url_object.id ) )}</h1>

{let item_type=ezpreference( 'admin_url_view_limit' )
     number_of_items=min( $item_type, 3)|choose( 10, 10, 25, 50 )}

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

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
    <label>{'Address'|i18n( 'design/admin/url/view' )}:</label>
    <a href="{$full_url}">{$full_url}</a>
</div>

{* Address. *}
<div class="block">
    <label>{'Status'|i18n( 'design/admin/url/view' )}:</label>
    {section show=$url_object.is_valid}
        {'Valid'|i18n( 'design/admin/url/view' )}
    {section-else}
        {'Invalid'|i18n( 'design/admin/url/view' )}
    {/section}
</div>

{* Last checked. *}
<div class="block">
    <label>{'Last checked'|i18n( 'design/admin/url/view' )}:</label>
    {section show=$url_object.last_checked|gt(0)}
        {$url_object.last_checked|l10n( shortdatetime )}
    {section-else}
        {'This URL has not been checked.'|i18n( 'design/admin/url/view' )}
    {/section}
</div>

</div>

{* DESIGN: Content END *}</div></div></div>


{* Buttons. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<form method="post" action={concat( 'url/edit/', $url_object.id )|ezurl}>
    <input class="button "type="submit" name="" value="{'Edit'|i18n( 'design/admin/url/view' )}" title="{'Edit this URL.'|i18n( 'design/admin/url/view' )}" />
</form>
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

<form action={concat( 'url/view/', $url_object.id )|ezurl} method="post">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'Objects using URL #%url_id [%url_count]'|i18n( 'design/admin/url/view',, hash( '%url_id', $url_object.id, '%url_count', $object_list|count ) )}</h2>

{* DESIGN: Subline *}<div class="header-subline"></div>

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
</div>
<div class="break"></div>
</div>
</div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{section show=$object_list}

<table class="list" cellspacing="0">

<tr>
    <th>{'Name'|i18n( 'design/admin/url/view' )}</th>
    <th>{'Version'|i18n( 'design/admin/url/view' )}</th>
    <th class="tight">&nbsp;</th>
</tr>
{section var=Objects loop=$object_list sequence=array( bglight, bgdark )}

<tr class="{$Objects.sequence}">
    <td>{$Objects.item.contentobject.class_identifier|class_icon( 'small', $Objects.item.contentobject.class_identifier )}&nbsp;{$Objects.item.name}</td>
    <td>{$Objects.item.version}</td>
    <td><a href={concat( 'content/edit/', $Objects.item.contentobject_id )|ezurl}><img src={'edit.png'|ezimage} alt="{'Edit'|i18n( 'design/admin/url/view' )}" title="{'Edit <%object_name>.'|i18n( 'design/admin/url/view',, hash( '%object_name', $Objects.item.name ) )|wash}" /></a></td>
</tr>

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

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>

</form>
