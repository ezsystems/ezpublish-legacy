<?php
/**
 * File containing the eZTemplateArrayOperator class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZTemplateArrayOperator eztemplatearrayoperator.php
  \ingroup eZTemplateOperators
  \brief Dynamic creation of arrays using operator "array"

  Creates an operator which can create arrays dynamically by
  adding all operator parameters as array elements.

\code
// Example template code
{array(1,"test")}
{array(array(1,2),3)}
\endcode

*/

class eZTemplateArrayOperator
{
    public $ArrayPrependName;
    public $PrependName;
    public $ArrayAppendName;
    public $AppendName;
    public $ArrayMergeName;
    public $MergeName;
    public $ContainsName;
    public $CompareName;
    public $ExtractName;
    public $ExtractLeftName;
    public $ExtractRightName;
    public $BeginsWithName;
    public $EndsWithName;
    public $ImplodeName;
    public $ExplodeName;
    public $RepeatName;
    public $ReverseName;
    public $InsertName;
    public $RemoveName;
    public $ReplaceName;
    public $UniqueName;
    public $ArraySumName;
    public function __construct(      $arrayName        = 'array',
                                      $hashName         = 'hash',
                                      $arrayPrependName = 'array_prepend', // DEPRECATED/OBSOLETE
                                      $prependName      = 'prepend',       // New, replaces array_prepend.
                                      $arrayAppendName  = 'array_append',  // DEPRECATED/OBSOLETE
                                      $appendName       = 'append',        // New, replaces array_append.
                                      $arrayMergeName   = 'array_merge',   // DEPRECATED/OBSOLETE
                                      $mergeName        = 'merge',         // New, replaces array_merge.
                                      $containsName     = 'contains',
                                      $compareName      = 'compare',
                                      $extractName      = 'extract',
                                      $extractLeftName  = 'extract_left',
                                      $extractRightName = 'extract_right',
                                      $beginsWithName   = 'begins_with',
                                      $endsWithName     = 'ends_with',
                                      $implodeName      = 'implode',
                                      $explodeName      = 'explode',
                                      $repeatName       = 'repeat',
                                      $reverseName      = 'reverse',
                                      $insertName       = 'insert',
                                      $removeName       = 'remove',
                                      $replaceName      = 'replace',
                                      $uniqueName       = 'unique',
                                      $arraySumName       = 'array_sum' )
    {
        $this->ArrayName        = $arrayName;
        $this->HashName         = $hashName;
        $this->ArrayPrependName = $arrayPrependName; // DEPRECATED/OBSOLETE
        $this->PrependName      = $prependName;      // New, replaces ArrayPrependName.
        $this->ArrayAppendName  = $arrayAppendName;  // DEPRECATED/OBSOLETE
        $this->AppendName       = $appendName;       // New, replaces ArrayAppendName.
        $this->ArrayMergeName   = $arrayMergeName;   // DEPRECATED/OBSOLETE
        $this->MergeName        = $mergeName;        // New, replaces ArrayMergeName.
        $this->ContainsName     = $containsName;
        $this->CompareName      = $compareName;
        $this->ExtractName      = $extractName;
        $this->ExtractLeftName  = $extractLeftName;
        $this->ExtractRightName = $extractRightName;
        $this->BeginsWithName   = $beginsWithName;
        $this->EndsWithName     = $endsWithName;
        $this->ImplodeName      = $implodeName;
        $this->ExplodeName      = $explodeName;
        $this->RepeatName       = $repeatName;
        $this->ReverseName      = $reverseName;
        $this->InsertName       = $insertName;
        $this->RemoveName       = $removeName;
        $this->ReplaceName      = $replaceName;
        $this->UniqueName       = $uniqueName;
        $this->ArraySumName     = $arraySumName;

        $this->Operators = array( $arrayName,
                                  $hashName,
                                  $arrayPrependName, // DEPRECATED/OBSOLETE
                                  $prependName,      // New, replaces arrayPrependName.
                                  $arrayAppendName,  // DEPRECATED/OBSOLETE
                                  $appendName,       // New, replaces arrayAppendName.
                                  $arrayMergeName,   // DEPRECATED/OBSOLETE
                                  $mergeName,        // New, replaces arrayMergeName.
                                  $containsName,
                                  $compareName,
                                  $extractName,
                                  $extractLeftName,
                                  $extractRightName,
                                  $beginsWithName,
                                  $endsWithName,
                                  $implodeName,
                                  $explodeName,
                                  $repeatName,
                                  $reverseName,
                                  $insertName,
                                  $removeName,
                                  $replaceName,
                                  $uniqueName,
                                  $arraySumName );
    }

    /*!
     Returns the operators in this class.
    */
    function operatorList()
    {
        return $this->Operators;
    }

    function operatorTemplateHints()
    {
        return array( $this->ArrayName => array( 'input' => true,
                                                 'output' => true,
                                                 'parameters' => true,
                                                 'element-transformation' => true,
                                                 'transform-parameters' => true,
                                                 'element-transformation-func' => 'arrayTrans' ),
                      $this->HashName => array( 'input' => true,
                                                'output' => true,
                                                'parameters' => true,
                                                'element-transformation' => true,
                                                'transform-parameters' => true,
                                                'element-transformation-func' => 'arrayTrans'),
                      $this->ArrayPrependName => array( 'input' => true,
                                                        'output' => true,
                                                        'parameters' => true,
                                                        'element-transformation' => true,
                                                        'transform-parameters' => true,
                                                        'input-as-parameter' => 'always',
                                                        'element-transformation-func' => 'mergeTrans' ),
                      $this->PrependName => array( 'input' => true,
                                                   'output' => true,
                                                   'parameters' => true,
                                                   'element-transformation' => true,
                                                   'transform-parameters' => true,
                                                   'input-as-parameter' => 'always',
                                                   'element-transformation-func' => 'mergeTrans' ),
                      $this->ArrayAppendName => array( 'input' => true,
                                                       'output' => true,
                                                       'parameters' => true,
                                                       'element-transformation' => true,
                                                       'transform-parameters' => true,
                                                       'input-as-parameter' => 'always',
                                                       'element-transformation-func' => 'mergeTrans' ),
                      $this->AppendName => array( 'input' => true,
                                                  'output' => true,
                                                  'parameters' => true,
                                                  'element-transformation' => true,
                                                  'transform-parameters' => true,
                                                  'input-as-parameter' => 'always',
                                                  'element-transformation-func' => 'mergeTrans' ),
                      $this->ArrayMergeName => array( 'input' => true,
                                                      'output' => true,
                                                      'parameters' => true,
                                                      'element-transformation' => true,
                                                      'transform-parameters' => true,
                                                      'input-as-parameter' => 'always',
                                                      'element-transformation-func' => 'mergeTrans' ),
                      $this->MergeName => array( 'input' => true,
                                                 'output' => true,
                                                 'parameters' => true,
                                                 'element-transformation' => true,
                                                 'transform-parameters' => true,
                                                 'input-as-parameter' => 'always',
                                                 'element-transformation-func' => 'mergeTrans' ),
                      $this->ContainsName => array( 'input' => true,
                                                    'output' => true,
                                                    'parameters' => 1,
                                                    'element-transformation' => true,
                                                    'transform-parameters' => true,
                                                    'input-as-parameter' => 'always',
                                                    'element-transformation-func' => 'arrayTrans'),
                      $this->CompareName => array( 'input' => true,
                                                   'output' => true,
                                                   'parameters' => 1,
                                                   'element-transformation' => true,
                                                   'transform-parameters' => true,
                                                   'input-as-parameter' => 'always',
                                                   'element-transformation-func' => 'arrayTrans'),
                      $this->ExtractName => array( 'input' => true,
                                                   'output' => true,
                                                   'parameters' => 2,
                                                   'element-transformation' => true,
                                                   'transform-parameters' => true,
                                                   'input-as-parameter' => 'always',
                                                   'element-transformation-func' => 'extractTrans'),
                      $this->ExtractLeftName => array( 'input' => true,
                                                       'output' => true,
                                                       'parameters' => 1,
                                                       'element-transformation' => true,
                                                       'transform-parameters' => true,
                                                       'input-as-parameter' => 'always',
                                                       'element-transformation-func' => 'extractTrans'),
                      $this->ExtractRightName => array( 'input' => true,
                                                        'output' => true,
                                                        'parameters' => 1,
                                                        'element-transformation' => true,
                                                        'transform-parameters' => true,
                                                        'input-as-parameter' => 'always',
                                                        'element-transformation-func' => 'extractTrans'),
                      $this->BeginsWithName => array( 'input' => true,
                                                      'output' => true,
                                                      'parameters' => true,
                                                      'element-transformation' => true,
                                                      'transform-parameters' => true,
                                                      'input-as-parameter' => 'always',
                                                      'element-transformation-func' => 'compareTrans'),
                      $this->EndsWithName => array( 'input' => true,
                                                    'output' => true,
                                                    'parameters' => true,
                                                    'element-transformation' => true,
                                                    'transform-parameters' => true,
                                                    'input-as-parameter' => 'always',
                                                    'element-transformation-func' => 'compareTrans'),
                      $this->ImplodeName => array( 'input' => true,
                                                   'output' => true,
                                                   'parameters' => 1,
                                                   'element-transformation' => true,
                                                   'transform-parameters' => true,
                                                   'input-as-parameter' => 'always',
                                                   'element-transformation-func' => 'arrayTrans'),
                      $this->ExplodeName => array( 'input' => true,
                                                   'output' => true,
                                                   'parameters' => 1,
                                                   'element-transformation' => true,
                                                   'transform-parameters' => true,
                                                   'input-as-parameter' => 'always',
                                                   'element-transformation-func' => 'arrayTrans'),
                      $this->RepeatName => array( 'input' => true,
                                                  'output' => true,
                                                  'parameters' => 1,
                                                  'element-transformation' => true,
                                                  'transform-parameters' => true,
                                                  'input-as-parameter' => 'always',
                                                  'element-transformation-func' => 'arrayTrans'),
                      $this->ReverseName => array( 'input' => true,
                                                   'output' => true,
                                                   'parameters' => false,
                                                   'element-transformation' => true,
                                                   'transform-parameters' => true,
                                                   'input-as-parameter' => 'always',
                                                   'element-transformation-func' => 'arrayTrans' ),
                      $this->InsertName => array( 'input' => true,
                                                  'output' => true,
                                                  'parameters' => true,
                                                  'element-transformation' => true,
                                                  'transform-parameters' => true,
                                                  'input-as-parameter' => 'always',
                                                  'element-transformation-func' => 'arrayTrans' ),
                      $this->RemoveName => array( 'input' => true,
                                                  'output' => true,
                                                  'parameters' => 2,
                                                  'element-transformation' => true,
                                                  'transform-parameters' => true,
                                                  'input-as-parameter' => 'always',
                                                  'element-transformation-func' => 'arrayTrans'),
                      $this->ReplaceName => array( 'input' => true,
                                                   'output' => true,
                                                   'parameters' => true ),
                      $this->UniqueName => array( 'input' => true,
                                                  'output' => true,
                                                  'parameters' => false,
                                                  'element-transformation' => true,
                                                  'transform-parameters' => true,
                                                  'input-as-parameter' => 'always',
                                                  'element-transformation-func' => 'arrayTrans'),
                      $this->ArraySumName => array( 'input' => true,
                                                    'output' => true,
                                                    'parameters' => false,
                                                    'element-transformation' => true,
                                                    'transform-parameters' => true,
                                                    'input-as-parameter' => 'always',
                                                    'element-transformation-func' => 'arrayTrans' ) );
    }

    function arrayTrans( $operatorName, &$node, $tpl, &$resourceData,
                         $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        switch( $operatorName )
        {
            case $this->ArrayName:
            {
                $code = '';
                $paramCount = 0;
                $values = array();
                $staticArray = array();
                for ( $i = 0; $i < count( $parameters ); ++$i )
                {
                    if ( $i != 0 )
                    {
                        $code .= ', ';
                    }
                    else
                    {
                        $code .= '%output% = array( ';
                    }

                    if ( !eZTemplateNodeTool::isConstantElement( $parameters[$i] ) )
                    {
                        $values[] = $parameters[$i];
                        ++$paramCount;
                        $code .= '%' . $paramCount . '%';
                    }
                    else
                    {
                        if ( $paramCount == 0 )
                        {
                            $staticArray[] = eZTemplateNodeTool::elementConstantValue( $parameters[$i] );
                        }

                        $code .= eZPHPCreator::variableText( eZTemplateNodeTool::elementConstantValue( $parameters[$i] ), 0, 0, false );
                    }
                }

                if ( $paramCount == 0 )
                {
                    return array( eZTemplateNodeTool::createArrayElement( $staticArray ) );
                }

                $code .= ' );';

                return array( eZTemplateNodeTool::createCodePieceElement( $code, $values ) );
            } break;

            case $this->HashName:
            {
                $code = '';
                $paramCount = 0;
                $values = array();
                $staticArray = array();
                $staticKeys = true;
                $keys = array();
                $vals = array();
                $hashCount = (int)( count( $parameters ) / 2 );
                for ( $i = 0; $i < $hashCount; ++$i )
                {
                    if ( $i != 0 )
                    {
                        $code .= ', ';
                    }
                    else
                    {
                        $code .= '%output% = array( ';
                    }

                    if ( !eZTemplateNodeTool::isConstantElement( $parameters[$i*2] ) )
                    {
                        $staticKeys = false;
                        $values[] = $parameters[$i*2];
                        ++$paramCount;
                        $code .= '%' . $paramCount . '%';
                    }
                    else
                    {
                        $keys[] = eZTemplateNodeTool::elementConstantValue( $parameters[$i*2] );
                        $code .= eZPHPCreator::variableText( eZTemplateNodeTool::elementConstantValue( $parameters[$i*2] ), 0, 0, false );
                    }

                    $code .= ' => ';

                    if ( !eZTemplateNodeTool::isConstantElement( $parameters[$i*2+1] ) )
                    {
                        $values[] = $parameters[$i*2 + 1];
                        ++$paramCount;
                        $code .= '%' . $paramCount . '%';
                    }
                    else
                    {
                        if ( $paramCount == 0 )
                        {
                            $staticArray[ eZTemplateNodeTool::elementConstantValue( $parameters[$i*2] ) ] = eZTemplateNodeTool::elementConstantValue( $parameters[$i*2+1] );
                        }
                        $code .= eZPHPCreator::variableText( eZTemplateNodeTool::elementConstantValue( $parameters[$i*2+1] ), 0, 0, false );
                    }

                    if ( $staticKeys )
                    {
                        $vals[$keys[count( $keys ) - 1]] = $parameters[$i*2 + 1];
                    }
                }

                if ( $paramCount == 0 )
                {
                    return array( eZTemplateNodeTool::createArrayElement( $staticArray ) );
                }

                if ( $staticKeys )
                {
                    return array( eZTemplateNodeTool::createDynamicArrayElement( $keys, $vals ) );
                }

                $code .= ' );';

                return array( eZTemplateNodeTool::createCodePieceElement( $code, $values ) );
            } break;

            case $this->ContainsName:
            {
                $values = array();
                $inParam = null;
                $isString = false;
                $isArray = false;

                if ( eZTemplateNodeTool::isConstantElement( $parameters[0] ) )
                {
                    $inParam = eZTemplateNodeTool::elementConstantValue( $parameters[0] );
                    if ( is_string( $inParam ) )
                    {
                        $isString = true;
                    }
                    else if( is_array( $inParam ) )
                    {
                        $isArray = true;
                    }

                    $inParamCode = eZPHPCreator::variableText( $inParam, 0, 0, false );
                }
                else
                {
                    $values[] = $parameters[0];
                    $inParamCode = '%' . count( $values ) . '%';
                }

                if ( eZTemplateNodeTool::isConstantElement( $parameters[1] ) )
                {
                    $matchParam = eZTemplateNodeTool::elementConstantValue( $parameters[1] );
                    if ( count( $values ) == 0 )
                    {
                        if ( $isString )
                        {
                            $result = ( mb_strpos( $inParam, $matchParam ) !== false );
                        }
                        else if( $isArray )
                        {
                            $result = in_array( $matchParam, $inParam );
                        }

                        return array( eZTemplateNodeTool::createBooleanElement( $result ) );
                    }
                    $matchParamCode = eZPHPCreator::variableText( $matchParam, 0, 0, false );
                }
                else
                {
                    $values[] = $parameters[1];
                    $matchParamCode = '%' . count( $values ) . '%';
                }

                if ( $isString )
                {
                    $code = '%output% = ( mb_strpos( ' . $inParamCode . ', ' . $matchParamCode . ' ) !== false );';
                }
                else if ( $isArray )
                {
                    $code = '%output% = in_array( ' . $matchParamCode . ', ' . $inParamCode . ' );';
                }
                else
                {
                    $code = 'if( is_string( ' . $inParamCode . ' ) )' . "\n" .
                        '{' . "\n" .
                        '  %output% = ( mb_strpos( ' . $inParamCode . ', ' . $matchParamCode . ' ) !== false );' . "\n" .
                        '}' . "\n" .
                        'else if ( is_array( ' . $inParamCode . ' ) )' . "\n" .
                        '{' . "\n" .
                        '  %output% = in_array( ' . $matchParamCode . ', ' . $inParamCode . ' );' . "\n" .
                        '}' . "\n" .
                        'else' ."\n" .
                        '{' . "\n" .
                           '%output% = false;' . "\n" .
                        '}';
                }

                return array( eZTemplateNodeTool::createCodePieceElement( $code, $values ) );
            } break;

            case $this->CompareName:
            {
                $inParam = null;
                $isString = false;
                $isArray = false;
                $values = array();

                if ( eZTemplateNodeTool::isConstantElement( $parameters[0] ) )
                {
                    $inParam = eZTemplateNodeTool::elementConstantValue( $parameters[0] );
                    if ( is_string( $inParam ) )
                    {
                        $isString = true;
                    }
                    else if( is_array( $inParam ) )
                    {
                        $isArray = true;
                    }

                    $inParamCode = eZPHPCreator::variableText( $inParam, 0, 0, false );
                }
                else
                {
                    $values[] = $parameters[0];
                    $inParamCode = '%' . count( $values ) . '%';
                }

                if ( eZTemplateNodeTool::isConstantElement( $parameters[1] ) )
                {
                    $matchParam = eZTemplateNodeTool::elementConstantValue( $parameters[1] );
                    if ( count( $values ) == 0 )
                    {
                        if ( $isString )
                        {
                            $result = strcmp( $inParam, $matchParam ) === 0;
                        }
                        else if( $isArray )
                        {
                            $result = ( count( array_diff( $matchParam, $inParam ) ) == 0 and
                                        count( array_diff( $inParam, $matchParam ) ) == 0 );
                        }

                        return array( eZTemplateNodeTool::createBooleanElement( $result ) );
                    }
                    $matchParamCode = eZPHPCreator::variableText( $matchParam, 0, 0, false );
                }
                else
                {
                    $values[] = $parameters[1];
                    $matchParamCode = '%' . count( $values ) . '%';
                }

                if ( $isString )
                {
                    $code = '%output% = strcmp( ' . $inParamCode . ', ' . $matchParamCode . ' ) === 0;';
                }
                else if ( $isArray )
                {
                    $code = '%output% = ( ( count( array_diff( ' . $inParamCode . ', ' . $matchParamCode . " ) ) == 0 ) and\n" .
                                        ' ( count( array_diff( ' . $matchParamCode . ', ' . $inParamCode . ' ) ) == 0 ) );';
                }
                else
                {
                    $code = 'if( is_string( ' . $inParamCode . ' ) )' . "\n" .
                         '{' . "\n" .
                         '  %output% = strcmp( ' . $inParamCode . ', ' . $matchParamCode . ') === 0;' . "\n" .
                         '}' . "\n" .
                         'else if ( is_array( ' . $inParamCode . ' ) )' . "\n" .
                         '{' . "\n" .
                         '  %output% = ( ( count( array_diff( ' . $inParamCode . ', ' . $matchParamCode . " ) ) == 0 ) and\n" .
                                        '( count( array_diff( ' . $matchParamCode . ', ' . $inParamCode . ' ) ) == 0 ) );' . "\n" .
                         '}';
                }

                return array( eZTemplateNodeTool::createCodePieceElement( $code, $values ) );
            } break;

            case $this->ImplodeName:
            {
                $values = array();
                if ( !eZTemplateNodeTool::isConstantElement( $parameters[1] ) )
                {
                    $values[] = $parameters[1];
                    $code = '%1%, ';
                }
                else
                {
                    $code = eZPHPCreator::variableText( eZTemplateNodeTool::elementConstantValue( $parameters[1] ), 0, 0, false ) . ', ';
                }

                if ( eZTemplateNodeTool::isConstantElement( $parameters[0] ) )
                {
                    if ( count( $values ) == 0 )
                    {
                        return array( eZTemplateNodeTool::createStringElement( implode( eZTemplateNodeTool::elementConstantValue( $parameters[1] ),
                                                                                        eZTemplateNodeTool::elementConstantValue( $parameters[0] ) ) ) );
                    }
                    else
                    {
                        $code .= eZPHPCreator::variableText( eZTemplateNodeTool::elementConstantValue( $parameters[0] ), 0, 0, false );
                    }
                }
                else
                {
                    $values[] = $parameters[0];
                    $code .= '%' . count( $values ) . '%';
                }

                $code = '%output% = implode( ' . $code . ' );';

                return array( eZTemplateNodeTool::createCodePieceElement( $code, $values ) );
            } break;

            case $this->UniqueName:
            {
                if ( eZTemplateNodeTool::isConstantElement( $parameters[0] ) )
                {
                    return array( eZTemplateNodeTool::createArrayElement( array_unique( eZTemplateNodeTool::elementConstantValue( $parameters[0] ) ) ) );
                }

                $values = array( $parameters[0] );
                $code = '%output% = array_unique( %1% );';
                return array( eZTemplateNodeTool::createCodePieceElement( $code, $values ) );
            } break;

            case $this->ExplodeName:
            {
                $values = array();
                $inParam = null;
                $isString = false;
                $isArray = false;

                if ( eZTemplateNodeTool::isConstantElement( $parameters[0] ) )
                {
                    $inParam = eZTemplateNodeTool::elementConstantValue( $parameters[0] );
                    if ( is_string( $inParam ) )
                    {
                        $isString = true;
                    }
                    else if( is_array( $inParam ) )
                    {
                        $isArray = true;
                    }

                    $inParamCode = eZPHPCreator::variableText( $inParam, 0, 0, false );
                }
                else
                {
                    $values[] = $parameters[0];
                    $inParamCode = '%' . count( $values ) . '%';
                }

                if ( eZTemplateNodeTool::isConstantElement( $parameters[1] ) )
                {
                    $matchParam = eZTemplateNodeTool::elementConstantValue( $parameters[1] );
                    if ( count( $values ) == 0 )
                    {
                        if ( $isString )
                        {
                            $result = explode( $matchParam, $inParam );
                        }
                        else if( $isArray )
                        {
                            $result = array( array_slice( $inParam, 0, $matchParam ), array_slice( $inParam, $matchParam ) );
                        }

                        return array( eZTemplateNodeTool::createArrayElement( $result ) );
                    }
                    $matchParamCode = eZPHPCreator::variableText( $matchParam, 0, 0, false );
                }
                else
                {
                    $values[] = $parameters[1];
                    $matchParamCode = '%' . count( $values ) . '%';
                }

                if ( $isString )
                {
                    $code = '%output% = explode( ' . $matchParamCode . ', ' . $inParamCode . ' );';
                }
                else if ( $isArray )
                {
                    $code = '%output% = array( array_slice( ' . $inParamCode . ', 0,' . $matchParamCode . ' ), array_slice( ' . $inParamCode . ', ' . $matchParamCode .' ) );';
                }
                else
                {
                    $code = "if ( is_string( $inParamCode ) )\n" .
                         "{\n" .
                         "\t%output% = explode( $matchParamCode, $inParamCode );\n" .
                         "}\n" .
                         "else if ( is_array( $inParamCode ) )\n" .
                         "{\n" .
                         "\t%output% = array( array_slice( $inParamCode, 0, $matchParamCode ), array_slice( $inParamCode, $matchParamCode ) );\n" .
                         "}\n" .
                         "else\n" .
                         "{\n" .
                         "\t%output% = null;\n" .
                         "}\n";
                }

                return array( eZTemplateNodeTool::createCodePieceElement( $code, $values ) );
            } break;

            case $this->RemoveName:
            {
                $values = array();
                $isArray = false;
                $isString = false;

                if ( eZTemplateNodeTool::isConstantElement( $parameters[0] ) )
                {
                    $inputArray = eZTemplateNodeTool::elementConstantValue( $parameters[0] );
                    $inputArrayCode = eZPHPCreator::variableText( $inputArray, 0, 0, false );
                    $isString = is_string( $inputArray );
                    $isArray = is_array( $inputArray );
                }
                else
                {
                    $values[] = $parameters[0];
                    $inputArrayCode = '%' . count( $values ) . '%';
                }

                if ( eZTemplateNodeTool::isConstantElement( $parameters[1] ) )
                {
                    $offset = eZTemplateNodeTool::elementConstantValue( $parameters[1] );
                    $offsetCode = eZPHPCreator::variableText( $offset, 0, 0, false );
                }
                else
                {
                    $values[] = $parameters[1];
                    $offsetCode = '%' . count( $values ) . '%';
                }

                $length = false;
                $lengthCode = '';
                if ( count( $parameters ) > 2 )
                {
                    if ( eZTemplateNodeTool::isConstantElement( $parameters[2] ) )
                    {
                        $length = eZTemplateNodeTool::elementConstantValue( $parameters[2] );
                        $lengthCode = eZPHPCreator::variableText( $length, 0, 0, false );
                    }
                    else
                    {
                        $values[] = $parameters[2];
                        $lengthCode = '%' . count( $values ) . '%';
                    }
                }

                if ( count( $values ) == 0 )
                {
                    if ( $isString )
                    {
                        return array( eZTemplateNodeTool::createStringElement( mb_substr( $inputArray, $offset, $length ) ) );
                    }
                    else if ( $isArray )
                    {
                        if ( $length === false )
                            $length = 1;

                        $array_one = array_slice( $inputArray, 0, $offset );
                        $array_two = array_slice( $inputArray, $offset + $length );

                        return array ( eZTemplateNodeTool::createArrayElement( array_merge( $array_one, $array_two ) ) );
                    }
                }

                if ( $isString )
                {
                    $code = '%output% = mb_substr( ' . $inputArrayCode . ', ' . $offsetCode;
                    if ( $lengthCode )
                        $code .= ', ' . $lengthCode;
                    $code .= ' );';
                }
                else if ( $isArray )
                {
                    $code = '%output% = array_merge( array_slice( ' .  $inputArrayCode . ', 0, ' . $offsetCode . ' ), array_slice( ' . $inputArrayCode . ', ' . $offsetCode;
                    if ( $lengthCode )
                        $code .= ' + ' . $lengthCode;
                    $code .= ' ) );';
                }
                else
                {
                    $code = ( '%tmp1% = ' . $inputArrayCode . ';' . "\n" .
                              'if ( is_string( %tmp1% ) )' . "\n" .
                              '{' . "\n" .
                              '    %output% = ( mb_substr( %tmp1%, 0, ' . $offsetCode . ' )' );

                    $lengthCode = !$lengthCode ? 1 : $lengthCode;

                    if ( $lengthCode )
                    {
                        $code .= ' . mb_substr( %tmp1%, ' . $offsetCode . ' + ' . $lengthCode . ' )';
                    }
                    $code .= ( ' );' . "\n" .
                               '}' . "\n" .
                               'else if ( is_array( %tmp1% ) )' . "\n" .
                               '{' . "\n" .
                               '    %output% = array_merge( array_slice( %tmp1%, 0, ' . $offsetCode . ' )' );
                    if ( $lengthCode )
                    {
                        $code .= ', array_slice( %tmp1%, ' . $offsetCode . ' + ' . $lengthCode . ' )';
                    }
                    $code .= ( ' );' . "\n" .
                               '}' );
                }

                return array( eZTemplateNodeTool::createCodePieceElement( $code, $values, false, 1 ) );
            } break;

            case $this->InsertName:
            {
                $values = array();
                $isArray = false;
                $isString = false;

                if ( eZTemplateNodeTool::isConstantElement( $parameters[0] ) )
                {
                    $inputArray = eZTemplateNodeTool::elementConstantValue( $parameters[0] );
                    $inputArrayCode = eZPHPCreator::variableText( $inputArray, 0, 0, false );
                    $isString = is_string( $inputArray );
                    $isArray = is_array( $inputArray );
                }
                else
                {
                    $values[] = $parameters[0];
                    $inputArrayCode = '%' . count( $values ) . '%';
                }

                if ( eZTemplateNodeTool::isConstantElement( $parameters[1] ) )
                {
                    $offset = eZTemplateNodeTool::elementConstantValue( $parameters[1] );
                    $offsetCode = eZPHPCreator::variableText( $offset, 0, 0, false );
                }
                else
                {
                    $values[] = $parameters[1];
                    $offsetCode = '%' . count( $values ) . '%';
                }

                if ( count( $parameters ) > 2 )
                {
                    if ( eZTemplateNodeTool::isConstantElement( $parameters[2] ) )
                    {
                        $insertText = eZTemplateNodeTool::elementConstantValue( $parameters[2] );
                    }
                }

                $insertElemCode = array();

                for( $i = 2; $i < count( $parameters ); ++$i )
                {
                    if ( eZTemplateNodeTool::isConstantElement( $parameters[$i] ) )
                    {
                        $insertElemCode[] = eZPHPCreator::variableText( eZTemplateNodeTool::elementConstantValue( $parameters[$i] ), 0, 0, false );
                    }
                    else
                    {
                        $values[] = $parameters[$i];
                        $insertElemCode[] = '%' . count( $values ) . '%';
                    }
                }

                if ( count( $values ) == 0 )
                {
                    if ( $isString )
                    {
                        return array( eZTemplateNodeTool::createStringElement( mb_substr( $inputArray, 0, $offset ) . $insertText . mb_substr( $inputArray, $offset ) ) );
                    }
                    else if ( $isArray )
                    {
                        $array_one = array_slice( $inputArray, 0, $offset );
                        $array_two = array_slice( $inputArray, $offset );

                        $array_to_insert = array();
                        for ( $i = 2; $i < count( $parameters ); ++$i )
                        {
                            $array_to_insert[] = eZTemplateNodeTool::elementConstantValue( $parameters[$i] );
                        }

                        return array( eZTemplateNodeTool::createArrayElement( array_merge( $array_one, $array_to_insert, $array_two ) ) );
                    }
                }

                $tmpCount = 0;
                if ( $isString )
                {
                    $code = '%output% = mb_substr( ' . $inputArrayCode . ', 0, ' . $offsetCode . ' ) . ' . $insertElemCode[0] . ' . mb_substr( ' . $inputArrayCode . ', ' . $offsetCode . ' );';
                }
                else if ( $isArray )
                {
                    $code = '%tmp1% = ' . $inputArrayCode . ';' . "\n" .
                         '%tmp2% = array_slice( %tmp1%, 0, ' . $offsetCode . ' );' . "\n" .
                         '%tmp3% = array_slice( %tmp1%, ' . $offsetCode . ' );' . "\n" .
                         '%tmp4% = array( ';
                    for( $i = 0; $i < count( $insertElemCode ); ++$i )
                    {
                        if ( $i != 0 )
                        {
                            $code .= ", ";
                        }
                        $code .= $insertElemCode[$i];
                    }
                    $code .= ' );' . "\n" .
                         '%output% = array_merge( %tmp2%, %tmp4%, %tmp3% );' . "\n";
                    $tmpCount = 4;
                }
                else
                {
                    $code = '%tmp1% = ' . $inputArrayCode . ';' . "\n" .
                         'if ( is_string( %tmp1% ) )' . "\n" .
                         '{' . "\n" .
                         '  %output% = mb_substr( ' . $inputArrayCode . ', 0, ' . $offsetCode . ' ) . ' . $insertElemCode[0] . ' . mb_substr( ' . $inputArrayCode . ', ' . $offsetCode . ' );' . "\n" .
                         '}' . "\n" .
                         'else if ( is_array( %tmp1% ) )' . "\n" .
                         '{' . "\n" .
                         '  %tmp2% = array_slice( %tmp1%, 0, ' . $offsetCode . ' );' . "\n" .
                         '  %tmp3% = array_slice( %tmp1%, ' . $offsetCode . ' );' . "\n" .
                         '  %tmp4% = array( ';
                    for( $i = 0; $i < count( $insertElemCode ); ++$i )
                    {
                        if ( $i != 0 )
                        {
                            $code .= ", ";
                        }
                        $code .= $insertElemCode[$i];
                    }
                    $code .= ' );' . "\n" .
                         '  %output% = array_merge( %tmp2%, %tmp4%, %tmp3% );' . "\n" .
                         '}' . "\n";
                    $tmpCount = 4;
                }

                return array( eZTemplateNodeTool::createCodePieceElement( $code, $values, false, $tmpCount ) );
            } break;

            case $this->ReverseName:
            {
                if ( eZTemplateNodeTool::isConstantElement( $parameters[0] ) )
                {
                    if ( is_string( eZTemplateNodeTool::elementConstantValue( $parameters[0] ) ) )
                    {
                        return array( eZTemplateNodeTool::createStringElement( strrev( eZTemplateNodeTool::elementConstantValue( $parameters[0] ) ) ) );
                    }
                    else if ( is_array( eZTemplateNodeTool::elementConstantValue( $parameters[0] ) ) )
                    {
                        return array( eZTemplateNodeTool::createArrayElement( array_reverse( eZTemplateNodeTool::elementConstantValue( $parameters[0] ) ) ) );
                    }
                }

                $values = array( $parameters[0] );
                $code = 'if ( is_string( %1% ) )' . "\n" .
                     '{' . "\n".
                     '  %output% = strrev( %1% );' . "\n" .
                     '}' . "\n" .
                     'else if( is_array( %1% ) )' . "\n" .
                     '{' . "\n" .
                     '  %output% = array_reverse( %1% );' . "\n" .
                     '}' . "\n";

                return array( eZTemplateNodeTool::createCodePieceElement( $code, $values ) );
            } break;

            case $this->ArraySumName:
            {
                if ( eZTemplateNodeTool::isConstantElement( $parameters[0] ) )
                {
                    return array( eZTemplateNodeTool::createNumericElement( array_sum( eZTemplateNodeTool::elementConstantValue( $parameters[0] ) ) ) );
                }

                $values = array( $parameters[0] );
                $code = '%output% = array_sum( %1% );';

                return array( eZTemplateNodeTool::createCodePieceElement( $code, $values ) );
            } break;

            case $this->RepeatName:
            {
                $values = array();
                $isString = false;
                $isArray = false;

                if ( !eZTemplateNodeTool::isConstantElement( $parameters[0] ) )
                {
                    $values[] = $parameters[0];
                    $arrayCode = '%' . count( $values ) . '%';
                }
                else
                {
                    $arrayCode = eZPHPCreator::variableText( eZTemplateNodeTool::elementConstantValue( $parameters[0] ), 0, 0, false );
                    $isString = is_string( eZTemplateNodeTool::elementConstantValue( $parameters[0] ) );
                    $isArray = is_array( eZTemplateNodeTool::elementConstantValue( $parameters[0] ) );
                }

                if ( !eZTemplateNodeTool::isConstantElement( $parameters[1] ) )
                {
                    $values[] = $parameters[1];
                    $countCode = '%' . count( $values ) . '%';
                }
                else
                {
                    $count = (int)eZTemplateNodeTool::elementConstantValue( $parameters[1] );

                    if ( count( $values ) == 0 )
                    {
                        if ( $isString )
                        {
                            $retText = '';
                            $origText = eZTemplateNodeTool::elementConstantValue( $parameters[0] );
                            for ( $i = 0; $i < $count; $i++)
                            {
                                $retText .= $origText;
                            }

                            return array( eZTemplateNodeTool::createStringElement( $retText ) );
                        }
                        else if ( $isArray )
                        {
                            $retArray = array();
                            $origArray = eZTemplateNodeTool::elementConstantValue( $parameters[0] );
                            for ( $i = 0; $i < $count; $i++)
                            {
                                $retArray = array_merge( $retArray, $origArray );
                            }

                            return array( eZTemplateNodeTool::createArrayElement( $retArray ) );
                        }
                    }

                    $countCode = (string)$count;
                }

                $code = '%tmp2% = ' . $arrayCode . ';' . "\n" .
                     'if ( is_string( %tmp2% ) )' . "\n" .
                     '  %output% = \'\';' . "\n" .
                     'else if ( is_array(  %tmp2% ) )' . "\n" .
                     '  %output% = array();' . "\n" .
                     'for( %tmp1% = 0; %tmp1% < ' . $countCode . '; ++%tmp1% )' . "\n" .
                     '{' . "\n" .
                     '  if ( is_string( %tmp2% )  )' . "\n" .
                     '    %output% .= %tmp2%;' . "\n" .
                     '  else if ( is_array( %tmp2% ) )' . "\n" .
                     '    %output% = array_merge( %output%, %tmp2% );' . "\n" .
                     '}' . "\n";

                return array( eZTemplateNodeTool::createCodePieceElement( $code, $values, false, 2 ) );
            } break;
        }
    }

    function compareTrans( $operatorName, &$node, $tpl, &$resourceData,
                           $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        $isArray = false;
        $isString = false;
        $inParam = null;
        $inParamCode = '';
        $compareParams = array();
        $compareParamsCode = array();
        $offset = 0;
        $values = array();
        $tmpCount = 0;

        if ( eZTemplateNodeTool::isConstantElement( $parameters[0] ) )
        {
            $inParam = eZTemplateNodeTool::elementConstantValue( $parameters[0] );
            $inParamCode = eZPHPCreator::variableText( $inParam, 0, 0, false );
            $isString = is_string( $inParam );
            $isArray = is_array( $inParam );
        }
        else
        {
            $values[] = $parameters[0];
            $inParamCode = '%' . count( $values ) . '%';
        }

        for( $i = 1; $i < count( $parameters ); $i++ )
        {
            if ( eZTemplateNodeTool::isConstantElement( $parameters[$i] ) )
            {
                $compareParams[] = eZTemplateNodeTool::elementConstantValue( $parameters[$i] );
                $compareParamsCode[] = eZPHPCreator::variableText( eZTemplateNodeTool::elementConstantValue( $parameters[$i] ), 0, 0, false );
            }
            else
            {
                $values[] = $parameters[$i];
                $compareParamsCode[] = '%' . count( $values ) . '%';
            }
        }

        switch( $operatorName )
        {
            case $this->EndsWithName:
            {
                if ( count( $values ) == 0 )
                {
                    if ( $isString )
                    {
                        $result = ( mb_strrpos( $inParam, $compareParams[0] ) === ( mb_strlen( $inParam ) - mb_strlen ( $compareParams[0] ) ) );
                    }
                    else if ( $isArray )
                    {
                        $length = count( $inParam );
                        $params = count( $compareParams );
                        $start = $length - $params;

                        $result = true;
                        for ( $i = 0; $i < $params; ++$i )
                        {
                            if ( $inParam[$start + $i] != $compareParams[$i] )
                            {
                                $result = false;
                                break;
                            }
                        }
                    }

                    return array( eZTemplateNodeTool::createBooleanElement( $result ) );
                }

                if ( $isString )
                {
                    $code = '%output% = ( mb_strrpos( ' . $inParamCode . ', ' . $compareParamsCode[0] . ' ) === ( mb_strlen( ' . $inParamCode . ' ) - mb_strlen( ' . $compareParamsCode[0] . ' ) ) );';
                }
                else if ( $isArray )
                {
                    $code = '%tmp4% = ' . $inParamCode . ';' . "\n" .
                         '%tmp1% = count( %tmp4% );' . "\n" .
                         '%tmp2% = ' . count( $compareParamsCode  ) . ';' . "\n" .
                         '%tmp3% = %tmp1% - %tmp2%;' . "\n" .
                         '%output% = true;' . "\n";
                    for( $i = 0 ; $i < count( $compareParamsCode ); ++$i )
                    {
                        if( $i != 0 )
                            $code .= 'else ';
                        $code .= 'if ( %tmp4%[%tmp3% + ' . $i . '] != ' . $compareParamsCode[$i] . ')' . "\n" .
                             '  %output% = false;' . "\n";
                    }

                    $tmpCount = 4;
                }
                else
                {
                    $code = '%tmp4% = ' . $inParamCode . ';' . "\n" .
                         'if ( is_string( %tmp4% ) )' . "\n" .
                         '{' . "\n" .
                         '  %output% = ( mb_strrpos( %tmp4%, ' . $compareParamsCode[0] . ' ) === ( mb_strlen( %tmp4% ) - mb_strlen( ' . $compareParamsCode[0] . ' ) ) );' . "\n" .
                         '}' . "\n" .
                         'else if( is_array( %tmp4% ) )' . "\n" .
                         '{' . "\n" .
                         '  %tmp1% = count( %tmp4% );' . "\n" .
                         '  %tmp2% = ' . count( $compareParamsCode  ) . ';' . "\n" .
                         '  %tmp3% = %tmp1% - %tmp2%;' . "\n" .
                         '  %output% = true;' . "\n";
                    for( $i = 0 ; $i < count( $compareParamsCode ); ++$i )
                    {
                        if( $i != 0 )
                            $code .= '  else ';
                        $code .= 'if ( %tmp4%[%tmp3% + ' . $i . '] != ' . $compareParamsCode[$i] . ')' . "\n" .
                             '    %output% = false;' . "\n";
                    }
                    $code .= '}';

                    $tmpCount = 4;
                }

                return array( eZTemplateNodeTool::createCodePieceElement( $code, $values, false, $tmpCount ) );
            } break;

            case $this->BeginsWithName:
            {
                if ( count( $values ) == 0 )
                {
                    if ( $isString )
                    {
                        $result = ( mb_strpos ( $inParam, $compareParams[0] ) === 0 );
                    }
                    else if ( $isArray )
                    {
                        $result = true;
                        for ( $i = 0; $i < count( $compareParams ); ++$i )
                        {
                            if ( $inParam[$i] != $compareParams[$i] )
                            {
                                $result = false;
                                break;
                            }
                        }
                    }

                    return array( eZTemplateNodeTool::createBooleanElement( $result ) );
                }

                if ( $isString )
                {
                    $code = '%output% = ( ' . $compareParamsCode[0] . ' && mb_strpos( ' . $inParamCode . ', ' . $compareParamsCode[0] . ' ) === 0 );';
                }
                else if ( $isArray )
                {
                    $code = '%tmp1% = ' . $inParamCode . ';' . "\n" .
                         '%output% = true;' . "\n";
                    for( $i = 0 ; $i < count( $compareParamsCode ); ++$i )
                    {
                        if( $i != 0 )
                            $code .= 'else ';
                        $code .= 'if ( %tmp1%[' . $i . '] != ' . $compareParamsCode[$i] . ')' . "\n" .
                             '  %output% = false;' . "\n";
                    }

                    $tmpCount = 1;
                }
                else
                {
                    $code = '%tmp1% = ' . $inParamCode . ';' . "\n" .
                         'if ( is_string( %tmp1% ) )' . "\n" .
                         '{' . "\n" .
                         "  if ( {$compareParamsCode[0]} == '' )\n" .
                         "    %output% = false;\n" .
                         "  else\n" .
                         '    %output% = ( mb_strpos( %tmp1%, ' . $compareParamsCode[0] . ' ) === 0 );' . "\n" .
                         '}' . "\n" .
                         'else if( is_array( %tmp1% ) )' . "\n" .
                         '{' . "\n" .
                         '  %output% = true;' . "\n";
                    for( $i = 0 ; $i < count( $compareParamsCode ); ++$i )
                    {
                        if( $i != 0 )
                            $code .= '  else ';
                        $code .= 'if ( %tmp1%[' . $i . '] != ' . $compareParamsCode[$i] . ')' . "\n" .
                             '    %output% = false;' . "\n";
                    }
                    $code .= '}';

                    $tmpCount = 1;
                }

                return array( eZTemplateNodeTool::createCodePieceElement( $code, $values, false, $tmpCount ) );
            } break;
        }
    }

    function extractTrans( $operatorName, &$node, $tpl, &$resourceData,
                           $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        $offset = 0;
        $length = false;
        $values = array();
        $code = '';
        if ( $operatorName == $this->ExtractName )
        {
            if ( eZTemplateNodeTool::isConstantElement( $parameters[1] ) )
            {
                $offset = eZTemplateNodeTool::elementConstantValue( $parameters[1] );
                $code .= (string)$offset;
            }
            else
            {
                $values[] = $parameters[1];
                $code .= '%' . count ( $values ) . '%';
            }
        }
        else if ( $operatorName == $this->ExtractRightName )
        {
            if ( eZTemplateNodeTool::isConstantElement( $parameters[1] ) )
            {
                $offset = -1 * eZTemplateNodeTool::elementConstantValue( $parameters[1] );
                $code .= (string)$offset;
            }
            else
            {
                $values[] = $parameters[1];
                $code .= '-1 * %' . count ( $values ) . '%';
            }
        }
        else
        {
            $code .= '0';
        }

        if ( $operatorName == $this->ExtractName )
        {
            if ( isset( $parameters[2] ) and eZTemplateNodeTool::isConstantElement( $parameters[2] ) )
            {
                $length = eZTemplateNodeTool::elementConstantValue( $parameters[2] );
                $code .= ', ' . (string)$length;
            }
            else if ( isset( $parameters[2] ) )
            {
                $values[] = $parameters[2];
                $code .= ', ' . '%' . count ( $values ) . '%';
            }
        }
        else if ( $operatorName == $this->ExtractLeftName )
        {
            if ( eZTemplateNodeTool::isConstantElement( $parameters[1] ) )
            {
                $length = eZTemplateNodeTool::elementConstantValue( $parameters[1] );
                $code .= ', ' . (string)$length;
            }
            else
            {
                $values[] = $parameters[1];
                $code .= ', ' . '%' . count ( $values ) . '%';
            }
        }

        if ( eZTemplateNodeTool::isConstantElement( $parameters[0] ) )
        {
            if ( count( $values ) == 0 )
            {
                $input = eZTemplateNodeTool::elementConstantValue( $parameters[0] );
                if ( $operatorName == $this->ExtractRightName or !$length )
                {
                    if ( is_string( $input ) )
                        $output = mb_substr( $input, $offset );
                    else
                        $output = array_slice( $input, $offset );
                }
                else
                {
                    if ( is_string( $input ) )
                        $output = mb_substr( $input, $offset, $length );
                    else
                        $output = array_slice( $input, $offset, $length );
                }
                return array( eZTemplateNodeTool::createConstantElement( $output ) );
            }
            else
            {
                $code = '%output% = array_slice( ' . eZPHPCreator::variableText( eZTemplateNodeTool::elementConstantValue( $parameters[0] ), 0, 0, false ) . ', ' . $code . ' );';
            }
        }
        else
        {
            $values[] = $parameters[0];
            $code = ( "if ( is_string( %" . count( $values ) . "% ) )\n" .
                      "    %output% = mb_substr( %" . count( $values ) . "%, " . $code . " );\n" .
                      "else\n" .
                      "    %output% = array_slice( %" . count( $values ) . "%, " . $code . " );" );
        }

        return array( eZTemplateNodeTool::createCodePieceElement( $code, $values ) );
    }

    function mergeTrans( $operatorName, &$node, $tpl, &$resourceData,
                         $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        $code = '';
        $stringCode = '';

        $paramCount = 0;
        $values = array();
        $staticArray = array();
        for ( $i = 1; $i < count( $parameters ); ++$i )
        {
            if ( $i != 1 )
            {
                $code .= ', ';
                $stringCode .= ', ';
            }

            if ( !eZTemplateNodeTool::isConstantElement( $parameters[$i] ) )
            {
                $values[] = $parameters[$i];
                ++$paramCount;
                if ( $operatorName == $this->MergeName or
                     $operatorName == $this->ArrayMergeName )
                    $code .= '%' . $paramCount . '%';
                else
                    $code .= 'array( %' . $paramCount . '% )';
                $stringCode .= '%' . $paramCount . '%';
            }
            else
            {
                if ( $paramCount == 0 )
                {
                    $staticArray[] = eZTemplateNodeTool::elementConstantValue( $parameters[$i] );
                }
                if ( $operatorName == $this->MergeName or
                     $operatorName == $this->ArrayMergeName )
                    $code .= '' . eZPHPCreator::variableText( eZTemplateNodeTool::elementConstantValue( $parameters[$i] ), 0, 0, false ) . '';
                else
                {
                    $tmp_check = eZPHPCreator::variableText( eZTemplateNodeTool::elementConstantValue( $parameters[$i] ), 0, 0, false );
                    // hiding "%1%", "%output%" etc. in static input string to avoid replacing it on "$var" inside compiler.
                    $tmp_check = str_replace( "%", '"."%"."', $tmp_check );
                    $code .= 'array( ' . $tmp_check . ' )';
                }
                $stringCode .= eZPHPCreator::variableText( eZTemplateNodeTool::elementConstantValue( $parameters[$i] ), 0, 0, false );
            }
        }

        $isString = false;
        $isArray = false;
        $code2 = false;
        if ( $parameters[0] )
        {
            if ( !eZTemplateNodeTool::isConstantElement( $parameters[0] ) )
            {
                $values[] = $parameters[0];
                ++$paramCount;
                $code2 = '%' . $paramCount . '%';
            }
            else
            {
                $isString = is_string( eZTemplateNodeTool::elementConstantValue( $parameters[0] ) );
                $isArray = is_array( eZTemplateNodeTool::elementConstantValue( $parameters[0] ) );
                if ( $paramCount == 0 )
                {
//                    $staticArray[] = eZTemplateNodeTool::elementConstantValue( $parameters[0] );
                }
                else
                {
                    $code2 = eZPHPCreator::variableText( eZTemplateNodeTool::elementConstantValue( $parameters[0] ), 0, 0, false );
                }
            }
        }

        if ( $paramCount == 0 )
        {
            if ( $operatorName == $this->AppendName or
                 $operatorName == $this->ArrayAppendName or
                 $operatorName == $this->MergeName or
                 $operatorName == $this->ArrayMergeName )
            {
                if ( $isString )
                {
                    $str = eZTemplateNodeTool::elementConstantValue( $parameters[0] );
                    for( $i = 0; $i < count( $staticArray ); ++$i )
                    {
                        $str .= $staticArray[$i];
                    }

                    return array( eZTemplateNodeTool::createStringElement( $str ) );
                }
                else if ( $isArray )
                {
                    $returnArray = eZTemplateNodeTool::elementConstantValue( $parameters[0] );
                    for( $i = 0; $i < count( $staticArray ); ++$i )
                    {
                        if ( is_array( $staticArray[$i] ) )
                        {
                            $returnArray = array_merge( $returnArray, $staticArray[$i] );
                        }
                        else
                        {
                            $returnArray[] = $staticArray[$i];
                        }
                    }
                    return array( eZTemplateNodeTool::createArrayElement( $returnArray ) );
                }
            }
            else if ( $operatorName == $this->PrependName or
                      $operatorName == $this->ArrayPrependName )
            {
                if ( $isString )
                {
                    return array( eZTemplateNodeTool::createStringElement( eZTemplateNodeTool::elementConstantValue( $parameters[1] ) . eZTemplateNodeTool::elementConstantValue( $parameters[0] ) ) );
                }
                else if ( $isArray )
                {
                    return array( eZTemplateNodeTool::createArrayElement( array_merge( $staticArray, eZTemplateNodeTool::elementConstantValue( $parameters[0] ) ) ) );
                }
            }
        }

        if ( $code2 )
        {
            if ( $operatorName == $this->AppendName )
            {
                $code = ( 'if ( is_string( ' . $code2 . ' ) )' . "\n" .
                          '    %output% = ' . $code2 . ' . implode( \'\', array( ' . $stringCode . ' ) );' . "\n" .
                          'else if( is_array( ' . $code2 . ' ) )' . "\n" .
                          '    %output% = array_merge( ' . $code2 . ', ' . $code . ' );' );
            }
            else if ( $operatorName == $this->ArrayAppendName )
            {
                $code = '%output% = array_merge( ' . $code2 . ', ' . $code . ' );';
            }
            else if ( $operatorName == $this->MergeName )
            {
                $code = '%output% = array_merge( ' . $code2 . ', ' . $code . ' );';
            }
            else if ( $operatorName == $this->ArrayMergeName )
            {
                $code = '%output% = array_merge( ' . $code2 . ', ' . $code . ' );';
            }
            else if ( $operatorName == $this->PrependName )
            {
                $code = ( 'if ( is_string( ' . $code2 . ' ) )' . "\n" .
                          '    %output% = implode( \'\', array( ' . $stringCode . ' ) ) . ' . $code2 . ';' . "\n" .
                          'else if( is_array( ' . $code2 . ' ) )' . "\n" .
                          '    %output% = array_merge( ' . $code . ', ' . $code2 . ' );' );
            }
            else if ( $operatorName == $this->ArrayPrependName )
            {
                $code = '%output% = array_merge( ' . $code . ', ' . $code2 . ' );';
            }
        }
        else
        {
            if ( $operatorName == $this->MergeName )
            {
                $code = '%output% = array_merge( ' . $code . ' );';
            }
            else
            {
                $code = '%output% = array(' . $code . ');';
            }
        }

        return array( eZTemplateNodeTool::createCodePieceElement( $code . "\n", $values ) );
    }

    /*!
     \return true to tell the template engine that the parameter list exists per operator type.
    */
    function namedParameterPerOperator()
    {
        return true;
    }
    /*!
     See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {
        return array( $this->RemoveName  => array( 'offset'            => array( "type"      => "integer",
                                                                                 "required"  => true,
                                                                                 "default"   => false ),
                                                   'length'            => array( "type"      => "integer",
                                                                                 "required"  => false,
                                                                                 "default"   => 1 ) ),
                      $this->RepeatName  => array( 'repeat_times'      => array( "type"      => "integer",
                                                                                 "required"  => false,
                                                                                 "default"   => 1 ) ),
                      $this->InsertName  => array( 'insert_position'   => array( "type"      => "integer",
                                                                                 "required"  => true,
                                                                                 "default"   => false ),
                                                   'insert_string'     => array( "type"      => "string",
                                                                                 "required"  => true,
                                                                                 "default"   => false ) ),
                      $this->ExplodeName => array( 'explode_first'     => array( "type"      => "mixed",
                                                                                 "required"  => true,
                                                                                 "default"   => false ) ),
                      $this->ExtractName => array( 'extract_start'     => array( "type"      => "integer",
                                                                                 "required"  => true,
                                                                                 "default"   => false ),
                                                   'extract_length'    => array( "type"      => "integer",
                                                                                 "required"  => false,
                                                                                 "default"   => false ) ),
                      $this->ExtractLeftName  => array( 'length'       => array( "type"      => "integer",
                                                                                 "required"  => true,
                                                                                 "default"   => false ) ),
                      $this->ExtractRightName => array( 'length'       => array( "type"      => "integer",
                                                                                 "required"  => true,
                                                                                 "default"   => false ) ),
                      $this->ReplaceName      => array( 'offset'       => array( "type"      => "integer",
                                                                                 "required"  => true,
                                                                                 "default"   => false),
                                                        'length'       => array( "type"      => "integer",
                                                                                 "required"  => false,
                                                                                 "default"   => false) ),
                      $this->PrependName    => array( 'prepend_string' => array( "type"      => "string",
                                                                                 "required"  => false,
                                                                                 "default"   => false ) ),
                      $this->ContainsName   => array( 'match'          => array( "type"      => "string",
                                                                                 "required"  => true,
                                                                                 "default"   => false ) ),
                      $this->BeginsWithName => array( 'match'          => array( "type"      => "string",
                                                                                 "required"  => true,
                                                                                 "default"   => false ) ),
                      $this->EndsWithName   => array( 'match'          => array( "type"      => "string",
                                                                                 "required"  => true,
                                                                                 "default"   => false ) ),
                      $this->ImplodeName    => array( 'separator'      => array( "type"      => "string",
                                                                                 "required"  => true,
                                                                                 "default"   => false) ),
                      $this->CompareName    => array( 'compare'        => array( "type"      => "mixed",
                                                                                 "required"  => true,
                                                                                 "default"   => false ) ) );
    }

    function modify( $tpl, $operatorName, $operatorParameters,
                     $rootNamespace, $currentNamespace, &$operatorValue,
                     $namedParameters, $placement )
    {
        switch( $operatorName )
        {
            case $this->ArrayName:
            {
                $operatorValue = array();
                for ( $i = 0; $i < count( $operatorParameters ); ++$i )
                {
                    $operatorValue[] = $tpl->elementValue( $operatorParameters[$i],
                                                            $rootNamespace,
                                                            $currentNamespace,
                                                            $placement );
                }
                return;
            }break;

            case $this->HashName:
            {
                $operatorValue = array();
                $hashCount = (int)( count( $operatorParameters ) / 2 );
                for ( $i = 0; $i < $hashCount; ++$i )
                {
                    $hashName = $tpl->elementValue( $operatorParameters[$i*2],
                                                    $rootNamespace,
                                                    $currentNamespace,
                                                    $placement );
                    if ( is_string( $hashName ) or
                         is_numeric( $hashName ) )
                        $operatorValue[$hashName] = $tpl->elementValue( $operatorParameters[($i*2)+1],
                                                                         $rootNamespace,
                                                                         $currentNamespace,
                                                                         $placement );
                    else
                        $tpl->error( $operatorName,
                                     "Unknown hash key type '" . gettype( $hashName ) . "', skipping",
                                     $placement );
                }
                return;
            }
            break;

            case $this->ArraySumName:
            {
                if ( is_array( $operatorValue ) )
                {
                    $operatorValue = array_sum( $operatorValue );
                }
                else
                {
                    $tpl->error( $operatorName,
                                 "Unknown input type, can only work with arrays '" . gettype( $operatorValue ) . "'",
                                 $placement );
                }
                return;
            }
            break;
        }

        $isArray = false;
        if ( isset( $operatorParameters[0] ) and
             is_array( $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement ) ) )
            $isArray = true;

        if ( is_array( $operatorValue ) )
            $isArray = true;

        if ( $isArray )
        {
            switch( $operatorName )
            {
                // Append or prepend an array (or single elements) to the target array:
                case $this->ArrayPrependName:
                case $this->ArrayAppendName:
                case $this->PrependName:
                case $this->AppendName:
                {
                    $i = 0;
                    if ( is_array( $operatorValue ) )
                    {
                        if ( count( $operatorParameters ) < 1 )
                        {
                            $tpl->error( $operatorName,
                                         "Requires at least one item!",
                                         $placement );
                            return;
                        }
                        $mainArray = $operatorValue;
                    }
                    else
                    {
                        if ( count( $operatorParameters ) < 2 )
                        {
                            $tpl->error( $operatorName,
                                         "Requires an array (and at least one item)!",
                                         $placement );
                            return;
                        }
                        $mainArray = $tpl->elementValue( $operatorParameters[$i++],
                                                          $rootNamespace,
                                                          $currentNamespace,
                                                          $placement );
                    }
                    $tmpArray = array();
                    for ( ; $i < count( $operatorParameters ); ++$i )
                    {
                        $tmpArray[] = $tpl->elementValue( $operatorParameters[$i],
                                                           $rootNamespace,
                                                           $currentNamespace,
                                                           $placement );
                    }
                    if ( $operatorName == $this->ArrayPrependName or $operatorName == $this->PrependName )
                        $operatorValue = array_merge( $tmpArray, $mainArray );
                    else
                        $operatorValue = array_merge( $mainArray, $tmpArray );

                }
                break;

                // Merge two arrays:
                case $this->ArrayMergeName:
                case $this->MergeName:
                {
                    $tmpArray   = array();
                    if ( $operatorValue === null ) {
                        $tmpArray[] = array(); // set to empty array in case of
                    } else {
                        $tmpArray[] = $operatorValue;
                    }

                    if ( count( $operatorParameters ) < 1 )
                    {
                        $tpl->error( $operatorName, "Requires an array (and at least one item!)",
                                     $placement );
                        return;
                    }

                    for ( $i = 0; $i < count( $operatorParameters ); ++$i )
                    {
                        $tmpVal = $tpl->elementValue( $operatorParameters[$i],
                                                           $rootNamespace,
                                                           $currentNamespace,
                                                           $placement );
                        if ( $tmpVal !== null )
                        {
                            $tmpArray[] = $tmpVal;
                        }
                    }
                    $operatorValue = call_user_func_array( 'array_merge', $tmpArray );
                }break;

                // Check if the array contains a specified element:
                case $this->ContainsName:
                {
                    if ( count( $operatorParameters ) < 1 )
                    {
                        $tpl->error( $operatorName, "Missing matching value!",
                                     $placement );
                        return;
                    }
                    $matchValue = $tpl->elementValue( $operatorParameters[0],
                                                       $rootNamespace,
                                                       $currentNamespace,
                                                       $placement );

                    $operatorValue = in_array( $matchValue, $operatorValue );
                }
                break;

                // Compare two arrays:
                case $this->CompareName:
                {
                    $operatorValue = ( count( array_diff( $operatorValue, $namedParameters['compare'] ) ) == 0 and
                                       count( array_diff( $namedParameters['compare'], $operatorValue ) ) == 0 );
                }
                break;

                // Extract a portion of the array:
                case $this->ExtractName:
                {
                    if ( $namedParameters['extract_length'] === false )
                        $operatorValue = array_slice( $operatorValue, $namedParameters['extract_start'] );
                    else
                        $operatorValue = array_slice( $operatorValue, $namedParameters['extract_start'], $namedParameters['extract_length'] );
                }
                break;

                // Extract a portion from the start of the array:
                case $this->ExtractLeftName:
                {
                    $operatorValue = array_slice( $operatorValue, 0,  $namedParameters['length'] );
                }break;

                // Extract a portion from the end of the array:
                case $this->ExtractRightName:
                {
                    $index = count( $operatorValue ) - $namedParameters['length'];
                    $operatorValue = array_slice( $operatorValue, $index );
                }break;

                // Check if the array begins with a given sequence:
                case $this->BeginsWithName:
                {
                    for ( $i = 0; $i < count( $operatorParameters ); $i++ )
                    {
                        $test = $tpl->elementValue( $operatorParameters[$i],
                                                    $rootNamespace,
                                                    $currentNamespace,
                                                    $placement );

                        if ( $operatorValue[$i] != $test )
                        {
                            $operatorValue = false;
                            return;
                        }
                    }

                    $operatorValue = true;
                }break;

                // Check if the array ends with a given sequence:
                case $this->EndsWithName:
                {
                    $length = count( $operatorValue );
                    $params = count( $operatorParameters );

                    $start = $length - $params;

                    for ( $i = 0; $i < $params; $i++ )
                    {
                        $test = $tpl->elementValue( $operatorParameters[$i],
                                                    $rootNamespace,
                                                    $currentNamespace,
                                                    $placement );

                        if ( $operatorValue[$start+$i] != $test )
                        {
                            $operatorValue = false;
                            return;
                        }
                    }
                    $operatorValue = true;
                }break;

                // Create a string containing the array elements with the separator string between elements.
                case $this->ImplodeName:
                {
                    $operatorValue = implode( (string)$namedParameters['separator'], (array)$operatorValue );
                }break;

                // Explode the array by making smaller arrays of it:
                case $this->ExplodeName:
                {
                    $array_one = array();
                    $array_two = array();

                    $array_one = array_slice( $operatorValue, 0, $namedParameters['explode_first'] );
                    $array_two = array_slice( $operatorValue, $namedParameters['explode_first'] );

                    $operatorValue = array( $array_one, $array_two );
                }break;

                // Repeat the contents of an array a specified number of times:
                case $this->RepeatName:
                {
                    $arrayElement = $operatorValue;
                    $count = $namedParameters['repeat_times'];
                    $operatorValue = array();
                    for ( $i = 0; $i < $count; $i++)
                    {
                        $operatorValue = array_merge( $operatorValue, $arrayElement );
                    }
                }break;

                // Reverse the contents of the array:
                case $this->ReverseName:
                {
                    $operatorValue = array_reverse( $operatorValue );
                }break;

                // Insert an array (or element) into a position in the target array:
                case $this->InsertName:
                {
                    $array_one = array_slice( $operatorValue, 0, $namedParameters['insert_position'] );
                    $array_two = array_slice( $operatorValue, $namedParameters['insert_position'] );


                    $array_to_insert = array();
                    for ( $i = 1; $i < count( $operatorParameters ); ++$i )
                    {
                        $array_to_insert[] = $tpl->elementValue( $operatorParameters[$i],
                                                                  $rootNamespace,
                                                                  $currentNamespace,
                                                                  $placement );
                    }

                    $operatorValue = array_merge( $array_one, $array_to_insert, $array_two );
                }break;

                // Remove a specified element (or portion) from the target array:
                case $this->RemoveName:
                {
                    $array_one = array_slice( $operatorValue, 0, $namedParameters['offset'] );
                    $array_two = array_slice( $operatorValue, $namedParameters['offset'] + $namedParameters['length'] );

                    $operatorValue = array_merge( $array_one, $array_two );
                }break;

                // Replace a portion of the array:
                case $this->ReplaceName:
                {
                    $array_one = array_slice( $operatorValue, 0, $namedParameters['offset'] );
                    $array_two = array_slice( $operatorValue, $namedParameters['offset'] + $namedParameters['length'] );
                    $array_mid = array();

                    for ( $i = 2; $i < count( $operatorParameters ); ++ $i )
                    {
                        $array_mid[] = $tpl->elementValue( $operatorParameters[$i],
                                                            $rootNamespace,
                                                            $currentNamespace,
                                                            $placement );
                    }

                    $operatorValue = array_merge( $array_one, $array_mid, $array_two );
                } break;

                // Removes duplicate values from array:
                case $this->UniqueName:
                {
                    $operatorValue = array_unique( $operatorValue );
                }break;

                // Default case:
                default:
                {
                    $tpl->warning( $operatorName, "Unknown operatorname: $operatorName", $placement );
                }
                break;
            }
        }
        else if ( is_string( $operatorValue ) )
        {
            switch( $operatorName )
            {
                // Not implemented.
                case $this->ArrayName:
                {
                    $tpl->warning( $operatorName, "$operatorName works only with arrays.", $placement );
                }break;

                // Not implemented.
                case $this->HashName:
                {
                    $tpl->warning( $operatorName, "$operatorName works only with arrays.", $placement );
                }
                break;

                // Add a string at the beginning of the input/target string:
                case $this->PrependName:
                {
                    $operatorValue = $namedParameters['prepend_string'].$operatorValue;
                }break;

                // Add a string at the end of the input/target string:
                case $this->AppendName:
                {
                    for ( $i = 0; $i < count( $operatorParameters ); ++$i )
                    {
                        $operatorValue .= $tpl->elementValue( $operatorParameters[$i],
                                                              $rootNamespace,
                                                              $currentNamespace,
                                                              $placement );
                    }

                }break;

                // Not implemented.
                case $this->MergeName:
                {
                    $tpl->warning( $operatorName, "$operatorName works only with arrays.", $placement );
                }break;

                // Check if the string contains a specified sequence of chars/string.
                case $this->ContainsName:
                {
                    $operatorValue = ( mb_strpos( $operatorValue, $namedParameters['match'] ) !== false );
                }
                break;

                // Compare two strings:
                case $this->CompareName:
                {
                    if ( strcmp( $operatorValue, $namedParameters['compare'] ) === 0 )
                    {
                        $operatorValue = true;
                    }
                    else
                    {
                        $operatorValue = false;
                    }
                }
                break;

                // Extract a portion from/of a string:
                case $this->ExtractName:
                {
                    if ( $namedParameters['extract_length'] === false )
                        $operatorValue = mb_substr( $operatorValue, $namedParameters['extract_start'] );
                    else
                        $operatorValue = mb_substr( $operatorValue, $namedParameters['extract_start'], $namedParameters['extract_length'] );
                }
                break;

                // Extract string/portion from the start of the string.
                case $this->ExtractLeftName:
                {
                    $operatorValue = mb_substr( $operatorValue, 0, $namedParameters['length'] );
                }break;

                // Extract string/portion from the end of the string.
                case $this->ExtractRightName:
                {
                    $offset  = mb_strlen( $operatorValue ) - $namedParameters['length'];
                    $operatorValue = mb_substr( $operatorValue, $offset );
                }break;

                // Check if string begins with specified sequence:
                case $this->BeginsWithName:
                {
                    if ( mb_strpos( $operatorValue, $namedParameters['match'] ) === 0 )
                    {
                        $operatorValue = true;
                    }
                    else
                    {
                        $operatorValue = false;
                    }
                }break;

                // Check if string ends with specified sequence:
                case $this->EndsWithName:
                {
                    if ( mb_strrpos( $operatorValue, $namedParameters['match'] ) === ( mb_strlen( $operatorValue ) - mb_strlen ($namedParameters['match'] ) ) )
                    {
                        $operatorValue = true;
                    }
                    else
                    {
                        $operatorValue = false;
                    }
                }break;

                // Only works with arrays.
                case $this->ImplodeName:
                {
                    $tpl->warning( $operatorName, "$operatorName only works with arrays", $placement );
                }break;

                // Explode string (split a string by string).
                case $this->ExplodeName:
                {
                    $operatorValue = explode( $namedParameters['explode_first'], $operatorValue );
                }break;

                // Repeat string n times:
                case $this->RepeatName:
                {
                    $operatorValue = str_repeat( $operatorValue, $namedParameters['repeat_times'] );
                }break;

                // Reverse contents of string:
                case $this->ReverseName:
                {
                    $operatorValue = strrev( $operatorValue );
                }break;

                // Insert a given string at a specified position:
                case $this->InsertName:
                {
                    $first  = mb_substr( $operatorValue, 0, $namedParameters['insert_position'] );
                    $second = mb_substr( $operatorValue, $namedParameters['insert_position'] );
                    $operatorValue = $first . $namedParameters['insert_string'] . $second;
                }break;

                // Remove a portion from a string:
                case $this->RemoveName:
                {
                    $first  = mb_substr( $operatorValue, 0, $namedParameters['offset'] );
                    $second = mb_substr( $operatorValue, $namedParameters['offset'] + $namedParameters['length'] );
                    $operatorValue = $first . $second;
                }break;

                // Replace a portion of a string:
                case $this->ReplaceName:
                {
                    $first  = mb_substr( $operatorValue, 0, $namedParameters['offset'] );
                    $second = mb_substr( $operatorValue, $namedParameters['offset'] + $namedParameters['length'] );
                    $mid = '';

                    for ( $i = 2; $i < count( $operatorParameters ); ++ $i )
                    {
                        $mid .= $tpl->elementValue( $operatorParameters[$i],
                                                    $rootNamespace,
                                                    $currentNamespace,
                                                    $placement );
                    }

                    $operatorValue = $first . $mid . $second;
                }break;

                // Not implemented.
                case $this->UniqueName:
                {
                    $tpl->warning( $operatorName, "$operatorName works only with arrays.", $placement );
                }break;

                // Default case:
                default:
                {
                    $tpl->warning( $operatorName, "Unknown operatorname: $operatorName", $placement );
                }
                break;
            }
        }
        // ..or something else? -> We're building the array:
        else
        {
            $operatorValue = array();
            for ( $i = 0; $i < count( $operatorParameters ); ++$i )
            {
                $operatorValue[] = $tpl->elementValue( $operatorParameters[$i],
                                                        $rootNamespace,
                                                        $currentNamespace,
                                                        $placement );
            }
        }
    }

    /// \privatesection
    public $Operators;
    public $ArrayName;
    public $HashName;
}

?>
