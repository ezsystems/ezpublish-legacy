<?php

$infoArray = array();
$infoArray["name"] = "eZ template";
$infoArray["description"] = "
<h1>eZ template&trade;</h1>
<p>The template systems allows for separation of code and
  layout by moving the layout part into template files. These
  template files are parsed and processed with template variables set
  by the PHP code.</p>

<p>The template system in itself is does not do much, it parses template files
  according to a rule set, sets up a tree hierarchy and processes the data
  using functions and operators. The standard template system comes with only
  a few functions and no operators, it is meant for these functions and operators
  to be specified by the users of the template system. But for simplicity a few
  help classes is available which can be easily enabled.</p>

<p>In keeping with the spirit of being simple the template system does not know how
  to get the template files itself. Instead it relies on resource handlers, these
  handlers fetches the template files using different kind of transport mechanism.
  For simplicity a default resource class is available, eZTemplateFileResource fetches
  templates from the filesystem.</p>

<p><b>Note: When developing templates you should disable the view cache. When this is
  enabled, the template engine does not check the modification date of the templates,
  thus you will not see any changes. Edit settings/site.ini and set ViewCaching=disabled
  in [ContentSettings].</b></p>
";

$dependArray = array();
$dependArray[] = array( "uri" => "ezutils",
                        "name" => "eZ utils" );
$dependArray[] = array( "uri" => "ezi18n",
                        "name" => "eZ i18n" );
$dependArray[] = array( "uri" => "ezlocale",
                        "name" => "eZ locale" );

$infoArray["dependencies"] =& $dependArray;

$featureArray = array();
// $featureArray[] = array( "level" => 0,
//                          "name" => "Guides" );
// $featureArray[] = array( "uri" => "engine",
//                          "level" => 0,
//                          "name" => "Engine Details" );
// $featureArray[] = array( "uri" => "todo",
//                          "level" => 0,
//                          "name" => "Bugs/Todo" );

$featureArray[] = array( "level" => 0,
                         "name" => "Basics" );
$featureArray[] = array( "uri" => "basic_comments",
                         "level" => 0,
                         "name" => "Comments" );
$featureArray[] = array( "uri" => "basic_types",
                         "level" => 0,
                         "name" => "Types" );
$featureArray[] = array( "uri" => "basic_namespaces",
                         "level" => 0,
                         "name" => "Namespaces" );
$featureArray[] = array( "uri" => "basic_attributes",
                         "level" => 0,
                         "name" => "Attributes" );
$featureArray[] = array( "uri" => "basic_operators",
                         "level" => 0,
                         "name" => "Operators" );
$featureArray[] = array( "uri" => "basic_functions",
                         "level" => 0,
                         "name" => "Functions" );

$featureArray[] = array( "level" => 0,
                         "name" => "Operators" );
$featureArray[] = array( "uri" => "operator_list",
                         "level" => 0,
                         "name" => "Operator list" );

$featureArray[] = array( "uri" => "operator_creators",
                         "level" => 0,
                         "name" => "Type creators" );
// $featureArray[] = array( "uri" => "operator_image",
//                          "level" => 0,
//                          "name" => "Text to image" );
// $featureArray[] = array( "uri" => "operator_unit",
//                          "level" => 0,
//                          "name" => "Units" );

$featureArray[] = array( "level" => 0,
                         "name" => "Functions" );
$featureArray[] = array( "uri" => "function_list",
                         "level" => 0,
                         "name" => "Function list" );
$featureArray[] = array( "uri" => "function_delimit",
                         "level" => 0,
                         "name" => "Delimiter" );
$featureArray[] = array( "uri" => "function_include",
                         "level" => 0,
                         "name" => "Include" );
$featureArray[] = array( "uri" => "function_sequence",
                         "level" => 0,
                         "name" => "Sequence" );
$featureArray[] = array( "uri" => "function_section",
                         "level" => 0,
                         "name" => "Section" );
$featureArray[] = array( "uri" => "function_switch",
                         "level" => 0,
                         "name" => "Switch" );

$infoArray["features"] =& $featureArray;

$docArray = array();
$docArray[] = array( "uri" => "eZTemplate",
                     "name" => "Template manager");
$docArray[] = array( "uri" => "eZTemplateElements",
                     "name" => "Template elements",
                     "part" => "module" );
$docArray[] = array( "uri" => "eZTemplateOperators",
                     "name" => "Template operators",
                     "part" => "module" );
$docArray[] = array( "uri" => "eZTemplateFunctions",
                     "name" => "Template functions",
                     "part" => "module" );

$infoArray["doc"] =& $docArray;

?>
