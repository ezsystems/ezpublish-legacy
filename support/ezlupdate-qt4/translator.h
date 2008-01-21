/****************************************************************************
**
** Copyright (C) 1992-2005 Trolltech AS. All rights reserved.
**
** This file is part of the Qt Linguist of the Qt Toolkit.
**
** This file may be used under the terms of the GNU General Public
** License version 2.0 as published by the Free Software Foundation
** and appearing in the file LICENSE.GPL included in the packaging of
** this file.  Please review the following information to ensure GNU
** General Public Licensing requirements will be met:
** http://www.trolltech.com/products/qt/opensource.html
**
** If you are unsure which license is appropriate for your use, please
** review the following information:
** http://www.trolltech.com/products/qt/licensing.html or contact the
** sales department at sales@trolltech.com.
**
** This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING THE
** WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE.
**
****************************************************************************/

#ifndef TRANSLATOR_H
#define TRANSLATOR_H

#include "QtCore/qobject.h"
#include "QtCore/qbytearray.h"
#include <qtranslator.h>

class TranslatorPrivate;
template <typename T> class QList;

class TranslatorMessage
{
public:
    TranslatorMessage();
    TranslatorMessage(const char * context, const char * sourceText,
                       const char * comment,
                       const QString& translation = QString());
    explicit TranslatorMessage(QDataStream &);
    TranslatorMessage(const TranslatorMessage & m);

    TranslatorMessage & operator=(const TranslatorMessage & m);

    uint hash() const { return h; }
    const char *context() const { return cx.isNull() ? 0 : cx.constData(); }
    const char *sourceText() const { return st.isNull() ? 0 : st.constData(); }
    const char *comment() const { return cm.isNull() ? 0 : cm.constData(); }

    inline void setTranslation(const QString & translation);
    QString translation() const { return tn; }

    enum Prefix { NoPrefix, Hash, HashContext, HashContextSourceText,
                  HashContextSourceTextComment };
    void write(QDataStream & s, bool strip = false,
                Prefix prefix = HashContextSourceTextComment) const;
    Prefix commonPrefix(const TranslatorMessage&) const;

    bool operator==(const TranslatorMessage& m) const;
    bool operator!=(const TranslatorMessage& m) const
    { return !operator==(m); }
    bool operator<(const TranslatorMessage& m) const;
    bool operator<=(const TranslatorMessage& m) const
    { return !m.operator<(*this); }
    bool operator>(const TranslatorMessage& m) const
    { return m.operator<(*this); }
    bool operator>=(const TranslatorMessage& m) const
    { return !operator<(m); }

private:
    uint h;
    QByteArray cx;
    QByteArray st;
    QByteArray cm;
    QString tn;

    enum Tag { Tag_End = 1, Tag_SourceText16, Tag_Translation, Tag_Context16,
               Tag_Hash, Tag_SourceText, Tag_Context, Tag_Comment,
               Tag_Obsolete1 };
};
Q_DECLARE_TYPEINFO(TranslatorMessage, Q_MOVABLE_TYPE);

inline void TranslatorMessage::setTranslation(const QString & atranslation)
{ tn = atranslation; }

class Translator : public QTranslator
{
    Q_OBJECT
public:
    explicit Translator(QObject *parent = 0);
    ~Translator();

    virtual TranslatorMessage findMessage(const char *, const char *,
                                          const char * = 0) const;
    virtual QString translate(const char *context, const char *sourceText,
                              const char *comment = 0) const
        { return findMessage(context, sourceText, comment).translation(); }

    bool load(const QString & filename,
               const QString & directory = QString(),
               const QString & search_delimiters = QString(),
               const QString & suffix = QString());
    bool load(const uchar *data, int len);

    void clear();

#ifndef QT_NO_TRANSLATION_BUILDER
    enum SaveMode { Everything, Stripped };

    bool save(const QString & filename, SaveMode mode = Everything);

    void insert(const TranslatorMessage&);
    inline void insert(const char *context, const char *sourceText, const QString &translation) {
        insert(TranslatorMessage(context, sourceText, "", translation));
    }
    void remove(const TranslatorMessage&);
    inline void remove(const char *context, const char *sourceText) {
        remove(TranslatorMessage(context, sourceText, ""));
    }
    bool contains(const char *, const char *, const char * comment = 0) const;

    void squeeze(SaveMode = Everything);
    void unsqueeze();

    QList<TranslatorMessage> messages() const;
#endif

    bool isEmpty() const;

private:
    Q_DISABLE_COPY(Translator)
    TranslatorPrivate *d;
};

#endif // TRANSLATOR_H
