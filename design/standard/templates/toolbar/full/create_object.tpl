{section show=$requested_uri_string|begins_with( $subtree )}

<div class="toolbox">
    <div class="toolbox-design">
    <h2>Weblog actions</h2>
    <form method="post" action={"content/action/"|ezurl}>
        <input class="button" type="submit" name="NewButton" value="New weblog" />
        <input type="hidden" name="NodeID" value="{$node_placement}" />
        <input type="hidden" name="ClassIdentifier" value="{$class_identifier}" />
     </form>
     </div>
</div>
{/section}
