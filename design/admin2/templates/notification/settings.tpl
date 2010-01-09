{let handlers=fetch( notification, handler_list )}
<form name="notification" method="post" action={'/notification/settings/'|ezurl}>

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h1 class="context-title">{'My notification settings'|i18n( 'design/admin/notification/settings' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">
{section var=Handlers loop=$handlers}
    {section-exclude match=eq( $Handlers.item, $handlers.ezsubtree )}
    {include name=newspace uri=concat( 'design:notification/handler/', $Handlers.item.id_string, '/settings/edit.tpl' ) handler=$Handlers.item}
{/section}
</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
    <input class="button" type="submit" name="Store" value="{'Apply changes'|i18n( 'design/admin/notification/settings' )}" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>

{include name=newspace uri=concat( 'design:notification/handler/', $handlers.ezsubtree.id_string, '/settings/edit.tpl' ) handler=$handlers.ezsubtree view_parameters=$view_parameters}

</form>
{/let}
