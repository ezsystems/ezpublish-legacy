import os, sys, subprocess
from glob import glob

def isBackupFile(filename):
    if filename[-1] == "~":
        return True
    return False


def iterFiles( start ):
    for path, dirs, files in os.walk(start, topdown=False):
        for src in files:
            if not isBackupFile(src):
                yield os.path.join(path, src)
 
total = 0
filenames = [ x[14:] for x in iterFiles("new_templates")]
arguments = 15
errorcount = 0
for nr in range(0, len(filenames), arguments):
    proc_call= ["php", "bin/neo/eztc.php"]
    proc_call += filenames[nr:nr+arguments]
    retcode = subprocess.call(proc_call)
    if retcode != 0:
        errorcount += 1
    total += len(proc_call) - 2


print "Compiled %i files" % total
if errorcount != 0:
    print "Found errors :("

sys.exit(errorcount)
