test::
	@bin/phpunit -c phpunit.xml \
		--enforce-time-limit \
		--coverage-html Build/Reports/coverage \
		Tests