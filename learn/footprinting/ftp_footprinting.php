<!DOCTYPE html>
<head>
    <title>Shellhop.com</title>
    <link href="/style.css" rel="stylesheet">
</head>
<body>

    <!-- Including the header file -->
    <?php
    require_once("../../config.php");
    require_once(ROOT_PATH . "/resources/header.php");
    ?>

    <main>
        <h1>FTP Enumeration</h1>
        <section>
            <h2>FTP Protocol</h2>
            <p>FTP is a file transfer protocol that typically runs over TCP on port 21. It is considered an application level protocol, and thus relies on a fully initialised TCP connection between two devices to transfer application data. In this case, the data itself is that of a file. Port 21 is only used for the initial connection, as well as the sending and recieving of status codes and commands, the port used for data transmission is TCP port 20, which has a seperate initialized communication channel for data transfer.</p>
            <p>FTP infrastructure is usually set up with an FTP server, and a client program that will connect to, authenticate with, and move files between itself and the server.</p>
            <p>There is a distinction between the two 'modes' of FTP, <b>Active</b> and <b>Passive</b>.</p>
            <p>In the active variant of FTP, once a connection is made over port 21, the server will then attempt to initiate the connection for data transmission over port 20 to the client. However, when there is a firewall present, blocking all external connection attempts, FTP would cease to function as expected as the connection would be blocked by the clients firewall. Because of this, 'passive' mode was developed, in which the FTP server will announce a port for the client to initiate data transfer.</p>
            <p>FTP understands a variety of commands and status codes, however not all codes and commands may be implemented depending on the server configuration.</p>
            <p>When we present the server with a command, we will recieve a status code in response.</p>
            <p>We usually need some credentials to interact with FTP, </p>


        </section>
    </main>

    <!-- Including footer here -->
    <?php
    require_once(ROOT_PATH . '/resources/footer.php')
    ?>

</body>