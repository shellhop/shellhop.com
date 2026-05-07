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
        <h1>POP3 and IMAP Footprinting</h1>
        <section>
            <h2>Introduction</h2>
            <p>IMAP and POP are protocols designed to retrieve emails from an smtp server for viewing as a user. Email clients use both SMTP and POP/IMAP to send and retrieve emails respectively. IMAP uses port 143 to start a connection and uses ASCII formatted text based commands to interact with the server.</p>
            <p>Once a connection is established, the user authenticates with a username and password. Once authenticated, we can access the desired mailbox.</p>
            <p>By default, IMAP transmits information in plaintext, however, in order to increase security, many servers now only allow traffic via an encrypted connection on port 143 or an alternative port like 993.</p>
            <p>IMAP and POP work differnently. POP will download all emails stored on the email server, and then those emails will be deleted from the server, leaving only a local copy. IMAP synchronizes mailboxes and allows for browsing directly on the server. This means you can access emails from any device.</p>
        </section>

        <section>
            <h2>IMAP Commands</h2>
            <table>
                <tr>
                    <th>Command</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>1 LOGIN username password</td>
                    <td>Login to the server.</td>
                </tr>
                <tr>
                    <td>1 LIST "" *</td>
                    <td>Lists all directories on the server</td>
                </tr>
                <tr>
                    <td>1 CREATE "INBOX"</td>
                    <td>Creates a mailbox with the specified name.</td>
                </tr>
                <tr>
                    <td>1 DELETE "INBOX"</td>
                    <td>Deletes the specified mailbox.</td>
                </tr>
                <tr>
                    <td>1 RENAME "FROMTHIS" "TOTHIS"</td>
                    <td>Renames mailbox.</td>
                </tr>
                <tr>
                    <td>1 LSUB "" *</td>
                    <td>Returns a subset of names from the set of names hat the User has declared 'active' or 'subscribed'.</td>
                </tr>
                <tr>
                    <td>1 SELECT INBOX</td>
                    <td>Selects a mailbox to access.</td>
                </tr>
                <tr>
                    <td>1 UNSELECT INBOX</td>
                    <td>Exists mailbox</td>
                </tr>
                <tr>
                    <td>1 FETCH &lt;ID&gt; all</td>
                    <td>Retrieves data associated with an email.</td>
                </tr>
                <tr>
                    <td>1 FETCH &lt;ID&gt; (body[])</td>
                    <td>Fetches the body of the email for reading.</td>
                </tr>
                <tr>
                    <td>1 CLOSE</td>
                    <td>Removes all messages with the 'deleted' flag.</td>
                </tr>
                <tr>
                    <td>1 LOGOUT</td>
                    <td>closes connection.</td>
                </tr>
            </table>
        </section>

        <section>
            <h2>POP3 Commands</h2>
            <table>
                <tr>
                    <th>Command</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>USER username</td>
                    <td>Declares the user.</td>
                </tr>
                <tr>
                    <td>PASS password</td>
                    <td>Declares password</td>
                </tr>
                <tr>
                    <td>STAT</td>
                    <td>Requests the number of emails stored by the server.</td>
                </tr>
                <tr>
                    <td>LIST</td>
                    <td>Requests number and size of all emails</td>
                </tr>
                <tr>
                    <td>RETR id</td>
                    <td>Delivers the requested email by its id</td>
                </tr>
                <tr>
                    <td>DELE id</td>
                    <td>Deletes email by id</td>
                </tr>
                <tr>
                    <td>CAPA</td>
                    <td>Requests server to display capabilities</td>
                </tr>
                <tr>
                    <td>RSET</td>
                    <td>Requests server to reset the transmitted information</td>
                </tr>
                <tr>
                    <td>QUIT</td>
                    <td>Closes connection</td>
                </tr>
            </table>
        </section>

        <section>
            <h2>Dangerous settings</h2>
            <p>There are several dangerous settings on a number of POP and IMAP servers that can be abused.</p>
            <table>
                <tr>
                    <th>Setting</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>auth_debug</td>
                    <td>Enables authentication debug logging</td>
                </tr>
                <tr>
                    <td>auth_debug_passwords</td>
                    <td>This setting adjusts the verbosity that logs are stored in</td>
                </tr>
                <tr>
                    <td>auth_verbose</td>
                    <td>Logs unsuccessful attempts and reasons</td>
                </tr>
                <tr>
                    <td>auth_verbose_passwords</td>
                    <td>Passwords used for authentication are stored.</td>
                </tr>
                <tr>
                    <td>auth_anonymous_username</td>
                    <td>Specifies the username for the anonymous sasl login mechanism</td>
                </tr>
            </table>
        </section>

        <section>
            <h2>Footprinting the service</h2>
            <p>Ports 110 and 995 are used for POP, wheras ports 143 and 993 are used for IMAP. We can see if these ports are open with a basic nmap scan, and scripts can be enabled to quickly extract valuable information about the service.</p>
            <code>$ sudo nmap -sV -sC -p110,143,993,995 10.0.0.3</code>
            <p>We can also use curl with the '-v' flag to see details on the ssl certificate used, and details on the banner which may contain server version information.</p>
            <code>$ curl -k 'imaps://10.0.0.3' --user user:p4ssword -v</code>
            <p>For encrypted connections, we can also use openssl.</p>
            <code>openssl s_client -connect 10.0.0.3:pop3s</code><br>
            <code>$ openssl s_client -connect 10.0.0.3:imaps</code>
        </section>
    </main>

    <!-- Including footer here -->
    <?php
    require_once(ROOT_PATH . '/resources/footer.php')
    ?>

</body>