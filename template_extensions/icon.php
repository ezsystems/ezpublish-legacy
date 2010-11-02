<?php

class ezpTemplateIconFunctions implements ezcTemplateCustomFunction
{
    public static function getCustomFunctionDefinition($name)
    {
        return ezpTemplateFunctions::mapFunction( __CLASS__, $name );
    }

    /**
     * Outputs an image tag referencing a MIME type icon.
     */
    public static function mimetype_icon($input, $sizeName = false, $altText = false, $returnURI = false )
    {
        return Image::getMimeTypeIcon($input, $sizeName, $altText, $returnURI);
    }

    /**
     * Outputs an image tag referencing a class icon.
     */
    public static function class_icon($input, $sizeName = false, $altText = false, $returnURI = false )
    {
        return Image::getClassIcon($input, $sizeName, $altText, $returnURI);
    }

    /**
     * Outputs an image tag referencing a class group icon.
     */
    public static function classgroup_icon($input, $sizeName = false, $altText = false, $returnURI = false )
    {
        return Image::getClassGroupIcon($input, $sizeName, $altText, $returnURI);
    }

    public static function action_icon($input, $sizeName = false, $altText = false, $returnURI = false )
    {
        return Image::getActionIcon($input, $sizeName, $altText, $returnURI);
    }

    public static function icon($input, $sizeName = false, $altText = false, $returnURI = false )
    {
        return Image::getIcon($input, $sizeName, $altText, $returnURI);
    }

    /**
     * Outputs an image tag referencing a flag icon.
     */
    public static function flag_icon($language = false )
    {
        return Image::getFlagIcon($language);
    }

    public static function icon_info($type = false )
    {
        return Image::getIconInfo($type);
    }
}

?>
