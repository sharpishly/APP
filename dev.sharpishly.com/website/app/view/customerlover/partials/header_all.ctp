<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Customer Lover with HTML, CSS, and JavaScript">
    <title>Customer Lover</title>
    <link rel="stylesheet" href="/customerlover/css/styles.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="#" class="navbar-brand">Customer Lover</a>
            <button class="navbar-toggle" id="navbar-toggle">☰</button>
            <ul class="navbar-menu" id="navbar-menu">
            </ul>
        </div>
    </nav>

    <!-- sub menu -->
    <header>
    <ul class="menu">
        <li>
            <a href="#">Actions</a>
            <ul class="sub-menu">
                <li><a {{{register}}}>Register</a></li>
                <li><a {{{business}}}>Business</a></li>
                <li><a {{{funding}}}>Funding</a></li>
            </ul>
        </li>
        <li>
            <a href="#">Statuses</a>
            <ul class="sub-menu">
                <li><a href="#">Active</a></li>
                <li><a href="#">Pending</a></li>
                <li><a href="#">Completed</a></li>
                <li><a href="#">Deleted</a></li>
            </ul>
        </li>
        <li><a {{{login}}}>Login</a></li>
    </ul>
    </header>

    <!-- Main Content -->
    <main class="main-content" id="hero">

        <!-- Breadcrumbs -->
        <div class="breadcrumbs">
            <a href="#">Home</a> &gt;
            <a href="#">Features</a> &gt;
            <span>Widgets</span>
        </div>