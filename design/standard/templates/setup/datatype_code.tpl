/*!
  \class   {$full_class_name} {$file_name}
  \ingroup eZDatatype
  \brief   {$description_brief}
  \version 1.0
  \date    {currentdate()|l10n(datetime)}

{section show=$creator_name}
  \author  {$creator_name}
{/section}


{$description_full|indent(2)}

*/

include_once( "kernel/classes/ezdatatype.php" );

define( {$constant_name}, "{$datatype_name}" );

class {$full_class_name} extends eZDataType 
{literal}{{/literal}
    /*!
      {'Constructor'|i18n('design/standard/setup/datatypecode')}
    */
    function {$full_class_name}()
    {literal}{{/literal}
        $this->eZDataType( {$constant_name}, "{$desc_name}" );
    {literal}}{/literal}

{section show=$class_input}
    /*!
    Validates all variables given on content class level
     \return EZ_INPUT_VALIDATOR_STATE_ACCEPTED or EZ_INPUT_VALIDATOR_STATE_INVALID if
             the values are accepted or not
    */
    function validateClassAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {literal}{{/literal}
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    {literal}}{/literal}

    /*!
     Fetches all variables inputed on content class level
     \return true if fetching of class attributes are successfull, false if not
    */
    function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {literal}{{/literal}
        return true;
    {literal}}{/literal}
{/section}

    /*!
     Validates input on content object level
     \return EZ_INPUT_VALIDATOR_STATE_ACCEPTED or EZ_INPUT_VALIDATOR_STATE_INVALID if
             the values are accepted or not
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {literal}{{/literal}
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    {literal}}{/literal}

    /*!
     Fetches all variables from the object
     \return true if fetching of class attributes are successfull, false if not
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {literal}{{/literal}
        return true;
    {literal}}{/literal}

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {literal}{{/literal}
        return "";
    {literal}}{/literal}

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {literal}{{/literal}
        return "";
    {literal}}{/literal}

    /*!
     Returns the value as it will be shown if this attribute is used in the object name pattern.
    */
    function title( &$contentObjectAttribute )
    {literal}{{/literal}
        return "";
    {literal}}{/literal}

    /*!
     \return true if the datatype can be indexed
    */
    function isIndexable()
    {literal}{{/literal}
        return true;
    {literal}}{/literal}

{literal}}{/literal}

eZDataType::register( {$constant_name}, "{$full_class_name}" );
