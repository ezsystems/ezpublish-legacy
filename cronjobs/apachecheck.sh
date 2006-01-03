#!/bin/sh


# Startet den apache neu wenn es einen Segmentation-Fault oder glibc Fehler gibt
LOGFILE="/var/log/apache2/error_log"
PSOUTPUT="/tmp/psout.txt"
ERG=`/usr/bin/tail -n 20 $LOGFILE | grep "Segmentation"`
ERG2=`/usr/bin/tail -n 20 $LOGFILE | grep "glibc detected"`


# echo $ERG

if [[ "$ERG" != "" || "$ERG2" != "" ]];
then

source /etc/profile
/etc/init.d/apache2 restart

# nicht die Leerzeilen entfernen!
/bin/echo " APACHE RESTARTED BY CRONJOB
























" >> $LOGFILE

fi

/bin/ps ax > $PSOUTPUT

ERG3=`grep "apache2" $PSOUTPUT`

if [ "$ERG3" == "" ];
then
	/bin/echo "APACHE LAEUFT NICHT - NEUSTART" >> $LOGFILE
	/etc/init.d/apache2 stop
	/bin/sleep 1
	/bin/killall -9 apache2	
	/bin/sleep 1
	source /etc/profile
	/etc/init.d/apache2 start
fi




