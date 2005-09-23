<?php
//
// Definition of eZPHPCreator class
//
// Created on: <28-Nov-2002 08:28:23 amos>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezphpcreator.php
*/

/*!
  \class eZPHPCreator ezphpcreator.php
  \ingroup eZUtils
  \brief eZPHPCreator provides a simple interface for creating and executing PHP code.

  To create PHP code you must create an instance of this class,
  add any number of elements you choose with
  addDefine(), addVariable(), addVariableUnset(), addVariableUnsetList(),
  addSpace(), addText(), addMethodCall(), addCodePiece(), addComment() and
  addInclude().
  After that you call store() to write all changes to disk.

\code
$php = new eZPHPCreator( 'cache', 'code.php' );

$php->addComment( 'Auto generated' );
$php->addInclude( 'inc.php' );
$php->addVariable( 'count', 10 );

$php->store();
\endcode

  To restore PHP code you must create an instance of this class,
  check if you can restore it with canRestore() then restore variables with restore().
  The class will include PHP file and run all code, once the file is done it will
  catch any variables you require and return it to you.

\code
$php = new eZPHPCreator( 'cache', 'code.php' );

if ( $php->canRestore() )
{
    $variables = $php->restore( array( 'max_count' => 'count' ) );
    print( "Max count was " . $variables['max_count'] );
}

$php->close();
\endcode

*/

define( 'EZ_PHPCREATOR_VARIABLE', 1 );
define( 'EZ_PHPCREATOR_SPACE', 2 );
define( 'EZ_PHPCREATOR_TEXT', 3 );
define( 'EZ_PHPCREATOR_METHOD_CALL', 4 );
define( 'EZ_PHPCREATOR_CODE_PIECE', 5 );
define( 'EZ_PHPCREATOR_EOL_COMMENT', 6 );
define( 'EZ_PHPCREATOR_INCLUDE', 7 );
define( 'EZ_PHPCREATOR_VARIABLE_UNSET', 8 );
define( 'EZ_PHPCREATOR_DEFINE', 9 );
define( 'EZ_PHPCREATOR_VARIABLE_UNSET_LIST', 10 );
define( 'EZ_PHPCREATOR_RAW_VARIABLE', 11 );

define( 'EZ_PHPCREATOR_VARIABLE_ASSIGNMENT', 1 );
define( 'EZ_PHPCREATOR_VARIABLE_APPEND_TEXT', 2 );
define( 'EZ_PHPCREATOR_VARIABLE_APPEND_ELEMENT', 3 );

define( 'EZ_PHPCREATOR_INCLUDE_ONCE', 1 );
define( 'EZ_PHPCREATOR_INCLUDE_ALWAYS', 2 );

define( 'EZ_PPCREATOR_METHOD_CALL_PARAMETER_VALUE', 1 );
define( 'EZ_PHPCREATOR_METHOD_CALL_PARAMETER_VARIABLE', 2 );

class eZPHPCreator
{
    /*!
     Initializes the creator with the directory path \a $dir and filename \a $file.
    */
    function eZPHPCreator( $dir, $file, $prefix = '', $options = array() )
    {
        $this->PHPDir = $dir;
        $this->PHPFile = $file;
        $this->FilePrefix = $prefix;
        $this->FileResource = false;
        $this->Elements = array();
        $this->TextChunks = array();
        $this->TemporaryCounter = 0;
        if ( isset( $options['spacing'] ) and ( $options['spacing'] == 'disabled') )
        {
            $this->Spacing = false;
        }
        else
        {
            $this->Spacing = true;
        }
    }

    //@{

    /*!
     Adds a new define statement to the code with the name \a $name and value \a $value.
     The parameter \a $caseSensitive determines if the define should be made case sensitive or not.

     Example:
     \code
$php->addDefine( 'MY_CONSTANT', 5 );
     \endcode

     Would result in the PHP code.

     \code
define( 'MY_CONSTANT', 5 );
     \endcode

     \param $parameters Optional parameters, can be any of the following:
            - \a spacing, The number of spaces to place before each code line, default is \c 0.

     \note \a $name must start with a letter or underscore, followed by any number of letters, numbers, or underscores.
           See http://php.net/manual/en/language.constants.php for more information.
     \sa http://php.net/manual/en/function.define.php
    */
    function addDefine( $name, $value, $caseSensitive = true, $parameters = array() )
    {
        $element = array( EZ_PHPCREATOR_DEFINE,
                          $name,
                          $value,
                          $caseSensitive,
                          $parameters );
        $this->Elements[] = $element;
    }

    /*!
     Adds a new raw variable tothe code with the name \a $name and value \a $value.

     Example:
     \code
$php->addVariable( 'TransLationRoot', $cache['root'] );
     \endcode

     This function makes use of PHP's var_export() function which is optimized
     for this task.
    */
    function addRawVariable( $name, $value )
    {
        $element = array( EZ_PHPCREATOR_RAW_VARIABLE, $name, $value );
        $this->Elements[] = $element;
    }

    /*!
     Adds a new variable to the code with the name \a $name and value \a $value.

     Example:
     \code
$php->addVariable( 'offset', 5  );
$php->addVariable( 'text', 'some more text', EZ_PHPCREATOR_VARIABLE_APPEND_TEXT );
$php->addVariable( 'array', 42, EZ_PHPCREATOR_VARIABLE_APPEND_ELEMENT );
     \endcode

     Would result in the PHP code.

     \code
$offset = 5;
$text .= 'some more text';
$array[] = 42;
     \endcode

     \param $assignmentType Controls the way the value is assigned, choose one of the following:
            - \b EZ_PHPCREATOR_VARIABLE_ASSIGNMENT, assign using \c = (default)
            - \b EZ_PHPCREATOR_VARIABLE_APPEND_TEXT, append using text concat operator \c .
            - \b EZ_PHPCREATOR_VARIABLE_APPEND_ELEMENT, append element to array using append operator \c []
     \param $parameters Optional parameters, can be any of the following:
            - \a spacing, The number of spaces to place before each code line, default is \c 0.
            - \a full-tree, Whether to displays array values as one large expression (\c true) or
                            split it up into multiple variables (\c false)

    */
    function addVariable( $name, $value, $assignmentType = EZ_PHPCREATOR_VARIABLE_ASSIGNMENT,
                          $parameters = array() )
    {
        $element = array( EZ_PHPCREATOR_VARIABLE,
                          $name,
                          $value,
                          $assignmentType,
                          $parameters );
        $this->Elements[] = $element;
    }

    /*!
     Adds code to unset a variable with the name \a $name.

     Example:
     \code
$php->addVariableUnset( 'offset' );
     \endcode

     Would result in the PHP code.

     \code
unset( $offset );
     \endcode

     \param $parameters Optional parameters, can be any of the following:
            - \a spacing, The number of spaces to place before each code line, default is \c 0.

     \sa http://php.net/manual/en/function.unset.php
    */
    function addVariableUnset( $name,
                               $parameters = array() )
    {
        $element = array( EZ_PHPCREATOR_VARIABLE_UNSET,
                          $name,
                          $parameters );
        $this->Elements[] = $element;
    }

    /*!
     Adds code to unset a list of variables with name from \a $list.

     Example:
     \code
$php->addVariableUnsetList( array ( 'var1', 'var2' ) );
     \endcode

     Would result in the PHP code.

     \code
unset( $var1, $var2 );
     \endcode

     \param $parameters Optional parameters, can be any of the following:
            - \a spacing, The number of spaces to place before each code line, default is \c 0.

     \sa http://php.net/manual/en/function.unset.php
    */
    function addVariableUnsetList( $list,
                               $parameters = array() )
    {
        $element = array( EZ_PHPCREATOR_VARIABLE_UNSET_LIST,
                          $list,
                          $parameters );
        $this->Elements[] = $element;
    }

    /*!
     Adds some space to the code in form of newlines. The number of lines
     is controlled with \a $lines which is \c 1 by default.
     You can use this to get more readable PHP code.

     Example:
     \code
$php->addSpace( 1 );
     \endcode
    */
    function addSpace( $lines = 1 )
    {
        $element = array( EZ_PHPCREATOR_SPACE,
                          $lines );
        $this->Elements[] = $element;
    }

    /*!
     Adds some plain text to the code. The text will be placed
     outside of PHP start and end markers and will in principle
     work as printing the text.

     Example:
     \code
$php->addText( 'Print me!' );
     \endcode
    */
    function addText( $text )
    {
        $element = array( EZ_PHPCREATOR_TEXT,
                          $text );
        $this->Elements[] = $element;
    }

    /*!
     Adds code to call the method \a $methodName on the object named \a $objectName,
     \a $methodParameters should be an array with parameter entries where each entry contains:
     - \a 0, The parameter value
     - \a 1 (\em optional), The type of parameter, is one of:
       - \b EZ_PPCREATOR_METHOD_CALL_PARAMETER_VALUE, Use value directly (default if this entry is missing)
       - \b EZ_PHPCREATOR_METHOD_CALL_PARAMETER_VARIABLE, Use value as the name of the variable.

     Optionally the \a $returnValue parameter can be used to decide what should be done
     with the return value of the method call. It can either be \c false which means
     to do nothing or an array with the following entries.
     - \a 0, The name of the variable to assign the value to
     - \a 1 (\em optional), The type of assignment, uses the same value as addVariable().

     \param $parameters Optional parameters, can be any of the following:
            - \a spacing, The number of spaces to place before each code line, default is \c 0.

     Example:
     \code
$php->addMethodCall( 'node', 'name', array(), array( 'name' ) );
$php->addMethodCall( 'php', 'addMethodCall',
                     array( array( 'node' ), array( 'name' ) ) );
     \endcode

     Would result in the PHP code.

     \code
$name = $node->name();
$php->addMethodCall( 'node', 'name' );
     \endcode
    */
    function addMethodCall( $objectName, $methodName, $methodParameters, $returnValue = false, $parameters = array() )
    {
        $element = array( EZ_PHPCREATOR_METHOD_CALL,
                          $objectName,
                          $methodName,
                          $methodParameters,
                          $returnValue,
                          $parameters );
        $this->Elements[] = $element;
    }

    /*!
     Adds custom PHP code to the file, you should only use this a last resort if any
     of the other \em add functions done give you the required result.

     \param $code Contains the code as text, the text will not be modified (except for spacing).
                  This means that each expression must be ended with a newline even if it's just one.
     \param $parameters Optional parameters, can be any of the following:
            - \a spacing, The number of spaces to place before each code line, default is \c 0.

     Example:
     \code
$php->addCodePiece( "if ( \$value > 2 )\n{\n    \$value = 2;\n}\n" );
     \endcode

     Would result in the PHP code.

     \code
if ( $value > 2 )
{
    $value = 2;
}
     \endcode

    */
    function addCodePiece( $code, $parameters = array() )
    {
        $element = array( EZ_PHPCREATOR_CODE_PIECE,
                          $code,
                          $parameters );
        $this->Elements[] = $element;
    }

    /*!
     Adds a comment to the code, the comment will be display using multiple end-of-line
     comments (//), one for each newline in the text \a $comment.

     \param $eol Whether to add a newline at the last comment line
     \param $whitespaceHandling Whether to remove trailing whitespace from each line
     \param $parameters Optional parameters, can be any of the following:
            - \a spacing, The number of spaces to place before each code line, default is \c 0.

     Example:
     \code
$php->addComment( "This file is auto generated\nDo not edit!" );
     \endcode

     Would result in the PHP code.

     \code
// This file is auto generated
// Do not edit!
     \endcode

    */
    function addComment( $comment, $eol = true, $whitespaceHandling = true, $parameters = array() )
    {
        $element = array( EZ_PHPCREATOR_EOL_COMMENT,
                          $comment,
                          array_merge( $parameters,
                                       array( 'eol' => $eol,
                                              'whitespace-handling' => $whitespaceHandling ) ) );
        $this->Elements[] = $element;
    }

    /*!
     Adds an include statement to the code, the file to include is \a $file.

     \param $type What type of include statement to use, can be one of the following:
                  - \b EZ_PHPCREATOR_INCLUDE_ONCE, use \em include_once()
                  - \b EZ_PHPCREATOR_INCLUDE_ALWAYS, use \em include()
     \param $parameters Optional parameters, can be any of the following:
            - \a spacing, The number of spaces to place before each code line, default is \c 0.

     Example:
     \code
$php->addInclude( 'lib/ezutils/classes/ezphpcreator.php' );
     \endcode

     Would result in the PHP code.

     \code
include_once( 'lib/ezutils/classes/ezphpcreator.php' );
     \endcode

    */
    function addInclude( $file, $type = EZ_PHPCREATOR_INCLUDE_ONCE, $parameters = array() )
    {
        $element = array( EZ_PHPCREATOR_INCLUDE,
                          $file,
                          $type,
                          $parameters );
        $this->Elements[] = $element;
    }

    //@}

    /*!
     \static
     Creates a variable statement with an assignment type and returns it.
     \param $variableName The name of the variable
     \param $assignmentType What kind of assignment to use, is one of the following;
                            - \b EZ_PHPCREATOR_VARIABLE_ASSIGNMENT, assign using \c =
                            - \b EZ_PHPCREATOR_VARIABLE_APPEND_TEXT, append to text using \c .
                            - \b EZ_PHPCREATOR_VARIABLE_APPEND_ELEMENT, append to array using \c []
     \param $variableParameters Optional parameters for the statement
            - \a is-reference, whether to do the assignment with reference or not (default is not)
    */
    function variableNameText( $variableName, $assignmentType, $variableParameters = array() )
    {
        $variableParameters = array_merge( array( 'is-reference' => false ),
                                           $variableParameters );
        $isReference = $variableParameters['is-reference'];
        $text = '$' . $variableName;
        if ( $assignmentType == EZ_PHPCREATOR_VARIABLE_ASSIGNMENT )
        {
            if ( $isReference )
                $text .= ' =& ';
            else
                $text .= ' = ';
        }
        else if ( $assignmentType == EZ_PHPCREATOR_VARIABLE_APPEND_TEXT )
        {
            $text .= ' .= ';
        }
        else if ( $assignmentType == EZ_PHPCREATOR_VARIABLE_APPEND_ELEMENT )
        {
            if ( $isReference )
                $text .= '[] =& ';
            else
                $text .= '[] = ';
        }
        return $text;
    }

    /*!
     Creates a text representation of the value \a $value which can
     be placed in files and be read back by a PHP parser as it was.
     The type of the values determines the output, it can be one of the following.
     - boolean, becomes \c true or \c false
     - null, becomes \c null
     - string, adds \ (backslash) to backslashes, double quotes, dollar signs and newlines.
               Then wraps the whole string in " (double quotes).
     - numeric, displays the value as-is.
     - array, expands all value recursively using this function
     - object, creates a representation of an object creation if the object has \c serializeData implemented.

     \param $column Determines the starting column in which the text will be placed.
                    This is used for expanding arrays and objects which can span multiple lines.
     \param $iteration The current iteration, starts at 0 and increases with 1 for each recursive call
     \param $maxIterations The maximum number of iterations to allow, if the iteration
                           exceeds this the array or object will be split into multiple variables.
                           Can be set to \c false to the array or object as-is.

     \note This function can be called statically if \a $maxIterations is set to \c false
    */
    function variableText( $value, $column = 0, $iteration = 0, $maxIterations = 2 )
    {
        if ( isset( $this->Spacing) and !$this->Spacing )
        {
            return var_export( $value, true );
        }

        if ( is_bool( $value ) )
            $text = ( $value ? 'true' : 'false' );
        else if ( is_null( $value ) )
            $text = 'null';
        else if ( is_string( $value ) )
        {
            $valueText = str_replace( array( "\\",
                                             "\"",
                                             "\$",
                                             "\n" ),
                                      array( "\\\\",
                                             "\\\"",
                                             "\\$",
                                             "\\n" ),
                                      $value );
            $text = "\"$valueText\"";
        }
        else if ( is_numeric( $value ) )
            $text = $value;
        else if ( is_object( $value ) )
        {
            if ( $maxIterations !== false and
                 $iteration > $maxIterations )
            {
                $temporaryVariableName = $this->temporaryVariableName( 'obj' );
                $this->writeVariable( $temporaryVariableName, $value );
                $text = '$' . $temporaryVariableName;
            }
            else
            {
                $text = '';
                if ( method_exists( $value, 'serializedata' ) )
                {
                    $serializeData = $value->serializeData();
                    $className = $serializeData['class_name'];
                    $text = "new $className(";

                    $column += strlen( $text );
                    $parameters = $serializeData['parameters'];
                    $variables = $serializeData['variables'];

                    $i = 0;
                    foreach ( $parameters as $parameter )
                    {
                        if ( $i > 0 )
                        {
                            $text .= ",\n" . str_repeat( ' ', $column );
                        }
                        $variableName = $variables[$parameter];
                        $variableValue = $value->$variableName;
                        $keyText = " ";
                        /* It is also used statically sometimes, so we need to
                         * do this ugly hack */
                        if ( $this and ( get_class( $this ) == 'ezphpcreator' ) )
                        {
                            $text .= $keyText . $this->variableText( $variableValue, $column + strlen( $keyText  ), $iteration + 1, $maxIterations );
                        }
                        else
                        {
                            $text .= $keyText . eZPHPCreator::variableText( $variableValue, $column + strlen( $keyText  ), $iteration + 1, $maxIterations );
                        }
                        ++$i;
                    }
                    if ( $i > 0 )
                        $text .= ' ';

                    $text .= ')';
                }
            }
        }
        else if ( is_array( $value ) )
        {
            if ( $maxIterations !== false and
                 $iteration > $maxIterations )
            {
                $temporaryVariableName = $this->temporaryVariableName( 'arr' );
                $this->writeVariable( $temporaryVariableName, $value );
                $text = '$' . $temporaryVariableName;
            }
            else
            {
                $text = 'array(';
                $column += strlen( $text );
                $valueKeys = array_keys( $value );
                $isIndexed = true;
                for ( $i = 0; $i < count( $valueKeys ); ++$i )
                {
                    if ( $i !== $valueKeys[$i] )
                    {
                        $isIndexed = false;
                        break;
                    }
                }
                $i = 0;
                foreach ( $valueKeys as $key )
                {
                    if ( $i > 0 )
                    {
                        $text .= ",\n" . str_repeat( ' ', $column );
                    }
                    $element =& $value[$key];
                    $keyText = ' ';
                    if ( !$isIndexed )
                    {
                        if ( is_int( $key ) )
                            $keyText = $key;
                        else
                            $keyText = "\"" . str_replace( array( "\\",
                                                                  "\"",
                                                                  "\n" ),
                                                           array( "\\\\",
                                                                  "\\\"",
                                                                  "\\n" ),
                                                           $key ) . "\"";
                        $keyText = " $keyText => ";
                    }
                    /* It is also used statically sometimes, so we need to do
                     * this ugly hack */
                    if ( $this and ( get_class( $this ) == 'ezphpcreator' ) )
                    {
                        $text .= $keyText . $this->variableText( $element, $column + strlen( $keyText  ), $iteration + 1, $maxIterations );
                    }
                    else
                    {
                        $text .= $keyText . eZPHPCreator::variableText( $element, $column + strlen( $keyText  ), $iteration + 1, $maxIterations );
                    }
                    ++$i;
                }
                if ( $i > 0 )
                    $text .= ' ';
                $text .= ')';
            }
        }
        else
            $text = 'null';
        return $text;
    }

    /*!
     \static
     Splits \a $text into multiple lines using \a $splitString for splitting.
     For each line it will prepend the string \a $spacingString n times as specified by \a $spacing.

     It will try to be smart and not do anything when \a $spacing is set to \c 0.

     \param $skipEmptyLines If \c true it will not prepend the string for empty lines.
     \param $spacing Must be a positive number, \c 0 means to not prepend anything.
    */
    function prependSpacing( $text, $spacing, $skipEmptyLines = true, $spacingString = " ", $splitString = "\n" )
    {
        if ( $spacing == 0 or !$this->Spacing )
            return $text;
        $textArray = explode( $splitString, $text );
        $newTextArray = array();
        foreach ( $textArray as $text )
        {
            if ( trim( $text ) != '' and $this->Spacing )
                $textLine = str_repeat( $spacingString, $spacing ) . $text;
            else
                $textLine = $text;
            $newTextArray[] = $textLine;
        }
        return implode( $splitString, $newTextArray );
    }

    //@{

    /*!
     Opens the file for writing and sets correct file permissions.
     \return The current file resource or \c false if it failed to open the file.
     \note The file name and path is supplied to the constructor of this class.
     \note Multiple calls to this method will only open the file once.
    */
    function open( $atomic = false )
    {
        if ( !$this->FileResource )
        {
            if ( !file_exists( $this->PHPDir ) )
            {
                include_once( 'lib/ezfile/classes/ezdir.php' );
                $ini =& eZINI::instance();
                $perm = octdec( $ini->variable( 'FileSettings', 'StorageDirPermissions' ) );
                eZDir::mkdir( $this->PHPDir, $perm, true );
            }
            $path = $this->PHPDir . '/' . $this->PHPFile;
            $oldumask = umask( 0 );
            $pathExisted = file_exists( $path );
            if ( $atomic )
            {
                $this->isAtomic = true;
                $this->requestedFilename = $path;
                $uniqid = md5( uniqid( "ezp". getmypid(), true ) );
                $path .= ".$uniqid";
                $this->tmpFilename = $path;
            }
            $ini =& eZINI::instance();
            $perm = octdec( $ini->variable( 'FileSettings', 'StorageFilePermissions' ) );
            $this->FileResource = @fopen( $this->FilePrefix . $path, "w" );
            if ( !$this->FileResource )
                eZDebug::writeError( "Could not open file '$path' for writing, perhaps wrong permissions" );
            if ( $this->FileResource and
                 !$pathExisted )
                chmod( $path, $perm );
            umask( $oldumask );
        }
        return $this->FileResource;
    }

    /*!
     Closes the currently open file if any.
    */
    function close()
    {
        if ( $this->FileResource )
        {
            fclose( $this->FileResource );

            if ( $this->isAtomic )
            {
                include_once( 'lib/ezfile/classes/ezfile.php' );
                eZFile::rename( $this->tmpFilename, $this->requestedFilename );
            }
            $this->FileResource = false;
        }
    }

    /*!
     \return \c true if the file and path already exists.
     \note The file name and path is supplied to the constructor of this class.
    */
    function exists()
    {
        $path = $this->PHPDir . '/' . $this->PHPFile;
        return file_exists( $path );
    }

    /*!
     \return \c true if file exists and can be restored.
     \param $timestamp The timestamp to check the modification time of the file against,
                       if the modification time is larger or equal to \a $timestamp
                       the file can be restored. Otherwise the file is considered too old.
     \note The file name and path is supplied to the constructor of this class.
    */
    function canRestore( $timestamp = false )
    {
        $path = $this->PHPDir . '/' . $this->PHPFile;
        $canRestore = file_exists( $path );
        if ( $timestamp !== false and
             $canRestore )
        {
            $cacheModifierTime = filemtime( $path );
            $canRestore = ( $cacheModifierTime >= $timestamp );
        }
        return $canRestore;
    }

    /*!
     Tries to restore the PHP file and fetch the defined variables in \a $variableDefinitions.
     This basically means including the file using include().

     \param $variableDefinitions Associative array with the return variable name being the key
                                 matched variable as value.

     \return An associatve array with the variables that were found according to \a $variableDefinitions.

     Example:
     \code
$values = $php->restore( array( 'MyValue' => 'node' ) );
print( $values['MyValue'] );
     \endcode

     \note The file name and path is supplied to the constructor of this class.
    */
    function restore( $variableDefinitions )
    {
        $returnVariables = array();
        $path = $this->PHPDir . '/' . $this->PHPFile;
        include( $path );
        foreach ( $variableDefinitions as $variableReturnName => $variableName )
        {
            $variableRequired = true;
            $variableDefault = false;
            if ( is_array( $variableName ) )
            {
                $variableDefinition = $variableName;
                $variableName = $variableDefinition['name'];
                $variableRequired = $variableDefinition['required'];
                if ( isset( $variableDefinition['default'] ) )
                    $variableDefault = $variableDefinition['default'];
            }
            if ( isset( ${$variableName} ) )
            {
                $returnVariables[$variableReturnName] = ${$variableName};
            }
            else if ( $variableRequired )
                eZDebug::writeError( "Variable '$variableName' is not present in cache '$path'",
                                     'eZPHPCreator::restore' );
            else
                $returnVariables[$variableReturnName] = $variableDefault;
        }
        return $returnVariables;
    }

    /*!
     Stores the PHP cache, returns false if the cache file could not be created.
    */
    function store( $atomic = false )
    {
        if ( $this->open( $atomic ) )
        {
            $this->write( "<?php\n" );

            $this->writeElements();

            $this->write( "?>\n" );

            $this->writeChunks();
            $this->flushChunks();
            $this->close();

            // Write log message to storage.log
            include_once( 'lib/ezutils/classes/ezlog.php' );
            eZLog::writeStorageLog( $this->PHPFile, $this->PHPDir . '/' );
            return true;
        }
        else
        {
            eZDebug::writeError( "Failed to open file '" . $this->PHPDir . '/' . $this->PHPFile . "'",
                                 'eZPHPCreator::store' );
            return false;
        }
    }

    /*!
     Creates a text string out of all elements and returns it.
     \note Calling this multiple times will resulting text processing each time.
    */
    function fetch( $addPHPMarkers = true )
    {
        if ( $addPHPMarkers )
            $this->write( "<?php\n" );
        $this->writeElements();
        if ( $addPHPMarkers )
            $this->write( "?>\n" );

        $text = implode( '', $this->TextChunks );

        $this->flushChunks();

        return $text;
    }

    //@}

    /*!
     \private
    */
    function writeChunks()
    {
        $count = count( $this->TextChunks );
        for ( $i = 0; $i < $count; ++$i )
        {
            $text = $this->TextChunks[$i];
            fwrite( $this->FileResource, $text );
        }
    }

    /*!
     \private
    */
    function flushChunks()
    {
        $this->TextChunks = array();
    }

    /*!
     \private
    */
    function write( $text )
    {
//         fwrite( $this->FileResource, $text );
        $this->TextChunks[] = $text;
    }

    /*!
     \private
    */
    function writeElements()
    {
        $count = count( $this->Elements );
        for ( $i = 0; $i < $count; ++$i )
        {
            $element =& $this->Elements[$i];
            if ( $element[0] == EZ_PHPCREATOR_DEFINE )
            {
                $this->writeDefine( $element );
            }
            else if ( $element[0] == EZ_PHPCREATOR_RAW_VARIABLE )
            {
                $this->writeRawVariable( $element[1], $element[2] );
            }
            else if ( $element[0] == EZ_PHPCREATOR_VARIABLE )
            {
                $this->writeVariable( $element[1], $element[2], $element[3], $element[4] );
            }
            else if ( $element[0] == EZ_PHPCREATOR_VARIABLE_UNSET )
            {
                $this->writeVariableUnset( $element );
            }
            else if ( $element[0] == EZ_PHPCREATOR_VARIABLE_UNSET_LIST )
            {
                $this->writeVariableUnsetList( $element );
            }
            else if ( $element[0] == EZ_PHPCREATOR_SPACE )
            {
                $this->writeSpace( $element );
            }
            else if ( $element[0] == EZ_PHPCREATOR_TEXT )
            {
                $this->writeText( $element );
            }
            else if ( $element[0] == EZ_PHPCREATOR_METHOD_CALL )
            {
                $this->writeMethodCall( $element );
            }
            else if ( $element[0] == EZ_PHPCREATOR_CODE_PIECE )
            {
                $this->writeCodePiece( $element );
            }
            else if ( $element[0] == EZ_PHPCREATOR_EOL_COMMENT )
            {
                $this->writeComment( $element );
            }
            else if ( $element[0] == EZ_PHPCREATOR_INCLUDE )
            {
                $this->writeInclude( $element );
            }
        }
    }

    /*!
     \private
    */
    function writeDefine( $element )
    {
        $name = $element[1];
        $value = $element[2];
        $caseSensitive = $element[3];
        $parameters = $element[4];
        $text = '';
        if ( $this->Spacing )
        {
            $spacing = 0;
            if ( isset( $parameters['spacing'] ) )
                $spacing = $parameters['spacing'];
            $text = str_repeat( ' ', $spacing );
        }
        $nameText = $this->variableText( $name, 0 );
        $valueText = $this->variableText( $value, 0 );
        $text .= "define( $nameText, $valueText";
        if ( !$caseSensitive )
            $text .= ", true";
        $text .= " );\n";
        $this->write( $text );
    }

    /*!
     \private
    */
    function writeInclude( $element )
    {
        $includeFile = $element[1];
        $includeType = $element[2];
        $parameters = $element[3];
        if ( $includeType == EZ_PHPCREATOR_INCLUDE_ONCE )
            $includeName = 'include_once';
        else if ( $includeType == EZ_PHPCREATOR_INCLUDE_ALWAYS )
            $includeName = 'include';
        $includeFileText = $this->variableText( $includeFile, 0 );
        $text = "$includeName( $includeFileText );\n";
        if ( $this->Spacing )
        {
            $spacing = 0;
            if ( isset( $parameters['spacing'] ) )
                $spacing = $parameters['spacing'];
            $text = str_repeat( ' ', $spacing ) . $text;
        }
        $this->write( $text );
    }

    /*!
     \private
    */
    function writeComment( $element )
    {
        $elementAttributes = $element[2];
        $spacing = 0;
        if ( isset( $elementAttributes['spacing'] ) and $this->Spacing )
            $spacing = $elementAttributes['spacing'];
        $whitespaceHandling = $elementAttributes['whitespace-handling'];
        $eol = $elementAttributes['eol'];
        $newCommentArray = array();
        $commentArray = explode( "\n", $element[1] );
        foreach ( $commentArray as $comment )
        {
            $textLine = '// ' . $comment;
            if ( $whitespaceHandling )
            {
                $textLine = rtrim( $textLine );
                $textLine = str_replace( "\t", '    ', $textLine );
            }
            $textLine = str_repeat( ' ', $spacing ) . $textLine;
            $newCommentArray[] = $textLine;
        }
        $text = implode( "\n", $newCommentArray );
        if ( $eol )
            $text .= "\n";
        $this->write( $text );
    }

    /*!
     \private
    */
    function writeSpace( $element )
    {
        $text = str_repeat( "\n", $element[1] );
        $this->write( $text );
    }

    /*!
     \private
    */
    function writeCodePiece( $element )
    {
        $code = $element[1];
        $parameters = $element[2];
        $spacing = 0;
        if ( isset( $parameters['spacing'] ) and $this->Spacing )
            $spacing = $parameters['spacing'];
        $text = eZPHPCreator::prependSpacing( $code, $spacing );
        $this->write( $text );
    }

    /*!
     \private
    */
    function writeText( $element )
    {
        $text = $element[1];
        $this->write( "\n?>" );
        $this->write( $text );
        $this->write( "<?php\n" );
    }

    /*!
     \private
    */
    function writeMethodCall( $element )
    {
        $objectName = $element[1];
        $methodName = $element[2];
        $parameters = $element[3];
        $returnValue = $element[4];
        $parameters = $element[5];
        $text = '';
        $spacing = 0;
        if ( isset( $parameters['spacing'] ) and $this->Spacing )
            $spacing = $parameters['spacing'];
        if ( is_array( $returnValue ) )
        {
            $variableName = $returnValue[0];
            $assignmentType = EZ_PHPCREATOR_VARIABLE_ASSIGNMENT;
            if ( isset( $variableValue[1] ) )
                $assignmentType = $variableValue[1];
            $text = $this->variableNameText( $variableName, $assignmentType );
        }
        $text .= '$' . $objectName . '->' . $methodName . '(';
        $column = strlen( $text );
        $i = 0;
        foreach ( $parameters as $parameterData )
        {
            if ( $i > 0 )
                $text .= ",\n" . str_repeat( ' ', $column );
            $parameterType = EZ_PHPCREATOR_METHOD_CALL_PARAMETER_VALUE;
            $parameterValue = $parameterData[0];
            if ( isset( $parameterData[1] ) )
                $parameterType = $parameterData[1];
            if ( $parameterType == EZ_PHPCREATOR_METHOD_CALL_PARAMETER_VALUE )
                 $text .= ' ' . $this->variableText( $parameterValue, $column + 1 );
            else if ( $parameterType == EZ_PHPCREATOR_METHOD_CALL_PARAMETER_VARIABLE )
                $text .= ' $' . $parameterValue;
            ++$i;
        }
        if ( $i > 0 )
            $text .= ' ';
        $text .= ");\n";
        $text = eZPHPCreator::prependSpacing( $text, $spacing );
        $this->write( $text );
    }

    /*!
     \private
    */
    function writeVariableUnset( $element )
    {
        $variableName = $element[1];
        $parameters = $element[2];
        $spacing = 0;
        if ( isset( $parameters['spacing'] ) and $this->Spacing )
            $spacing = $parameters['spacing'];
        $text = "unset( \$$variableName );\n";
        $text = eZPHPCreator::prependSpacing( $text, $spacing );
        $this->write( $text );
    }

    /*!
     \private
    */
    function writeVariableUnsetList( $element )
    {
        $variableNames = $element[1];

        if ( count( $variableNames ) )
        {
            $parameters = $element[2];
            $spacing = 0;
            if ( isset( $parameters['spacing'] ) and $this->Spacing )
                $spacing = $parameters['spacing'];
            $text = 'unset( ';
            array_walk( $variableNames, create_function( '&$variableName,$key', '$variableName = "\$" . $variableName;') );
            $text .= join( ', ', $variableNames );
            $text .= " );\n";
            $text = eZPHPCreator::prependSpacing( $text, $spacing );
            $this->write( $text );
        }
    }

    /*!
     \private
    */
    function writeRawVariable( $variableName, $variableValue )
    {
        $this->write( "\${$variableName} = ". var_export( $variableValue, true). ";\n" );
    }

    /*!
     \private
    */
    function writeVariable( $variableName, $variableValue, $assignmentType = EZ_PHPCREATOR_VARIABLE_ASSIGNMENT,
                            $variableParameters = array() )
    {
        $variableParameters = array_merge( array( 'full-tree' => false,
                                                  'spacing' => 0 ),
                                           $variableParameters );
        $fullTree = $variableParameters['full-tree'];
        $spacing = $this->Spacing ? $variableParameters['spacing'] : 0;
        $text = $this->variableNameText( $variableName, $assignmentType, $variableParameters );
        $maxIterations = 2;
        if ( $fullTree )
            $maxIterations = false;
        $text .= $this->variableText( $variableValue, strlen( $text ), 0, $maxIterations );
        $text .= ";\n";
        $text = eZPHPCreator::prependSpacing( $text, $spacing );
        $this->write( $text );
    }

    /*!
     \private
    */
    function temporaryVariableName( $prefix )
    {
        $temporaryCounter =& $this->TemporaryCounter;
        $variableName = $prefix . '_' . $temporaryCounter;
        ++$temporaryCounter;
        return $variableName;
    }

    /// \privatesection
    var $PHPDir;
    var $PHPFile;
    var $FileResource;
    var $Elements;
    var $TextChunks;
    var $isAtomic;
    var $tmpFilename;
    var $requestedFilename;
    var $Spacing = true;
}
?>
