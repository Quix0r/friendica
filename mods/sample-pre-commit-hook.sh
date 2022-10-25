#!/bin/sh

# INSTRUCTION: Copy this file to .git/hooks/pre-commit

DBSTRUCTURE_PHP="static/dbstructure.config.php"

# You might have to change this to run the unit tests:
UNIT_TEST_SCRIPT="/some/path/run_unit_tests.sh"

echo "$0: Checking syntax and code-style ..."
CHANGES=$(git status | grep "modified:" | grep ".php" | cut -d ":" -f 2)
ERRORS=""

for CHANGE in ${CHANGES};
do
	CHECK=$(php -l ${CHANGE} | grep -v "No syntax errors detected in")
	if [ -n "${CHECK}" ]
	then
		echo "${CHECK}"
		ERRORS="1"
	fi
	CHECK=$(./bin/dev/php-cs-fixer/vendor/bin/php-cs-fixer fix --dry-run ${CHANGE})
	if [ "$$" != "0" -a "$$" -lt 100 ]
	then
		echo "$0: Found code-style issue in '${CHANGE}'."
		ERRORS="1"
	fi
done

if [ -n "${ERRORS}" ]
then
	echo "$0: Some error(s) were found."
	exit 255
fi

DBSTRUCTURE=$(echo "${CHANGES}" | grep "${DBSTRUCTURE_PHP}")
if [ -n "${DBSTRUCTURE}" ]
then
	echo "$0: '${DBSTRUCTURE_PHP}' has changed. Updating documentation ..."
	./bin/console dbstructure dumpsql > database.sql || exit 255
	git add database.sql doc/database/*.md
fi

echo "$0: Running unit tests ..."
${UNIT_TEST_SCRIPT}
STATUS=$?
echo "$0: STATUS='${STATUS}'"

echo "$0: All checks passed."
exit 0
