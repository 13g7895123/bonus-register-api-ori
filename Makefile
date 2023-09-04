projectName = 'api-ori'

up:
	docker-compose up -d
stop:
	docker-compose stop
update:
	docker exec -it $(projectName) bash -c "composer install"
w:
	docker exec -it $(projectName) bash -c "npx tailwindcss -i ./src/input.css -o ./dist/output.css --watch"
