/**********************************************************************
**   Copyright (C) 2000 Trolltech AS.  All rights reserved.
**
**   fetchtr.cpp
**
**   This file is part of Qt Linguist.
**
**   See the file LICENSE included in the distribution for the usage
**   and distribution terms.
**
**   The file is provided AS IS with NO WARRANTY OF ANY KIND,
**   INCLUDING THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR
**   A PARTICULAR PURPOSE.
**
**********************************************************************/

#include <qfile.h>
#include <qfileinfo.h>
#include <qregexp.h>
#include <qstring.h>
#include <qtextstream.h>

#include <ctype.h>
#include <errno.h>
#include <metatranslator.h>
#include <stdio.h>
#include <string.h>
#include <qxml.h>

static const char MagicComment[] = "TRANSLATOR ";

/*
  The first part of this source file is the PHP tokenizer.  We skip
  most of PHP; the only tokens that interest us are defined here.
  Thus, the code fragment

  function main()
  {
      printf( "Hello, world!\n" );
      return 0;
  }

  is broken down into the following tokens:

      Ident Ident LeftParen RightParen
      LeftBrace
	  Ident LeftParen String RightParen Semicolon
	  return Semicolon
      RightBrace.

  Notice that the 0 doesn't produce any token.
*/

enum { Tok_Eof, Tok_class, Tok_namespace, Tok_return, Tok_tr,
       Tok_trUtf8, Tok_translate, Tok_Ident, Tok_i18n,
       Tok_Comment, Tok_String, Tok_SString, Tok_Colon, Tok_Gulbrandsen,
       Tok_LeftBrace, Tok_RightBrace, Tok_LeftParen, Tok_RightParen,
       Tok_Comma, Tok_Semicolon };

/*
  The tokenizer maintains the following global variables. The names
  should be self-explanatory.
*/
static QCString yyFileName;
static int yyCh;
static char yyIdent[128];
static size_t yyIdentLen;
static char yyComment[65536];
static size_t yyCommentLen;
static char yyString[16384];
static size_t yyStringLen;
static int yyBraceDepth;
static int yyParenDepth;
static int yyLineNo;
static int yyCurLineNo;

// the file to read from (if reading from a file)
static FILE *yyInFile;

// the string to read from and current position in the string (otherwise)
static QString yyInStr;
static int yyInPos;

static int (*getChar)();

static int getCharFromFile()
{
    int c = getc( yyInFile );
    if ( c == '\n' )
        yyCurLineNo++;
    return c;
}

static int getCharFromString()
{
    if ( yyInPos == (int) yyInStr.length() ) {
        return EOF;
    } else {
        return yyInStr[yyInPos++].latin1();
    }
}

static void startTokenizer( const char *fileName, int (*getCharFunc)() )
{
    yyInPos = 0;
    getChar = getCharFunc;

    yyFileName = fileName;
    yyCh = getChar();
    yyBraceDepth = 0;
    yyParenDepth = 0;
    yyCurLineNo = 1;
}

static int getToken()
{
    const char tab[] = "abfnrtv";
    const char backTab[] = "\a\b\f\n\r\t\v";
    uint n;

    yyIdentLen = 0;
    yyCommentLen = 0;
    yyStringLen = 0;

    while ( yyCh != EOF ) {
        yyLineNo = yyCurLineNo;

        if ( isalpha(yyCh) || yyCh == '_' ) {
            do {
                if ( yyIdentLen < sizeof(yyIdent) - 1 )
                    yyIdent[yyIdentLen++] = (char) yyCh;
                yyCh = getChar();
            } while ( isalnum(yyCh) || yyCh == '_' );
            yyIdent[yyIdentLen] = '\0';

            switch ( yyIdent[0] ) {
                case 'e':
                    if ( strcmp(yyIdent + 1, "zi18n") == 0 )
                        return Tok_i18n;
                    break;
            }
            return Tok_Ident;
        } else {
            switch ( yyCh ) {
                case '/':
                    yyCh = getChar();
                    if ( yyCh == '/' ) {
                        do {
                            yyCh = getChar();
                        } while ( yyCh != EOF && yyCh != '\n' );
                    } else if ( yyCh == '*' ) {
                        bool metAster = FALSE;
                        bool metAsterSlash = FALSE;

                        while ( !metAsterSlash ) {
                            yyCh = getChar();
                            if ( yyCh == EOF ) {
                                qWarning( "%s: Unterminated PHP comment starting at"
                                          " line %d", (const char *) yyFileName,
                                          yyLineNo );
                                yyComment[yyCommentLen] = '\0';
                                return Tok_Comment;
                            }
                            if ( yyCommentLen < sizeof(yyComment) - 1 )
                                yyComment[yyCommentLen++] = (char) yyCh;

                            if ( yyCh == '*' )
                                metAster = TRUE;
                            else if ( metAster && yyCh == '/' )
                                metAsterSlash = TRUE;
                            else
                                metAster = FALSE;
                        }
                        yyCh = getChar();
                        yyCommentLen -= 2;
                        yyComment[yyCommentLen] = '\0';
                        return Tok_Comment;
                    }
                    break;
                case '"':
                    yyCh = getChar();

                    while ( yyCh != EOF && yyCh != '\n' && yyCh != '"' ) {
                        if ( yyCh == '\\' ) {
                            yyCh = getChar();

                            if ( yyCh == 'x' ) {
                                QCString hex = "0";

                                yyCh = getChar();
                                while ( isxdigit(yyCh) ) {
                                    hex += (char) yyCh;
                                    yyCh = getChar();
                                }
                                sscanf( hex, "%x", &n );
                                if ( yyStringLen < sizeof(yyString) - 1 )
                                    yyString[yyStringLen++] = (char) n;
                            } else if ( yyCh >= '0' && yyCh < '8' ) {
                                QCString oct = "";

                                do {
                                    oct += (char) yyCh;
                                    yyCh = getChar();
                                } while ( yyCh >= '0' && yyCh < '8' );
                                sscanf( oct, "%o", &n );
                                if ( yyStringLen < sizeof(yyString) - 1 )
                                    yyString[yyStringLen++] = (char) n;
                            } else {
                                const char *p = strchr( tab, yyCh );
                                if ( yyStringLen < sizeof(yyString) - 1 )
                                    yyString[yyStringLen++] = ( p == 0 ) ?
                                        (char) yyCh : backTab[p - tab];
                                yyCh = getChar();
                            }
                        } else {
                            if ( yyStringLen < sizeof(yyString) - 1 )
                                yyString[yyStringLen++] = (char) yyCh;
                            yyCh = getChar();
                        }
                    }
                    yyString[yyStringLen] = '\0';

//                     if ( yyCh != '"' )
//                         qWarning( "%s:%d: Unterminated PHP string",
//                                   (const char *) yyFileName, yyLineNo );

                    if ( yyCh == EOF ) {
                        return Tok_Eof;
                    } else {
                        yyCh = getChar();
                        return Tok_String;
                    }
                    break;
                case '\'':
                    yyCh = getChar();

                    while ( yyCh != EOF && yyCh != '\n' && yyCh != '\'' ) {
                        if ( yyCh == '\\' ) {
                            yyCh = getChar();

                            if ( yyCh == 'x' ) {
                                QCString hex = "0";

                                yyCh = getChar();
                                while ( isxdigit(yyCh) ) {
                                    hex += (char) yyCh;
                                    yyCh = getChar();
                                }
                                sscanf( hex, "%x", &n );
                                if ( yyStringLen < sizeof(yyString) - 1 )
                                    yyString[yyStringLen++] = (char) n;
                            } else if ( yyCh >= '0' && yyCh < '8' ) {
                                QCString oct = "";

                                do {
                                    oct += (char) yyCh;
                                    yyCh = getChar();
                                } while ( yyCh >= '0' && yyCh < '8' );
                                sscanf( oct, "%o", &n );
                                if ( yyStringLen < sizeof(yyString) - 1 )
                                    yyString[yyStringLen++] = (char) n;
                            } else {
                                const char *p = strchr( tab, yyCh );
                                if ( yyStringLen < sizeof(yyString) - 1 )
                                    yyString[yyStringLen++] = ( p == 0 ) ?
                                        (char) yyCh : backTab[p - tab];
                                yyCh = getChar();
                            }
                        } else {
                            if ( yyStringLen < sizeof(yyString) - 1 )
                                yyString[yyStringLen++] = (char) yyCh;
                            yyCh = getChar();
                        }
                    }
                    yyString[yyStringLen] = '\0';

//                     if ( yyCh != '\'' )
//                         qWarning( "%s:%d: Unterminated PHP string",
//                                   (const char *) yyFileName, yyLineNo );

                    if ( yyCh == EOF ) {
                        return Tok_Eof;
                    } else {
                        yyCh = getChar();
                        return Tok_SString;
                    }
                    break;
                case ':':
                    yyCh = getChar();
                    if ( yyCh == ':' ) {
                        yyCh = getChar();
                        return Tok_Gulbrandsen;
                    }
                    return Tok_Colon;
                case '{':
                    yyBraceDepth++;
                    yyCh = getChar();
                    return Tok_LeftBrace;
                case '}':
                    yyBraceDepth--;
                    yyCh = getChar();
                    return Tok_RightBrace;
                case '(':
                    yyParenDepth++;
                    yyCh = getChar();
                    return Tok_LeftParen;
                case ')':
                    yyParenDepth--;
                    yyCh = getChar();
                    return Tok_RightParen;
                case ',':
                    yyCh = getChar();
                    return Tok_Comma;
                case ';':
                    yyCh = getChar();
                    return Tok_Semicolon;
                default:
                    yyCh = getChar();
            }
        }
    }
    return Tok_Eof;
}

/*
  The second part of this source file is the parser. It accomplishes
  a very easy task: It finds all strings inside a tr() or translate()
  call, and possibly finds out the context of the call. It supports
  three cases: (1) the context is specified, as in
  FunnyDialog::tr("Hello") or translate("FunnyDialog", "Hello");
  (2) the call appears within an inlined function; (3) the call
  appears within a function defined outside the class definition.
*/

static int yyTok;

static bool match( int t )
{
    bool matches = ( yyTok == t );
    if ( matches )
        yyTok = getToken();
    return matches;
}

static bool matchString( QCString *s )
{
    bool matches = ( yyTok == Tok_String );
    *s = "";
    while ( yyTok == Tok_String ) {
        *s += yyString;
        yyTok = getToken();
    }
    return matches;
}

static bool matchSString( QCString *s )
{
    bool matches = ( yyTok == Tok_SString );
    *s = "";
    while ( yyTok == Tok_SString ) {
        *s += yyString;
        yyTok = getToken();
    }
    return matches;
}

static bool matchEncoding( bool *utf8 )
{
    if ( yyTok == Tok_Ident ) {
        if ( strcmp(yyIdent, "QApplication") == 0 ) {
            yyTok = getToken();
            if ( yyTok == Tok_Gulbrandsen )
                yyTok = getToken();
        }
        *utf8 = QString( yyIdent ).endsWith( QString("UTF8") );
        yyTok = getToken();
        return TRUE;
    } else {
        return FALSE;
    }
}

static void parse( MetaTranslator *tor, const char *initialContext,
                   const char *defaultContext )
{
    QMap<QCString, QCString> qualifiedContexts;
    QStringList namespaces;
    QCString context;
    QCString text;
    QCString comment;
    QCString functionContext = initialContext;
    QCString prefix;
    bool utf8 = FALSE;

    yyTok = getToken();
    while ( yyTok != Tok_Eof ) {
        switch ( yyTok ) {
            case Tok_i18n:
                utf8 = FALSE;
                yyTok = getToken();
                if ( match( Tok_LeftParen ) &&
                     matchString( &context ) || matchSString( &context ) &&
                     match( Tok_Comma ) &&
                     matchString( &text ) || matchSString( &text ) )
                {
                    if ( ( match( Tok_Comma ) &&
                           matchString( &comment ) || matchSString( &comment ) &&
                           match( Tok_RightParen ) ) == false )
                    {
                        comment = "";
                    }
                    tor->insert( MetaTranslatorMessage( context, text, comment, QString::null, utf8 ) );
                }
                else
                    qDebug( " --- token failed ------------" );
                break;
            case Tok_Ident:
                if ( !prefix.isNull() )
                    prefix += "::";
                prefix += yyIdent;
                yyTok = getToken();
                if ( yyTok != Tok_Gulbrandsen )
                    prefix = (const char *) 0;
                break;
            case Tok_Comment:
                comment = yyComment;
                comment = comment.simplifyWhiteSpace();
                if ( comment.left(sizeof(MagicComment) - 1) == MagicComment ) {
                    comment.remove( 0, sizeof(MagicComment) - 1 );
                    int k = comment.find( ' ' );
                    if ( k == -1 ) {
                        context = comment;
                    } else {
                        context = comment.left( k );
                        comment.remove( 0, k + 1 );
                        tor->insert( MetaTranslatorMessage(context, "", comment,
                                                           QString::null, FALSE) );
                    }

                    /*
                      Provide a backdoor for people using "using
                      namespace". See the manual for details.
                    */
                    k = 0;
                    while ( (k = context.find("::", k)) != -1 ) {
                        qualifiedContexts.insert( context.mid(k + 2), context );
                        k++;
                    }
                }
                yyTok = getToken();
                break;
            case Tok_Gulbrandsen:
                // at top level?
                if ( yyBraceDepth == (int) namespaces.count() && yyParenDepth == 0 )
                    functionContext = prefix;
                yyTok = getToken();
                break;
            case Tok_RightBrace:
            case Tok_Semicolon:
                if ( yyBraceDepth >= 0 &&
                     yyBraceDepth + 1 == (int) namespaces.count() )
                    namespaces.remove( namespaces.fromLast() );
                if ( yyBraceDepth == (int) namespaces.count() ) {
                    functionContext = defaultContext;
                }
                yyTok = getToken();
                break;
            default:
                yyTok = getToken();
        }
    }

//     if ( yyBraceDepth != 0 )
//         qWarning( "%s: Unbalanced braces in PHP code", (const char *) yyFileName );
//     if ( yyParenDepth != 0 )
//         qWarning( "%s: Unbalanced parentheses in PHP code", (const char *) yyFileName );
}

void fetchtr_php( QFileInfo *fi, MetaTranslator *tor, bool mustExist )
{
    char *defaultContext = "";
    yyInFile = fopen( fi->filePath().latin1(), "r" );
    if ( yyInFile == 0 )
    {
        if ( mustExist )
            qWarning( "lupdate error: cannot open PHP source file '%s': %s",
                      fi->filePath().latin1(), strerror(errno) );
        return;
    }

    startTokenizer( fi->fileName().latin1(), getCharFromFile );
    parse( tor, 0, defaultContext );
    fclose( yyInFile );
}
