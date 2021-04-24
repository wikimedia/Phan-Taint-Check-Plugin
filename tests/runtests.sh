#!/bin/bash
#
# Run the tests in integration.
# Usage: ./runtests.sh [-v] [testname]
#
# -v will add debug output. testname will run only that test
#

cd `dirname "$0"`/
tmpFile=`mktemp testtmp.XXXXXXXX`
trap "rm $tmpFile" exit
totalTests=0
failedTests=0
if [ "$1" = '-v' ]
	then SECCHECK_DEBUG=/dev/stderr
		shift
		export SECCHECK_DEBUG
	else SECCHECK_DEBUG=${SECCHECK_DEBUG:-/dev/null}
fi
testList=${1:-`ls integration`}

for i in $testList
do
	echo "Running test $i"
	SECURITY_CHECK_EXT_PATH=`pwd`"/integration/$i/"
	export SECURITY_CHECK_EXT_PATH
	totalTests=$((totalTests+1))
	php ../vendor/phan/phan/phan \
        	--project-root-directory "." \
            --allow-polyfill-parser \
        	--config-file "integration-test-config.php" \
        	--no-progress-bar \
        	--output "php://stdout" \
		-l "integration/$i" | tee "$SECCHECK_DEBUG"   > $tmpFile
	diff -u "integration/$i/expectedResults.txt" "$tmpFile"
	if [ $? -gt 0 ]
		then failedTests=$((failedTests+1))
	fi
done
if [ $failedTests -gt 0 ]
	then echo $failedTests out of $totalTests failed.
		exit 1
	else echo "All $totalTests passed."
fi
