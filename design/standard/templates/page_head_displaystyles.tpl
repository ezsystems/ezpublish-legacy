{* NOTE: Can not be inside a cache-block, unless it has user id as key! *}
<style type="text/css">
.limitdisplay-user {ldelim} display: none; {rdelim}
.limitdisplay-user-{$current_user.contentobject_id} {ldelim} display: inline; {rdelim}
.limitdisplay-user-block-{$current_user.contentobject_id} {ldelim} display: block; {rdelim}
</style>