<?php

class ezpTemplateImageFunctions implements ezcTemplateCustomFunction
{
    public static function getCustomFunctionDefinition($name)
    {
        return ezpTemplateFunctions::mapFunction( __CLASS__, $name );
    }

    /**
     * Returns the input string with embedded image tags.
     */
    public static function wordtoimage($input)
    {
        return Image::getWordToImage($input);
    }

}

?>
