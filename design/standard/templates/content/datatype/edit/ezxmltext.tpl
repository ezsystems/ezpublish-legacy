{switch name=Sw match=$attribute.show_editor}
  {case match=1}
  <!-- WYSIWYG editor textarea field -->
  <textarea name="ContentObjectAttribute_data_text_{$attribute.id}" cols="76.5" rows="{$attribute.contentclass_attribute.data_int1}">
  {$attribute.input_xml}
  </textarea>
  <script language="javascript">
  var Editor = new eZEditor( 'ContentObjectAttribute_data_text_{$attribute.id}', '/extension/editor/images/', '', '{$attribute.contentobject_id}', '{$attribute.version}');
  Editor.startEditor();
  </script>
  <!-- End editor -->
  {/case}
  {case}
  <textarea name="ContentObjectAttribute_data_text_{$attribute.id}" cols="70" rows="{$attribute.contentclass_attribute.data_int1}">{$attribute.input_xml}</textarea>
  {/case}
{/switch}

