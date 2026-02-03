<!DOCTYPE html>
<head>
    <title>Shellhop.com</title>
    <link href="/style.css" rel="stylesheet">
</head>
<body>
    <!-- Including the header file -->
    <?php
    require_once("../config.php");
    require_once(ROOT_PATH . "/resources/header.php");
    ?>

    <main>
        <h1 class="page_header">Learn</h1>
        <section class="introduction_section">
            <h2 class="">Getting Started!</h2>
            <p>Welcome to <b>Learn</b>!, here is where you can deep dive into many topics related to cyber security and ethical hacking! Please do keep in mind this site is <i>very early</i> in its development, and as a result, the content here will be extremely limited! (for now :D)<br>To get started, click any of the available topics you see here, and it will take you to the appropriate learn article.</p>
        </section>

        <section>
            <h3><a href="/learn/host_discovery.php" target="_blank">Host Discovery</a></h3>
            <p>Get started discovering hosts with active/passive reconnaissance techniques!</p>
        </section>
    </main>

    <!-- Including footer here -->
    <?php
    require_once(ROOT_PATH . '/resources/footer.php')
    ?>

</body>