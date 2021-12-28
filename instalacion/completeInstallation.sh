#!/bin/bash
cp -R archivos_completar/vendor ../;fuente_codigo
cp archivos/completar/.env ../fuente_codigo
chmod -R 777 ../prueba_tecnica;
docker exec -it debian php /Aplicaciones/prueba_tecnica/artisan migrate --force
printf "\n\nAplicacion inicializada correctamente\n";