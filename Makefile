install:
	symfony composer update

lint: lint.php-cs-fixer lint.phpstan

lint.php-cs-fixer:
	symfony php vendor/bin/php-cs-fixer fix

lint.phpstan:
	symfony php vendor/bin/phpstan analyze --memory-limit=-1

lint.php-cs-fixer@integration:
	symfony php vendor/bin/php-cs-fixer fix  --ansi --dry-run --diff

install@integration:
	composer install --ansi --verbose --no-interaction --no-progress --prefer-dist --optimize-autoloader --no-scripts --ignore-platform-reqs

lint.phpstan@integration:
	symfony php vendor/bin/phpstan --no-progress --ansi --no-interaction analyse --configuration ./phpstan.neon.dist
