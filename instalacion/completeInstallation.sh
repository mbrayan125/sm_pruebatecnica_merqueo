#!/bin/bash
cp -R archivos_completar/vendor /Aplicaciones/prueba_tecnica;
cp archivos/completar/.env /Aplicaciones/prueba_tecnica;
chmod -R 777 /Aplicaciones/prueba_tecnica;
printf "\n\nAplicacion completada correctamente\n";