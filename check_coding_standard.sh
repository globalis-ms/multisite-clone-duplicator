#!/bin/bash

declare -r dir="."
declare -r standard="WordPress"

echo "Cleaning temp .cs files ... "
for cs in $(find $dir -name "*.cs")
do
    rm $cs
done

if [ "$1" != "--clean" ]
then
	echo ""
	echo "Parsing all .php files ... "
	echo ""
	for php in $(find $dir -name "*.php")
	do
	    cs="$php.cs"
	    results=$(phpcs --standard=$standard $php)
	    if [ $? != 0 ]
	    then
	    	echo "Error / Warning found : see $cs"
	    	echo "$results" > $cs
	    fi
	done
	echo ""
fi

echo "Done !"