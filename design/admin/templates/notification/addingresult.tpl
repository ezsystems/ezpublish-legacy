{let Node=fetch( content, node, hash( node_id, $node_id) )}
{section show=$already_exists}
    <h3>Notification for node {$Node.name} already exists.</h3>
{section-else}
    <h3>Notification for node {$Node.name} was added successfully.</h3>
{/section}
<form action={$redirect_url|ezurl} method="post">
    <input class="button" type="submit" value="{'OK'|i18n( 'design/admin/notification/addingresult' )}" />
</form>
{/let}