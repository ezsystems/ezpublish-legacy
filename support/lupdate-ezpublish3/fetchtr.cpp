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
#include <qregexp.h>
#include <qstring.h>
#include <qtextstream.h>

#include <ctype.h>
#include <errno.h>
#include <metatranslator.h>
#include <stdio.h>
#include <string.h>
#include <qxml.h>

/* qmake ignore Q_OBJECT */

static const char MagicComment[] = "TRANSLATOR ";

static QMap<QCString, int> needs_Q_OBJECT;
static QMap<QCString, int> lacks_Q_OBJECT;

/*
  The first part of this source file is the C++ tokenizer.  We skip
  most of C++; the only tokens that interest us are defined here.
  Thus, the code fragment

      int main()
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
       Tok_trUtf8, Tok_translate, Tok_Q_OBJECT, Tok_Ident,
       Tok_Comment, Tok_String, Tok_Colon, Tok_Gulbrandsen,
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
	    case 'Q':
		if ( strcmp(yyIdent + 1, "_OBJECT") == 0 ) {
		    return Tok_Q_OBJECT;
		} else if ( strcmp(yyIdent + 1, "T_TR_NOOP") == 0 ) {
		    return Tok_tr;
		} else if ( strcmp(yyIdent + 1, "T_TRANSLATE_NOOP") == 0 ) {
		    return Tok_translate;
		}
		break;
	    case 'T':
		// TR() for when all else fails
		if ( qstricmp(yyIdent + 1, "R") == 0 )
		    return Tok_tr;
		break;
	    case 'c':
		if ( strcmp(yyIdent + 1, "lass") == 0 )
		    return Tok_class;
		break;
	    case 'n':
		if ( strcmp(yyIdent + 1, "amespace") == 0 )
		    return Tok_namespace;
		break;
	    case 'r':
		if ( strcmp(yyIdent + 1, "eturn") == 0 )
		    return Tok_return;
		break;
	    case 's':
		if ( strcmp(yyIdent + 1, "truct") == 0 )
		    return Tok_class;
		break;
	    case 't':
		if ( strcmp(yyIdent + 1, "r") == 0 )
		    return Tok_tr;
		else if ( qstrcmp(yyIdent + 1, "rUtf8") == 0 )
		    return Tok_trUtf8;
		else if ( qstrcmp(yyIdent + 1, "ranslate") == 0 )
		    return Tok_translate;
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
			    qWarning( "%s: Unterminated C++ comment starting at"
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

		if ( yyCh != '"' )
		    qWarning( "%s:%d: Unterminated C++ string",
			      (const char *) yyFileName, yyLineNo );

		if ( yyCh == EOF ) {
		    return Tok_Eof;
		} else {
		    yyCh = getChar();
		    return Tok_String;
		}
		break;
	    case ':':
		yyCh = getChar();
		if ( yyCh == ':' ) {
		    yyCh = getChar();
		    return Tok_Gulbrandsen;
		}
		return Tok_Colon;
	    case '\'':
		yyCh = getChar();
		if ( yyCh == '\\' )
		    yyCh = getChar();

		do {
		    yyCh = getChar();
		} while ( yyCh != EOF && yyCh != '\'' );
		yyCh = getChar();
		break;
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
    QCString com;
    QCString functionContext = initialContext;
    QCString prefix;
    bool utf8 = FALSE;
    bool missing_Q_OBJECT = FALSE;

    yyTok = getToken();
    while ( yyTok != Tok_Eof ) {
	switch ( yyTok ) {
	case Tok_class:
	    /*
	      Partial support for inlined functions.
	    */
	    yyTok = getToken();
	    if ( yyBraceDepth == (int) namespaces.count() &&
		 yyParenDepth == 0 ) {
		do {
		    /*
		      This code should execute only once, but we play
		      safe with impure definitions such as
		      'class Q_EXPORT QMessageBox', in which case
		      'QMessageBox' is the class name, not 'Q_EXPORT'.
		    */
		    functionContext = yyIdent;
		    yyTok = getToken();
		} while ( yyTok == Tok_Ident );

		while ( yyTok == Tok_Gulbrandsen ) {
		    yyTok = getToken();
		    functionContext += "::";
		    functionContext += yyIdent;
		    yyTok = getToken();
		}

		if ( yyTok == Tok_Colon ) {
		    missing_Q_OBJECT = TRUE;
		} else {
		    functionContext = defaultContext;
		}
	    }
	    break;
	case Tok_namespace:
	    yyTok = getToken();
	    if ( yyTok == Tok_Ident ) {
		QCString ns = yyIdent;
		yyTok = getToken();
		if ( yyTok == Tok_LeftBrace &&
		     yyBraceDepth == (int) namespaces.count() + 1 )
		    namespaces.append( QString(ns) );
	    }
	    break;
	case Tok_tr:
	case Tok_trUtf8:
	    utf8 = ( yyTok == Tok_trUtf8 );
	    yyTok = getToken();
	    if ( match(Tok_LeftParen) && matchString(&text) ) {
		com = "";
		if ( match(Tok_RightParen) || (match(Tok_Comma) &&
			matchString(&com) && match(Tok_RightParen)) ) {
		    if ( prefix.isNull() ) {
			context = functionContext;
			if ( !namespaces.isEmpty() )
			    context.prepend( (namespaces.join(QString("::")) +
					      QString("::")).latin1() );
		    } else {
			context = prefix;
		    }
		    prefix = (const char *) 0;

		    if ( qualifiedContexts.contains(context) )
			context = qualifiedContexts[context];
		    tor->insert( MetaTranslatorMessage(context, text, com,
						       QString::null, utf8) );

		    if ( lacks_Q_OBJECT.contains(context) ) {
			qWarning( "%s:%d: Class '%s' lacks Q_OBJECT macro",
				  (const char *) yyFileName, yyLineNo,
				  (const char *) context );
			lacks_Q_OBJECT.remove( context );
		    } else {
			needs_Q_OBJECT.insert( context, 0 );
		    }
		}
	    }
	    break;
	case Tok_translate:
	    utf8 = FALSE;
	    yyTok = getToken();
	    if ( match(Tok_LeftParen) &&
		 matchString(&context) &&
		 match(Tok_Comma) &&
		 matchString(&text) ) {
		com = "";
		if ( match(Tok_RightParen) ||
		     (match(Tok_Comma) &&
		      matchString(&com) &&
		      (match(Tok_RightParen) ||
		       match(Tok_Comma) &&
		       matchEncoding(&utf8) &&
		       match(Tok_RightParen))) )
		    tor->insert( MetaTranslatorMessage(context, text, com,
						       QString::null, utf8) );
	    }
	    break;
	case Tok_Q_OBJECT:
	    missing_Q_OBJECT = FALSE;
	    yyTok = getToken();
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
	    com = yyComment;
	    com = com.simplifyWhiteSpace();
	    if ( com.left(sizeof(MagicComment) - 1) == MagicComment ) {
		com.remove( 0, sizeof(MagicComment) - 1 );
		int k = com.find( ' ' );
		if ( k == -1 ) {
		    context = com;
		} else {
		    context = com.left( k );
		    com.remove( 0, k + 1 );
		    tor->insert( MetaTranslatorMessage(context, "", com,
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
		if ( missing_Q_OBJECT ) {
		    if ( needs_Q_OBJECT.contains(functionContext) ) {
			qWarning( "%s:%d: Class '%s' lacks Q_OBJECT macro",
				  (const char *) yyFileName, yyLineNo,
				  (const char *) functionContext );
		    } else {
			lacks_Q_OBJECT.insert( functionContext, 0 );
		    }
		}
		functionContext = defaultContext;
		missing_Q_OBJECT = FALSE;
	    }
	    yyTok = getToken();
	    break;
	default:
	    yyTok = getToken();
	}
    }

    if ( yyBraceDepth != 0 )
	qWarning( "%s: Unbalanced braces in C++ code (or abuse of the C++"
		  " preprocessor)", (const char *) yyFileName );
    if ( yyParenDepth != 0 )
	qWarning( "%s: Unbalanced parentheses in C++ code (or abuse of the C++"
		  " preprocessor)", (const char *) yyFileName );
}

void fetchtr_cpp( const char *fileName, MetaTranslator *tor,
		  const char *defaultContext, bool mustExist )
{
    yyInFile = fopen( fileName, "r" );
    if ( yyInFile == 0 ) {
	if ( mustExist )
	    qWarning( "lupdate error: cannot open C++ source file '%s': %s",
		      fileName, strerror(errno) );
	return;
    }

    startTokenizer( fileName, getCharFromFile );
    parse( tor, 0, defaultContext );
    fclose( yyInFile );
}

/*
  In addition to C++, we support Qt Designer UI files.
*/

/*
  Fetches tr() calls in C++ code in UI files (inside "<function>"
  tag). This mechanism is obsolete.
*/
void fetchtr_inlined_cpp( const char *fileName, const QString& in,
			  MetaTranslator *tor, const char *context )
{
    yyInStr = in;
    startTokenizer( fileName, getCharFromString );
    parse( tor, context, 0 );
    yyInStr = QString::null;
}

class UiHandler : public QXmlDefaultHandler
{
public:
    UiHandler( MetaTranslator *translator, const char *fileName )
	: tor( translator ), fname( fileName ), comment( "" ) { }

    virtual bool startElement( const QString& namespaceURI,
			       const QString& localName, const QString& qName,
			       const QXmlAttributes& atts );
    virtual bool endElement( const QString& namespaceURI,
			     const QString& localName, const QString& qName );
    virtual bool characters( const QString& ch );
    virtual bool fatalError( const QXmlParseException& exception );

private:
    void flush();

    MetaTranslator *tor;
    QCString fname;
    QString context;
    QString source;
    QString comment;

    QString accum;
};

bool UiHandler::startElement( const QString& /* namespaceURI */,
			      const QString& /* localName */,
			      const QString& qName,
			      const QXmlAttributes& atts )
{
    if ( qName == QString("item") ) {
	flush();
	if ( !atts.value(QString("text")).isEmpty() )
	    source = atts.value( QString("text") );
    } else if ( qName == QString("string") ) {
	flush();
    }
    accum.truncate( 0 );
    return TRUE;
}

bool UiHandler::endElement( const QString& /* namespaceURI */,
			    const QString& /* localName */,
			    const QString& qName )
{
    accum.replace( QRegExp(QString("\r\n")), "\n" );

    if ( qName == QString("class") ) {
	if ( context.isEmpty() )
	    context = accum;
    } else if ( qName == QString("string") ) {
	source = accum;
    } else if ( qName == QString("comment") ) {
	comment = accum;
	flush();
    } else if ( qName == QString("function") ) {
	fetchtr_inlined_cpp( (const char *) fname, accum, tor,
			     context.latin1() );
    } else {
	flush();
    }
    return TRUE;
}

bool UiHandler::characters( const QString& ch )
{
    accum += ch;
    return TRUE;
}

bool UiHandler::fatalError( const QXmlParseException& exception )
{
    QString msg;
    msg.sprintf( "Parse error at line %d, column %d (%s).",
		 exception.lineNumber(), exception.columnNumber(),
		 exception.message().latin1() );
    qWarning( "XML error: %s", msg.latin1() );
    return FALSE;
}

void UiHandler::flush()
{
    if ( !context.isEmpty() && !source.isEmpty() )
	tor->insert( MetaTranslatorMessage(context.utf8(), source.utf8(),
					   comment.utf8(), QString::null,
					   TRUE) );
    source.truncate( 0 );
    comment.truncate( 0 );
}

void fetchtr_ui( const char *fileName, MetaTranslator *tor,
		 const char * /* defaultContext */, bool mustExist )
{
    QFile f( fileName );
    if ( !f.open(IO_ReadOnly) ) {
	if ( mustExist )
	    qWarning( "lupdate error: cannot open UI file '%s': %s", fileName,
		      strerror(errno) );
	return;
    }

    QTextStream t( &f );
    QXmlInputSource in( t );
    QXmlSimpleReader reader;
    reader.setFeature( "http://xml.org/sax/features/namespaces", FALSE );
    reader.setFeature( "http://xml.org/sax/features/namespace-prefixes", TRUE );
    reader.setFeature( "http://trolltech.com/xml/features/report-whitespace"
		       "-only-CharData", FALSE );
    QXmlDefaultHandler *hand = new UiHandler( tor, fileName );
    reader.setContentHandler( hand );
    reader.setErrorHandler( hand );

    if ( !reader.parse(in) )
	qWarning( "%s: Parse error in UI file", fileName );
    reader.setContentHandler( 0 );
    reader.setErrorHandler( 0 );
    delete hand;
    f.close();
}
