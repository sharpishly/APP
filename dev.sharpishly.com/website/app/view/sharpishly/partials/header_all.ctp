<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Grants Page with HTML, CSS, and JavaScript">
    <title>{{{title}}}</title>
    <link rel="stylesheet" href="/{{{model}}}/css/styles.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="#" class="navbar-brand">{{{title}}}</a>
            <button class="navbar-toggle" id="navbar-toggle">☰</button>
            <ul class="navbar-menu" id="navbar-menu">
                <!-- populated by JavaScript -->
            </ul>
        </div>
    </nav>

        <!-- sub menu -->
        <header>
    <ul class="menu" id="menu">
        <li>
            <a href="#">Shop</a>
            <ul class="sub-menu">
                <!--li><a {{{add}}}>add</a></li>
                <li><a {{{records}}}>records</a></li>
                <li><a {{{terms}}}>terms</a></li>
                <li><a {{{policy}}}>policy</a></li-->
                <li><a {{{shop}}}>shop</a></li>
                <li><a {{{basket}}}>basket</a></li>
            </ul>
        </li>
        <li><a {{{login}}}>Login</a></li>
    </ul>
    </header>

    <!-- Main Content -->
    <main class="main-content" id="hero">
