<form method="post" action={"/notification/settings/"|ezurl}>

<div class="context-block">
<h2 class="context-title">{"Notification settings"|i18n('design/standard/notification')}</h2>

{let handlers=fetch('notification','handler_list')}
    <p>
    {section name=Handlers loop=$handlers}
        {*Handler: {$Handlers:item.name}*}
        {include handler=$Handlers:item uri=concat( "design:notification/handler/",$Handlers:item.id_string,"/settings/edit.tpl")}
        {delimiter}<br/>{/delimiter}
    {/section}
    </p>

{/let}

<div class="controlbar">
<div class="block">
    <input class="button" type="submit" name="Store" value="{'OK'|i18n('design/standard/notification')}" />
    <input class="button" type="submit" name="Cancel" value="{'Cancel'|i18n('design/standard/notification')}" />
</div>
</div>

</div>

</form>
