#!/bin/sh
for TEST in `find . -name 'tests'` ; do
	phpunit $TEST ;
done

