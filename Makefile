lint: lint.php-cs-fixer lint.phpstan

lint.php-cs-fixer:
	symfony php vendor/bin/php-cs-fixer fix

lint.phpstan:
	symfony php vendor/bin/phpstan analyze --memory-limit=-1
