<!-- Create here menu -->
<script type="text/javascript">
menuArray['CreateHereMenu'] = {ldelim} 'depth': 1 {rdelim};
menuArray['CreateHereMenu']['elements'] = {ldelim}{rdelim};
menuArray['CreateHereMenu']['elements']['menu-classes'] = {ldelim} 'variable': '%classList%' {rdelim};
menuArray['CreateHereMenu']['elements']['menu-classes']['content'] = '<a id="menu-item-create-here" href="#" onclick="ezpopmenu_submitForm( \'menu-form-create-here\', new Array( \'classID\', \'%classID%\' ) ); return false;">%name%<\/a>';
</script>

<div class="popupmenu" id="CreateHereMenu">
    <div id="menu-classes"></div>
</div>

{* Create here form *}
<form id="menu-form-create-here" method="post" action={"/content/action"|ezurl}>
  <input type="hidden" name="NewButton" value="x" />
  <input type="hidden" name="ContentNodeID" value="%nodeID%" />
  <input type="hidden" name="NodeID" value="%nodeID%" />
  <input type="hidden" name="ContentObjectID" value="%objectID%" />
  <input type="hidden" name="ClassID" value="%classID%" />
  <input type="hidden" name="ViewMode" value="full" />
  {* <input type="hidden" name="ContentLanguageCode" value="eng-GB" /> *}
</form>