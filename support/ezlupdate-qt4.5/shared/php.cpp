//
// php.cpp for ezlupdate
//
// This file is based on cpp.cpp from lupdate/Qt Linguist,
// which is Copyright (C) 2009 Trolltech AS (www.trolltech.com).
//
// Gunnstein Lye <gl@ez.no>
// Created on: <5-Jun-2009 09:19:17 gl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

#include "translator.h"

#include <QtCore/QDebug>
#include <QtCore/QStack>
#include <QtCore/QString>
#include <QtCore/QTextCodec>
#include <QtCore/QTextStream>

QT_BEGIN_NAMESPACE

/* qmake ignore Q_OBJECT */

enum
{
    Tok_Eof, Tok_class, Tok_namespace, Tok_return, Tok_tr,
    Tok_trUtf8, Tok_translate, Tok_Ident, Tok_i18n, Tok_x18n,
    Tok_Comment, Tok_String, Tok_SString, Tok_Colon, Tok_Gulbrandsen,
    Tok_LeftBrace, Tok_RightBrace, Tok_LeftParen, Tok_RightParen,
    Tok_Comma, Tok_Semicolon
};

/*
  The tokenizer maintains the following global variables. The names
  should be self-explanatory.
*/
static QString yyFileName;
static int yyCh;
static bool yyCodecIsUtf8;
static bool yyForceUtf8;
static char yyIdent[128];
static size_t yyIdentLen;
static char yyComment[65536];
static size_t yyCommentLen;
static char yyString[16384];
static size_t yyStringLen;
static QStack<int> yySavedBraceDepth;
static QStack<int> yySavedParenDepth;
static int yyBraceDepth;
static int yyParenDepth;
static int yyLineNo;
static int yyCurLineNo;
static int yyBraceLineNo;
static int yyParenLineNo;

// the string to read from and current position in the string
static QTextCodec *yySourceCodec;
static bool yySourceIsUnicode;
static QString yyInStr;
static int yyInPos;

static uint getChar()
{
    forever {
        if (yyInPos >= yyInStr.size())
            return EOF;
        uint c = yyInStr[yyInPos++].unicode();
        if (c == '\\' && yyInPos < yyInStr.size() && yyInStr[yyInPos].unicode() == '\n') {
            ++yyCurLineNo;
            ++yyInPos;
            continue;
        }
        if (c == '\n')
            ++yyCurLineNo;
        return c;
    }
}

static uint getToken()
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
                    else if ( strcmp(yyIdent + 1, "zx18n") == 0 )
                        return Tok_x18n;
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
                                          " line %d", qPrintable(yyFileName),
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
                                QString hex = "0";

                                yyCh = getChar();
                                while ( isxdigit(yyCh) ) {
                                    hex += (char) yyCh;
                                    yyCh = getChar();
                                }
                                sscanf( qPrintable(hex), "%x", &n );
                                if ( yyStringLen < sizeof(yyString) - 1 )
                                    yyString[yyStringLen++] = (char) n;
                            } else if ( yyCh >= '0' && yyCh < '8' ) {
                                QString oct = "";

                                do {
                                    oct += (char) yyCh;
                                    yyCh = getChar();
                                } while ( yyCh >= '0' && yyCh < '8' );
                                sscanf( qPrintable(oct), "%o", &n );
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
                                QString hex = "0";

                                yyCh = getChar();
                                while ( isxdigit(yyCh) ) {
                                    hex += (char) yyCh;
                                    yyCh = getChar();
                                }
                                sscanf( qPrintable(hex), "%x", &n );
                                if ( yyStringLen < sizeof(yyString) - 1 )
                                    yyString[yyStringLen++] = (char) n;
                            } else if ( yyCh >= '0' && yyCh < '8' ) {
                                QString oct = "";

                                do {
                                    oct += (char) yyCh;
                                    yyCh = getChar();
                                } while ( yyCh >= '0' && yyCh < '8' );
                                sscanf( qPrintable(oct), "%o", &n );
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

static uint yyTok;

static bool match(uint t)
{
    bool matches = (yyTok == t);
    if (matches)
        yyTok = getToken();
    return matches;
}

static bool matchString(QString *s)
{
    bool matches = (yyTok == Tok_String);
    s->clear();
    while (yyTok == Tok_String) {
        *s += yyString;
        yyTok = getToken();
    }
    return matches;
}

static bool matchSString(QString *s)
{
    bool matches = (yyTok == Tok_SString);
    s->clear();
    while ( yyTok == Tok_SString ) {
        *s += yyString;
        yyTok = getToken();
    }
    return matches;
}

static QString transcode(const QString &str, bool utf8)
{
    static const char tab[] = "abfnrtv";
    static const char backTab[] = "\a\b\f\n\r\t\v";
    const QString in = (!utf8 || yySourceIsUnicode)
        ? str : QString::fromUtf8(yySourceCodec->fromUnicode(str).data());
    QString out;

    out.reserve(in.length());
    for (int i = 0; i < in.length();) {
        ushort c = in[i++].unicode();
        if (c == '\\') {
            if (i >= in.length())
                break;
            c = in[i++].unicode();

            if (c == '\n')
                continue;

            if (c == 'x') {
                QByteArray hex;
                while (i < in.length() && isxdigit((c = in[i].unicode()))) {
                    hex += c;
                    i++;
                }
                out += hex.toUInt(0, 16);
            } else if (c >= '0' && c < '8') {
                QByteArray oct;
                int n = 0;
                oct += c;
                while (n < 2 && i < in.length() && (c = in[i].unicode()) >= '0' && c < '8') {
                    i++;
                    n++;
                    oct += c;
                }
                out += oct.toUInt(0, 8);
            } else {
                const char *p = strchr(tab, c);
                out += QChar(QLatin1Char(!p ? c : backTab[p - tab]));
            }
        } else {
            out += c;
        }
    }
    return out;
}

static void recordMessage(
    Translator *tor, int line, const QString &context, const QString &text, const QString &comment,
    const QString &extracomment,  bool utf8, bool plural)
{
    TranslatorMessage msg(
        transcode(context, utf8), transcode(text, utf8), transcode(comment, utf8), QString(),
        yyFileName, line, QStringList(),
        TranslatorMessage::Unfinished, plural);
    msg.setExtraComment(transcode(extracomment.simplified(), utf8));
    if ((utf8 || yyForceUtf8) && !yyCodecIsUtf8 && msg.needs8Bit())
        msg.setUtf8(true);
    tor->extend(msg);
}

static void parse( Translator *tor, const QString &initialContext, const QString &defaultContext )
{
    QMap<QString, QString> qualifiedContexts;
    QStringList namespaces;
    QString context;
    QString ext;
    QString text;
    QString comment;
    QString extracomment;
    QString functionContext = initialContext;
    QString prefix;
    int line;
    bool utf8 = FALSE;

    yyTok = getToken();
    while ( yyTok != Tok_Eof ) {
        switch ( yyTok ) {
            case Tok_i18n:
                utf8 = FALSE;
                line = yyLineNo;
                yyTok = getToken();
                if ( match( Tok_LeftParen ) &&
                     ( matchString( &context ) || matchSString( &context ) ) &&
                     match( Tok_Comma ) &&
                     ( matchString( &text ) || matchSString( &text ) ) )
                {
                    if ( ( match( Tok_Comma ) &&
                           ( matchString( &comment ) || matchSString( &comment ) ) &&
                           match( Tok_RightParen ) ) == false )
                    {
                        comment = "";
                    }
                    bool plural = false; // We don't support plural yet
                    recordMessage(tor, line, context, text, comment, extracomment, utf8, plural);
                }
//                 else
//                     qDebug( " --- token failed ------------" );
                break;
            case Tok_x18n:
                utf8 = FALSE;
                line = yyLineNo;
                yyTok = getToken();
                if ( match( Tok_LeftParen ) &&
                     ( matchString( &ext ) || matchSString( &ext ) ) &&
                     match( Tok_Comma ) &&
                     ( matchString( &context ) || matchSString( &context ) ) &&
                     match( Tok_Comma ) &&
                     ( matchString( &text ) || matchSString( &text ) ) )
                {
                    if ( ( match( Tok_Comma ) &&
                           ( matchString( &comment ) || matchSString( &comment ) ) &&
                           match( Tok_RightParen ) ) == false )
                    {
                        comment = "";
                    }
                    bool plural = false; // We don't support plural yet
                    recordMessage(tor, line, context, text, comment, extracomment, utf8, plural);
                }
//                 else
//                     qDebug( " --- token failed ------------" );
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
                comment = comment.simplified();
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
                    namespaces.erase( namespaces.isEmpty() ? namespaces.end() : --namespaces.end() );
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


bool loadPHP(Translator &translator, QIODevice &dev, ConversionData &cd)
{
    QString defaultContext = cd.m_defaultContext;

    yyCodecIsUtf8 = (translator.codecName() == "UTF-8");
    yyForceUtf8 = false;
    QTextStream ts(&dev);
    QByteArray codecName = cd.m_codecForSource.isEmpty()
        ? translator.codecName() : cd.m_codecForSource;
    ts.setCodec(QTextCodec::codecForName(codecName));
    ts.setAutoDetectUnicode(true);
    yySourceCodec = ts.codec();
    if (yySourceCodec->name() == "UTF-16")
        translator.setCodecName("System");
    yySourceIsUnicode = yySourceCodec->name().startsWith("UTF-");
    yyInStr = ts.readAll();
    yyInPos = 0;
    yyFileName = cd.m_sourceFileName;
    yySavedBraceDepth.clear();
    yySavedParenDepth.clear();
    yyBraceDepth = 0;
    yyParenDepth = 0;
    yyCurLineNo = 1;
    yyBraceLineNo = 1;
    yyParenLineNo = 1;
    yyCh = getChar();

    parse(&translator, defaultContext, defaultContext);

    return true;
}

int initPHP()
{
    Translator::FileFormat format;
    format.extension = QLatin1String("php");
    format.fileType = Translator::FileFormat::SourceCode;
    format.priority = 0;
    format.description = QObject::tr("PHP source files");
    format.loader = &loadPHP;
    format.saver = 0;
    Translator::registerFileFormat(format);
    return 1;
}

Q_CONSTRUCTOR_FUNCTION(initPHP)

QT_END_NAMESPACE
