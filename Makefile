install:
	composer self-update
	composer install -vvv
	cp .env.example .env

start:
	./bin/watch run \
	--cmd "php" --args "./bin/hyperf, start" \
	--folder "/mnt/d/www/hyChat/app" \
	--folder "/mnt/d/www/hyChat/config" \
	--folder "/mnt/d/www/hyChat/resources" \
	--folder "/mnt/d/www/hyChat/routers" \
	--delay=3 \
	--autoRestart=true