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
        <h1>SMTP Footprinting</h1>
        <section>
            <h2>Introduction</h2>
            <p>SMTP is 'Simple Mail Transfer Protocol'. It runs on TCP port 25 by default, however newer servers use 587 as well.</p>
            <p>The purpose of SMTP is to send emails. It can be used between a client and an 'outgoing mail server' or between two mail servers.</p>
            <p>IMAP is only used to 'send' emails, which requires certain authentication. To then 'pull' emails from a server, you use different protocols which we will come to later.</p>
            <p>SMTP works without anyy encryption, which means protocol commands, authentication details and emails are sent in plantext.</p>
            <p>Usually, to use TLS, the client sends a 'STARTTLS' command which encrypts the session.</p>

            <p>One of the main goals of SMTP is preventing spam. This works by splitting up the process into several 'agents' and assigning each a role.</p>
            <p>The individual sending the email, or 'Mail User Agent', converts their email into a header and a body and uploads both to the server. The server is called the 'Mail Transfer Agent', which checks the email for spam and size and then stores it.</p>
            <p>Sometimes, to relieve the MTA, it is preceeded by a 'Mail Submission Agent', which checks certain validity criteria like the origin. This MSA is also called a 'relay'.</p>
            <p>Once the email arrives at the destination server, it is reassembled and stored, later to be retrieved with the mailbox protocols IMAP and POP.</p>
        </section>

        <section>
            <h2>The problems</h2>
            <p>There are two main issues with SMTP that come from its design. The first is that there is very little feedback in the protocol. When an error occurs, it is unlikely that anything other than an english message stating the nature of the issue and the header of the undelivered message is returned.</p>
            <p>The second issue is that users are not authenticated at the time a connection is established, therefore the sender of the email is 'unreliable'. Because of this, open SMTP relays are often used to send spam messages en masse, basically spoofing their origin address to send malicious messages and not be traced.</p>
            <p>There are now security measures in place that prevent these issues, such as DomainKeys DKIM, and the Sender Policy Framework or SPF.</p>
            <p>Now, most configurations of SMTP are actually ESMTP, or 'Extended SMTP' that support TLS connections. Most of the time, after a connection is made, the 'STARTTLS' command is sent via the connection which initializes SSL-protected communications. From then on, users can authenticate freely using the 'AUTH PLAIN' extension.</p>
        </section>

        <section>
            <h2>Interacting with SMTP</h2>
            <p>We can initiate a connection to an SMTP server with telnet.</p>
            <code>$ telnet 10.0.0.3 25</code>
            <p>We now have the option of several commands.</p>
            <table>
                <tr>
                    <td>Command</td>
                    <td>Description</td>
                </tr>
                <tr>
                    <td>AUTH PLAIN</td>
                    <td>This is a service extension that will authenticate the client.</td>
                </tr>
                <tr>
                    <td>HELO</td>
                    <td>The client logs in with its own computer name and starts a session.</td>
                </tr>
                <tr>
                    <td>MAIL FROM</td>
                    <td>Client names the 'email sender' in the format 'MAIL FROM:name@shellhop.lab'</td>
                </tr>
                <tr>
                    <td>RCPT TO</td>
                    <td>The recipient of the email, in the format 'RCPT TO:admin@shellhop.com</td>
                </tr>
                <tr>
                    <td>DATA</td>
                    <td>This begins data transfer. You can write what you want to write here, and it will be the data of the message, end the message with a single period on its own line and press enter.</td>
                </tr>
                <tr>
                    <td>RSET</td>
                    <td>The client aborts the current transmission and keeps the connection with the server.</td>
                </tr>
                <tr>
                    <td>VRFY</td>
                    <td>This is used to verify if a mailbox is available to send a message to.</td>
                </tr>
                <tr>
                    <td>EXPN</td>
                    <td>This in another way to check if a message is available for messaging.</td>
                </tr>
                <tr>
                    <td>NOOP</td>
                    <td>The client requests a response to avoid a timeout.</td>
                </tr>
                <tr>
                    <td>QUIT</td>
                    <td>Client terminates the connection.</td>
                </tr>
            </table>
        </section>

        <p>At this point, once a message is sent, it will appear in the relevant mailbox, either on the local machine or transfered to another mail server to be stored there.</p>

        <section>
            <h2>Open Relay Attack</h2>
            <p>An important quick thing to check for on a server is the 'open relay' attack, to see if the server can be used by attackers to send spoof emails.</p>
            <code>$ nmap --script smtp-open-relay -p25 -v 10.0.0.3</code>
        </section>

        <section>
            <h2>Enumerating usernames from SMTP</h2>
            <p>If we can see there are different values for usernames that are and are not valid, we can use 'smtp-user-enum' to probe for usernames.</p>
            <code>$ smtp-user-enum -M VRFY -U ./footprinting-wordlist.txt -t 10.0.0.3 -m 60 -w 20</code>
        </section>
    </main>

    <!-- Including footer here -->
    <?php
    require_once(ROOT_PATH . '/resources/footer.php')
    ?>

</body>