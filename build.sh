docker exec borda_app_1 php artisan config:cache;
docker exec borda_app_1 php artisan build:lang;
cd svelte && npm run build;
cd public && cp -R global.css ../../public/spa;
cp -R bootstrap-grid.css ../../public/spa;
cp -R fonts ../../public;
cp -R borda.css ../../public/spa;
cp -R build/bundle.css ../../public/spa;
echo 'finished building frontend'
