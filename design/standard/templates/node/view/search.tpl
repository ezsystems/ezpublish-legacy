{default use_url_translation=false()}
    <td class="{$sequence}">
    <a href={cond( $use_url_translation, $node.url_alias|ezurl,
                   true(), concat( "/content/view/full/", $node.main_node_id )|ezurl )}>{$node.name|wash}</a>
       
    </td>
    <td class="{$sequence}">
      <nobr>{$node.object.class_name|wash}</nobr>
    </td>
{/default}
