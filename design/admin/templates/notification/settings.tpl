{let handlers=fetch( 'notification', 'handler_list' )}
<form method="post" action={'/notification/settings/'|ezurl}>

<div class="context-block">
<h2 class="context-title">{'Notification settings'|i18n( 'design/admin/notification/settings' )}</h2>

<div class="context-attributes">
{section var=Handlers loop=$handlers}
    {section-exclude match=eq( $Handlers.item, $handlers.ezsubtree )}
    {include name=newspace uri=concat( 'design:notification/handler/', $Handlers.item.id_string, '/settings/edit.tpl' ) handler=$Handlers.item}
{/section}
</div>

<div class="controlbar">
    <div class="block">
        <input class="button" type="submit" name="Store" value="{'Apply changes'|i18n( 'design/admin/notification/settings' )}" />
    </div>
</div>

</div>

{include name=newspace uri=concat( 'design:notification/handler/', $handlers.ezsubtree.id_string, '/settings/edit.tpl' ) handler=$handlers.ezsubtree}

</form>
{/let}
