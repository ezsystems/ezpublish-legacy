
<form method="post" action={"/notification/settings/"|ezurl}>

Handlers <br/>
{let handlers=fetch('notification','handler_list')}

{section name=Handlers loop=$handlers}
Handler: {$Handlers:item.name} <br/>

{include handler=$Handlers:item uri=concat( "design:notification/handler/",$Handlers:item.id_string,"/settings/edit.tpl")}
{delimiter}<br/>{/delimiter}
{/section}

{/let}

<div>
<input class="button" type="submit" name="Store" value="{'Store'|i18n('design/standard/notification')}" />
<input class="button" type="submit" name="Cancel" value="{'Cancel'|i18n('design/standard/notification')}" />
</div>

</form>