<a href={$node.path_identification_string|ezurl}>{$node.name|wash()}
<table width="150" height="150" class="list">
<tr>
    <td align="center" class="bglight">
     {$node.object.content_class.identifier|class_icon( normal, $node.object.content_class.name )}
    </td>
</tr>
</table>
</a>