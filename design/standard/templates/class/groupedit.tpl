<form action="{$module.functions.groupedit.uri}/{$classgroup.id}" method="post" name="GroupEdit">

<h1>Editing class group - {$classgroup.name}</h1>
<p>Created by {$classgroup.creator_id} on {$classgroup.created|l10n(shortdatetime)}</p>
<p>Modified by {$classgroup.modifier_id} on {$classgroup.modified|l10n(shortdatetime)}</p>

<table>
<tr><td>Name:</td></tr>
<tr><td>{include uri="design:gui/lineedit.tpl" name=Name id_name=Group_name value=$classgroup.name}</td></tr>
</table>
<br />
<table width="100%">
<tr>
<td>{include uri="design:gui/button.tpl" name=Store id_name=StoreButton value=Store}</td>
<td>{include uri="design:gui/button.tpl" name=Discard id_name=DiscardButton value=Discard}</td>
<td width="99%"></td>
</tr>
</table>

</td></tr>
</table>

</form>