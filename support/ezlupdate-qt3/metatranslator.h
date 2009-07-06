/**********************************************************************
**   Copyright (C) 2000 Trolltech AS.  All rights reserved.
**
**   metatranslator.h
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

#ifndef METATRANSLATOR_H
#define METATRANSLATOR_H

#include <qmap.h>
#include <qstring.h>
#include <qtranslator.h>
#include <qvaluelist.h>

class QTextCodec;

class MetaTranslatorMessage : public QTranslatorMessage
{
public:
    enum Type { Unfinished, Finished, Obsolete };

    MetaTranslatorMessage();
    MetaTranslatorMessage( const char *context, const char *sourceText,
			   const char *comment,
			   const QString& translation = QString::null,
			   bool utf8 = FALSE, Type type = Unfinished );
    MetaTranslatorMessage( const MetaTranslatorMessage& m );

    MetaTranslatorMessage& operator=( const MetaTranslatorMessage& m );

    void setType( Type nt ) { ty = nt; }
    Type type() const { return ty; }
    bool utf8() const { return utfeight; }

    bool operator==( const MetaTranslatorMessage& m ) const;
    bool operator!=( const MetaTranslatorMessage& m ) const
    { return !operator==( m ); }
    bool operator<( const MetaTranslatorMessage& m ) const;
    bool operator<=( const MetaTranslatorMessage& m )
    { return !operator>( m ); }
    bool operator>( const MetaTranslatorMessage& m ) const
    { return this->operator<( m ); }
    bool operator>=( const MetaTranslatorMessage& m ) const
    { return !operator<( m ); }

private:
    bool utfeight;
    Type ty;
};

class MetaTranslator
{
public:
    MetaTranslator();
    MetaTranslator( const MetaTranslator& tor );

    MetaTranslator& operator=( const MetaTranslator& tor );

    bool load( const QString& filename );
    bool save( const QString& filename ) const;
    bool release( const QString& filename, bool verbose = FALSE ) const;

    bool contains( const char *context, const char *sourceText,
		   const char *comment ) const;
    void insert( const MetaTranslatorMessage& m );

    void stripObsoleteMessages();
    void stripEmptyContexts();

    void setCodec( const char *name );
    QString toUnicode( const char *str, bool utf8 ) const;

    QValueList<MetaTranslatorMessage> messages() const;
    QValueList<MetaTranslatorMessage> translatedMessages() const;

private:
    typedef QMap<MetaTranslatorMessage, int> TMM;
    typedef QMap<int, MetaTranslatorMessage> TMMInv;

    TMM mm;
    QCString codecName;
    QTextCodec *codec;
};

#endif
