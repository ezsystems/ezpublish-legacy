<?php

class TemplateConversionBlocks implements ezcTemplateCustomBlock
{
    public static $runOnce = array();

    public static function getCustomBlockDefinition($name)
    {
        if( $name == "node_view_gui" )
        {
            $def = new ezcTemplateCustomBlockDefinition();
            $def->class = __CLASS__;
            $def->method = "node_view_gui";
            $def->hasCloseTag = false;
            $def->startExpressionName = false;
            $def->requiredParameters = array("content_node");
            $def->optionalParameters = array("view", "render_mode"); // JB-TODO: render-mode is the original name, how to fix it?
            $def->excessParameters = true;

            return $def;
        }
        elseif( $name == "attribute_view_gui" )
        {
            $def = new ezcTemplateCustomBlockDefinition();
            $def->class = __CLASS__;
            $def->method = "attribute_view_gui";
            $def->hasCloseTag = false;
            $def->startExpressionName = false;
            $def->requiredParameters = array("attribute");
            $def->excessParameters = true;

            return $def;
        }
        elseif( $name == "content_view_gui" )
        {
            $def = new ezcTemplateCustomBlockDefinition();
            $def->class = __CLASS__;
            $def->method = "content_view_gui";
            $def->hasCloseTag = false;
            $def->startExpressionName = false;
            $def->requiredParameters = array();
            $def->optionalParameters = array("content_object", "view");
            $def->excessParameters = true;

            return $def;
        }
        elseif( $name == "content_version_view_gui" )
        {
            $def = new ezcTemplateCustomBlockDefinition();
            $def->class = __CLASS__;
            $def->method = "content_version_view_gui";
            $def->hasCloseTag = false;
            $def->startExpressionName = false;
            $def->requiredParameters = array("content_version");
            $def->optionalParameters = array("view");
            $def->excessParameters = true;

            return $def;
        }
        elseif( $name == "class_attribute_edit_gui" )
        {
            $def = new ezcTemplateCustomBlockDefinition();
            $def->class = __CLASS__;
            $def->method = "class_attribute_edit_gui";
            $def->hasCloseTag = false;
            $def->startExpressionName = false;
            $def->requiredParameters = array("class_attribute");
            $def->optionalParameters = array();
            $def->excessParameters = true;

            return $def;
        }

        elseif( $name == "attribute_edit_gui" )
        {
            $def = new ezcTemplateCustomBlockDefinition();
            $def->class = __CLASS__;
            $def->method = "attribute_edit_gui";
            $def->hasCloseTag = false;
            $def->startExpressionName = false;
            $def->requiredParameters = array("attribute");
            $def->optionalParameters = array();
            $def->excessParameters = true;
            return $def;
        }

        elseif( $name == "attribute_pdf_gui" )
        {
            $def = new ezcTemplateCustomBlockDefinition();
            $def->class = __CLASS__;
            $def->method = "attribute_pdf_gui";
            $def->hasCloseTag = false;
            $def->startExpressionName = false;
            $def->requiredParameters = array("attribute");
            $def->optionalParameters = array();
            $def->excessParameters = true;
            return $def;
        }

        elseif( $name == "attribute_result_gui" )
        {
            $def = new ezcTemplateCustomBlockDefinition();
            $def->class = __CLASS__;
            $def->method = "attribute_result_gui";
            $def->hasCloseTag = false;
            $def->startExpressionName = false;
            $def->requiredParameters = array("attribute");
            $def->optionalParameters = array();
            $def->excessParameters = true;
            return $def;
        }

        elseif( $name == "class_attribute_view_gui" )
        {
            $def = new ezcTemplateCustomBlockDefinition();
            $def->class = __CLASS__;
            $def->method = "class_attribute_view_gui";
            $def->hasCloseTag = false;
            $def->startExpressionName = false;
            $def->requiredParameters = array("class_attribute");
            $def->optionalParameters = array();
            $def->excessParameters = true;
            return $def;
        }

        elseif( $name == "collaboration_icon" )
        {
            $def = new ezcTemplateCustomBlockDefinition();
            $def->class = __CLASS__;
            $def->method = "collaboration_icon";
            $def->hasCloseTag = false;
            $def->startExpressionName = false;
            $def->requiredParameters = array("collaboration_item");
            $def->optionalParameters = array("view");
            $def->excessParameters = true;
            return $def;
        }

        elseif( $name == "collaboration_participation_view" )
        {
            $def = new ezcTemplateCustomBlockDefinition();
            $def->class = __CLASS__;
            $def->method = "collaboration_participation_view";
            $def->hasCloseTag = false;
            $def->startExpressionName = false;
            $def->requiredParameters = array("collaboration_participant");
            $def->optionalParameters = array("view");
            $def->excessParameters = true;
            return $def;
        }

        elseif( $name == "collaboration_simple_message_view" )
        {
            $def = new ezcTemplateCustomBlockDefinition();
            $def->class = __CLASS__;
            $def->method = "collaboration_simple_message_view";
            $def->hasCloseTag = false;
            $def->startExpressionName = false;
            $def->requiredParameters = array("collaboration_message", "sequence", "is_read", "item_link");
            $def->optionalParameters = array("view");
            $def->excessParameters = true;
            return $def;
        }

        elseif( $name == "collaboration_view_gui" )
        {
            $def = new ezcTemplateCustomBlockDefinition();
            $def->class = __CLASS__;
            $def->method = "collaboration_view_gui";
            $def->hasCloseTag = false;
            $def->startExpressionName = false;
            $def->requiredParameters = array("item_class", "collaboration_item");
            $def->optionalParameters = array("view");
            $def->excessParameters = true;
            return $def;
        }
        elseif( $name == "content_pdf_gui" )
        {
            $def = new ezcTemplateCustomBlockDefinition();
            $def->class = __CLASS__;
            $def->method = "content_pdf_gui";
            $def->hasCloseTag = false;
            $def->startExpressionName = false;
            $def->requiredParameters = array("content_object");
            $def->optionalParameters = array("view");
            $def->excessParameters = true;
            return $def;
        }

        elseif( $name == "event_edit_gui" )
        {
            $def = new ezcTemplateCustomBlockDefinition();
            $def->class = __CLASS__;
            $def->method = "event_edit_gui";
            $def->hasCloseTag = false;
            $def->startExpressionName = false;
            $def->requiredParameters = array("event");
            $def->optionalParameters = array();
            $def->excessParameters = true;
            return $def;
        }

        elseif( $name == "event_view_gui" )
        {
            $def = new ezcTemplateCustomBlockDefinition();
            $def->class = __CLASS__;
            $def->method = "event_view_gui";
            $def->hasCloseTag = false;
            $def->startExpressionName = false;
            $def->requiredParameters = array("event");
            $def->optionalParameters = array();
            $def->excessParameters = true;
            return $def;
        }


//        elseif( $name == "related_view_gui" )
//        {
//            $def = new ezcTemplateCustomBlockDefinition();
//            $def->class = __CLASS__;
//            $def->method = "related_view_gui";
//            $def->hasCloseTag = false;
//            $def->startExpressionName = false;
//            $def->requiredParameters = array("");
//            $def->optionalParameters = array();
//            $def->excessParameters = true;
//            return $def;
//        }

        elseif( $name == "shop_account_view_gui" )
        {
            $def = new ezcTemplateCustomBlockDefinition();
            $def->class = __CLASS__;
            $def->method = "shop_account_view_gui";
            $def->hasCloseTag = false;
            $def->startExpressionName = false;
            $def->requiredParameters = array("order");
            $def->optionalParameters = array("view");
            $def->excessParameters = true;
            return $def;
        }
        elseif( $name == "tool_bar" )
        {
            $def = new ezcTemplateCustomBlockDefinition();
            $def->class = __CLASS__;
            $def->method = "tool_bar";
            $def->hasCloseTag = false;
            $def->startExpressionName = false;
            $def->requiredParameters = array("name");
            $def->optionalParameters = array("view");
            $def->excessParameters = true;
            return $def;
        }
        elseif( $name == "menu" )
        {
            $def = new ezcTemplateCustomBlockDefinition();
            $def->class = __CLASS__;
            $def->method = "menu";
            $def->hasCloseTag = false;
            $def->startExpressionName = false;
            $def->requiredParameters = array("name");
            return $def;
        }
        elseif( $name == "run_once" )
        {
            $def = new ezcTemplateCustomBlockDefinition();
            $def->class = __CLASS__;
            $def->method = "run_once";
            $def->hasCloseTag = true;
            $def->startExpressionName = "id";
            $def->optionalParameters = array("id");
            return $def;
        }
  
        elseif( $name == "node_pdf_gui" )
        {
            $def = new ezcTemplateCustomBlockDefinition();
            $def->class = __CLASS__;
            $def->method = "node_pdf_gui";
            $def->hasCloseTag = false;
            $def->startExpressionName = false;
            $def->requiredParameters = array();
            $def->optionalParameters = array("view");
            $def->excessParameters = true;

            return $def;
        }
        elseif( $name == "attribute_diff_gui" )
        {
            $def = new ezcTemplateCustomBlockDefinition();
            $def->class = __CLASS__;
            $def->method = "attribute_diff_gui";
            $def->hasCloseTag = false;
            $def->startExpressionName = false;
            $def->requiredParameters = array();
            $def->optionalParameters = array("view");
            $def->excessParameters = true;

            return $def;
        }


    }

    /**
     * Common function for all *_gui blocks.
     */
    public static function process_gui($inName, $outName, $viewName, $renderName, $tplRoot, $tplExtra, $parameters)
    {
//        echo "process_gui('$inName', '$outName', '$viewName', '$tplRoot', \$parameters)<br/>\n";
        $contentNode = $parameters[$inName];
        $subPath = "";
        $excludes = array( $inName );
        if ( $renderName && isset( $parameters[$renderName] ) )
        {
            $subPath  .= '/' . $parameters[$renderName];
            $exclude[] = $renderName;
        }
        if ( $viewName && isset( $parameters[$viewName] ) )
        {
            $subPath  .= '/' . $parameters[$viewName];
            $exclude[] = $viewName;
        }
        if ( $tplExtra )
            $subPath .= '/' . $tplExtra;
        $name = "design:{$tplRoot}{$subPath}.tpl";

        $tpl = eZTemplate::instance();
        $tpl->setVariable( $outName, $contentNode );

        foreach ( $excludes as $exclude )
            unset( $parameters[$exclude] );
        foreach ( $parameters as $key => $parameter )
        {
            $tpl->setVariable( $key, $parameter );
        }

//        echo "\$tpl->fetch( '$name' );<br/>\n";
        $text = $tpl->fetch( $name );
        return $text;
    }

    /* JB-TODO: The current implementation is very close to the original one but lacks a few features.
                Replacing all *_gui blocks with a new mechanism (e.g. widgets) might be an idea for the final Neo release. */
    public static function node_view_gui($parameters)
    {
        return self::process_gui("content_node", "node", "view", "render-mode", "node/view", false, $parameters);
    }

    /* JB-TODO: The current implementation is very close to the original one but lacks a few features.
                Replacing all *_gui blocks with a new mechanism (e.g. widgets) might be an idea for the final Neo release. */
    public static function attribute_view_gui($parameters)
    {
        $attr    = $parameters['attribute'];
        $tplRoot = "content/datatype/view";
        if ( $attr->is_information_collector )
            $tplRoot = "content/datatype/collect";
        return self::process_gui("attribute", "attribute", "view", false, $tplRoot, $attr->view_template, $parameters);
    }

    /* JB-TODO: The current implementation is very close to the original one but lacks a few features.
                Replacing all *_gui blocks with a new mechanism (e.g. widgets) might be an idea for the final Neo release. */
    public static function content_view_gui($parameters)
    {
        return self::process_gui("content_object", "object", "view", false, "content/view", false, $parameters);
    }

    public static function content_version_view_gui($parameters)
    {
        return "[content_version_view_gui] not implemented";
    }

    /* JB-TODO: The current implementation is very close to the original one but lacks a few features.
                Replacing all *_gui blocks with a new mechanism (e.g. widgets) might be an idea for the final Neo release. */
    public static function attribute_edit_gui($parameters)
    {
        $attr = $parameters['attribute'];
        return self::process_gui("attribute", "attribute", false, false, "content/datatype/edit", $attr->edit_template, $parameters);
    }

    public static function attribute_pdf_gui($parameters)
    {
        return "[attribute_pdf_gui] not implemented";
    }

    public static function attribute_result_gui($parameters)
    {
        return "[attribute_result_gui] not implemented";
    }

    /* JB-TODO: The current implementation is very close to the original one but lacks a few features.
                Replacing all *_gui blocks with a new mechanism (e.g. widgets) might be an idea for the final Neo release. */
    public static function class_attribute_view_gui($parameters)
    {
        $attr = $parameters['class_attribute'];
        return self::process_gui("class_attribute", "class_attribute", false, false, "class/datatype/view", $attr->data_type->information['string'], $parameters);
    }

    /* JB-TODO: The current implementation is very close to the original one but lacks a few features.
                Replacing all *_gui blocks with a new mechanism (e.g. widgets) might be an idea for the final Neo release. */
    public static function class_attribute_edit_gui($parameters)
    {
        $attr = $parameters['class_attribute'];
        return self::process_gui("class_attribute", "class_attribute", false, false, "class/datatype/edit", $attr->data_type->information['string'], $parameters);
    }

    public static function collaboration_icon($parameters)
    {
        return "[collaboration_icon] not implemented";
    }

    public static function collaboration_participation_view ($parameters)
    {
        return "[collaboration_participation_view] not implemented";
    }

    public static function collaboration_simple_message_view($parameters)
    {
        return "[collaboration_simple_message_view] not implemented";
    }

    public static function collaboration_view_gui($parameters)
    {
        return "[collaboration_view_gui] not implemented";
    }

    public static function content_pdf_gui($parameters)
    {
        return "[content_pdf_gui] not implemented";
    }

    public static function event_edit_gui($parameters)
    {
        return "[event_edit_gui] not implemented";
    }

    public static function event_view_gui($parameters)
    {
        return "[event_view_gui] not implemented";
    }


//    public static function related_view_gui ($parameters)
//    {
//        return "[related_view_gui] not implemented";
//    }

    public static function shop_account_view_gui ($parameters)
    {
        return "[shop_account_view_gui] not implemented";
    }

    /* JB-TODO: Propose to move code into a separate class (ie. no template code) and call that. */
    /*!
     Parameters:
     name = Name of tool bar to render (Required)
     view = View mode for render (Default is 'full')
     */
    public static function tool_bar($params)
    {
        if ( !isset( $params["name"] ) )
            return ezpTemplateFunctions::runtimeError( "Parameter 'name' is required for block 'tool_bar'" );

        $name = $params["name"];
        unset( $params["name"] );

        $view = false;
        if ( isset( $params["view"] ) )
        {
            $view = $params["view"];
            unset( $params["view"] );
        }

        return Toolbar::getToolbar($name, $view, $params);
    }

    /* JB-TODO: Propose to move code into a separate class (ie. no template code) and call that. */
    public static function menu(/*string*/ $parameters)
    {
        return Menu::getMenu($parameters["name"]);
    }

    public static function run_once($parameters, $text)
    {
        $id = isset($parameters["id"]) ? $parameters["id"] : "unique";
        if( isset( self::$runOnce[$id] ) )
        {
            return "";
        }

        self::$runOnce[$id] = true;
        return $text;

    }


    public static function node_pdf_gui($parameters)
    {
        return "[node_pdf_gui] not implemented";
    }

    public static function attribute_diff_gui($parameters)
    {
        return "[attribute_diff_gui] not implemented";
    }


}


