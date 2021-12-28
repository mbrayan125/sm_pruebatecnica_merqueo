#!/bin/bash
make build;
make run;
cd installation/;
cp -R ./archivos_completar/vendor ../fuente_codigo/
cp ./archivos_completar/.env ../fuente_codigo/
chmod -R 777 ../prueba_tecnica;
cd ../;
docker exec -it debian php /Aplicaciones/prueba_tecnica/artisan migrate --force
printf "\n\nAplicacion inicializada correctamente\n";