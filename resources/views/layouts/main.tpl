<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{csrf_token}">
    <title>{block name="title"}Chain of Command{/block}</title>
    <link rel="stylesheet" href="{asset path='css/app.css'}">
</head>
<body>
    <header class="topbar">
        <a class="brand" href="{route name='chart'}">Chain of Command</a>
        <span class="company">Kestrel Labs</span>
        <nav class="nav">
            <a href="{route name='chart'}">Chart</a>
            <a href="{route name='people.index'}">People</a>
        </nav>
    </header>
    <main>{block name="content"}{/block}</main>
    <footer class="debug"><!--identity-map--></footer>
</body>
</html>
