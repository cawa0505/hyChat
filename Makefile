install:
	composer self-update
	composer install -vvv
	cp .env.example .env

start:
	./bin/watch run \
	--cmd "php" --args "./bin/hyperf, start" \
	--folder "/var/www/html/hyChat/app" \
	--folder "/var/www/html/hyChat/config" \
	--folder "/var/www/html/hyChat/resources" \
	--folder "/var/www/html/hyChat/routers" \
	--delay=3 \
	--autoRestart=true