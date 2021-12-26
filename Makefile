DEBIAN = debian
MYSQL = mysql

cs: ## Lista los contenedores que se están ejecutando
	docker ps -a

run: ## Inicia los contenedores
	docker network create prueba_tecnica_network || true
	docker-compose up -d
	docker exec -it ${DEBIAN} service apache2 restart

stop: ## Detiene la ejecución de los contenedores
	docker-compose stop ${DEBIAN} ${MYSQL}
	docker-compose rm ${DEBIAN} ${MYSQL}

delete: ## Elimina todos los contenedores
	docker-compose rm ${DEBIAN} ${MYSQL}

restart: ## Reinicia los contenedores
	$(MAKE) stop && $(MAKE) run

build: ## Genera todos los contenedores
	docker-compose build