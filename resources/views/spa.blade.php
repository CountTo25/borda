<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width,initial-scale=1'>

    <title>Svelte app</title>

    <link rel='icon' type='image/png' href='/favicon.png'>
    <link rel='stylesheet' href='/spa/global.css'>
    <link rel='stylesheet' href='/spa/bundle.css'>
    <link rel='stylesheet' href='/spa/bootstrap-grid.css'>
    <link rel='stylesheet' href='/spa/borda.css'>

    <script>
        window.__prefetched = {{ Illuminate\Support\Js::from($prefetch)}};
    </script>
    <script defer src='/spa/bundle.js'></script>
</head>

<body>
</body>
</html>
