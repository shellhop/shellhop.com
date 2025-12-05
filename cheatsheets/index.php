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
        <h2 class="">What is shellhop?</h2>
        <p>Shellhop, put simply, is a <i>digital namespace</i>. Shellhop exists as a group of physical and digital infrastructure, combining cloud and local solutions into a hybrid structure that allows me to practice setting up, maintaining, and testing various digital technologies. This site, for example, is hosted on a cloud server under digital ocean. It is running on an apache2 server. Other domains, such as 'gaming.shellhop.com' resolves to a locally hosted proxmox server that serves game servers for various clients. <br>Some services within shellhop are publically accessible, like this site and my game servers, others however, are internally accessible only, such as 'lab.shellhop.com', which is a comprehensive ethical hacking labratory environment made up of linux clients and servers, as well as windows servers and clients. Having an organized, centralized and comprehensive digital namespace has allowed me to safely experiment and gain experience with all things tech and security.</p>
        <h3>Security</h3>
        <p>Practicing security principles, offensive and defensive, has always been the main goal of shellhop. 'To reverse engineer, one must first learn to engineer' is an idiom I choose to employ where I can, with offense and defensive existing as two sides of the same coin, meaning one cannot be mastered or practiced in isolation, and both are equally important to understand regardless of which side of the coin you prefer.<br>Through putting shellhop together, countless decisions were, and continue to be made regarding security. These security considerations range from layer 1 considerations, (e.g. physically securing my local servers, using wired connections exclusively, purchasing a lockable server case etc.), through to layer 7, (e.g. ensuring my services themselves are up to date, limiting access to services at application level with username whitelisting) and far more.</p>
        <h3>Layout</h3>
        <p>The layout of the main shellhop site, meaning the site you are currently visiting, is as follows:<br><b>Learn</b>: This section is designed to teach topics related to security and technology. There will be more and less detailed sections within this section, and the topics are broken down into the categories 'offensive', 'defensive' and 'general'. Offensive/defensive sections are related to security, 'general' sections are useful principles that can be applied to either field or are completely unrelated to security. The purpose of this section is to demonstrate knowledge through the creation of learning material, as well as provide references for myself or others looking to refresh or dive deeper into certain topics.<br><b>Projects</b>: The projects section details several projects I have worked on related to security or technology. This acts as a personal portfolio, and so may be less applicable to you if you are here to learn technology, although perhaps some inspiration can be gained through the content in this section.<br><b>Cheatsheets</b>: This section contains quickly accessible 'how to' sheets for common 'jobs' in technology. Some examples may include 'Securely configuring a system account/service in Systemd', 'Getting a shell: quick reference' and 'Nmap useful flags'. This section will mostly contain quick references for the jobs that I personally find popping up more and more, and will be significantly less detailed than the information in the 'learn' section, although some overlap will exist.</p>
        </section>
        <section>
            <h1>Considerations</h1>
            <h2>Footnote</h2>
            <p>I would like to use this section to clarify some things, I am not a website designer, I am not a web application developer (yet), I have a wide understanding of many internet and computer science topics, however I would not consider myself an expert in any field by any means. My end goal is to specialise in ethical hacking, and am constantly working on some kind of personal/professional development to further that goal. Every line of code for this site, and any of my other projects, has been written by me. Even the development of <i>this site</i>is my first real attempt at creating a functional website, and so is bound to undergo serious and regular changes. If content seems to be missing, or the site itself seems barebones, remember, this is by design. This site is designed to centralise my notes and record my development, not to serve as an end product in and of itself. This may change down the line, but for now, this site acts simply as a portfolio and a simple conglomerate of my learning, notes and projects.</p>
        </section>
    </main>

    <!-- Including footer here -->
    <?php
    require_once(ROOT_PATH . '/resources/footer.php')
    ?>

</body>