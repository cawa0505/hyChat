install:
	composer self-update
	composer install -vvv
	cp .env.example .env

start:
	./bin/watch run \
	--cmd "php" --args "./bin/hyperf, start" \
	--folder "/mnt/d/dnmp/www/hyChat/app" \
	--folder "/mnt/d/dnmp/www/hyChat/config" \
	--folder "/mnt/d/dnmp/www/hyChat/resources" \
	--folder "/mnt/d/dnmp/www/hyChat/routers" \
	--delay=3 \
	--autoRestart=true