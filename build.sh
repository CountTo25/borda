cd svelte && npm run build;
cd public && cp -R global.css ../../public/spa;
cp -R bootstrap-grid.css ../../public/spa;
cp -R fonts ../../public;
cp -R borda.css ../../public/spa;
cp -R build/bundle.css ../../public/spa;
