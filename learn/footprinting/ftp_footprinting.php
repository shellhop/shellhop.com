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
            <p>We usually need some credentials to interact with FTP. This is a good time to mention FTP is a <b>plain text</b> protocol, meaning credentials and data can be sniffed over the network if traditional FTP is being used.</p>
            <p>Despite the need for authentication, FTP also supports <b>anonymous login</b>, which means we can log into an FTP server via an anonymous user, e.g. without credentials.</p>

        </section>

        <section>
            <h2>TFTP</h2>
            <p>TFTP or Trivial File Transfer Protocol is another implementation of an FTP-like protocol. It is however, far simpler than FTP is.</p>
            <p>TFTP differs from FTP in the following ways:</p>
            <ul>
                <li>TFTP does not support authentication</li>
                <li>TFTP uses UDP as opposed to TCP making it <i>unreliable</i></li>
                <li>TFTP can only be used in <i>local</i> and <i>protected</i> networks</li>
            </ul>

            <p>Here are some of the most common and useful FTP commands:</p>
            <table>
                <tr>
                    <th>Command</th>
                    <th>Desc</th>
                </tr>
                <tr>
                    <td>Connect</td>
                    <td>Sets the remote host and port for file transfers</td>
                </tr>
                <tr>
                    <td>get</td>
                    <td>Transfers files from the server to the client</td>
                </tr>
                <tr>
                    <td>put</td>
                    <td>Transfers files from the client to the server</td>
                </tr>
                <tr>
                    <td>quit</td>
                    <td>exits TFTP</td>
                </tr>
                <tr>
                    <td>status</td>
                    <td>Shows the current status for TFTP connections, such as data transfer mode and time-out value</td>
                </tr>
                <tr>
                    <td>verbose</td>
                    <td>Turns on verbose mode, showing more information for debugging</td>
                </tr>
            </table>

            <p>One other important note: <b>TFTP does NOT support file listing</b></p>
        </section>

        <section>
            <h2>VSFTPD Configuration</h2>
            <p>Let's take a look at the default configuration for an example FTP server. The installation we will be using is 'vsftpd', which is the most common FTP server available for linux. For this example, vsftpd has been installed on a debian server within our lab network.</p>
            <p>vsftpd was installed and enabled on the target machine. Try using nmap to see if the port is open from another vm. In this case, port 21 is open.</p>
            <p>let's take a look at the configuration file in '/etc/vsftpd.conf'. There are some important settings in this file that we should take a look at.</p>
            <table>
                <tr>
                    <th>Setting</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>listen=no</td>
                    <td>run from inetd or as a standalone daemon?</td>
                </tr>
                <tr>
                    <td>listen_ipv6=YES</td>
                    <td>listen on ipv6?</td>
                </tr>
                <tr>
                    <td>anonymous_enable</td>
                    <td>Should anonymous users be enabled?</td>
                </tr>
                <tr>
                    <td>dirmessage_enable=YES</td>
                    <td>Display active directory messages when users go into certain directories?</td>
                </tr>
                <tr>
                    <td>use_localtime=YES</td>
                    <td>use localtime?</td>
                </tr>
                <tr>
                    <td>xferlog_enable=YES</td>
                    <td>Should data transfers be logged?</td>
                </tr>
                <tr>
                    <td>connect_from_port_20=YES</td>
                    <td>Should the server connect from port 20?</td>
                </tr>
                <tr>
                    <td>secure_chroot_dir=/var/run/vsftpd/empty</td>
                    <td>Name of an empty directory</td>
                </tr>
                <tr>
                    <td>pam_service_name=vsftpd</td>
                    <td>Name of the PAM service vsftpd will use</td>
                </tr>
                <tr>
                    <td>rsa_cert_file=/etc/ssl/certs/ssl-cert.pem</td>
                    <td>Name of the RSA cert file to use for encryted connections</td>
                </tr>
                <tr>
                    <td>rsa_private_key_file=/etc/ssl/private/ssl-cert.key</td>
                    <td>Name of the private key for ssl connections</td>
                </tr>
                <tr>
                    <td>ssl_enable=NO</td>
                    <td>Activate ssl connections?</td>
                </tr>
            </table>

            <p>There is one more important file, the '/etc/ftpusers' file. Which, counter-intuitively, acts as a blacklist for certain users. Users in this file are not permitted to use FTP, even if they are present locally on the system.</p>
        </section>

        <section>
            <h2>Dangerous settings</h2>
            <p>There are some dangerous optional settings that can be configured in vsftpd to watch out for.</p>
            <table>
                <tr>
                    <th>Setting</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>anonymous_enable=YES</td>
                    <td>Allow anonymous login?</td>
                </tr>
                <tr>
                    <td>anon_upload_enable=YES</td>
                    <td>Allow anonymous users to upload files?</td>
                </tr>
                <tr>
                    <td>anon_mkdir_write_enable=YES</td>
                    <td>Allow anonymous users to create directories?</td>
                </tr>
                <tr>
                    <td>anon_root=/home/username/ftp</td>
                    <td>Sets the home directory for anonymous users</td>
                </tr>
                <tr>
                    <td>write_enable=YES</td>
                    <td>Allows the usage of certain critical FTP commands</td>
                </tr>
            </table>
        </section>

        <section>
            <h2>Interacting with FTP as a client</h2>
            <p>For this example, anonymous login has been enabled in the config file for our FTP service.</p>
            <p>We can connect to the server with:</p>
            <code>$ ftp 10.0.0.3</code>
            <p>Once in, use the 'status' command to take a look at what we have going on.</p>
            <p>We can use the 'get &gt;filename&lt;' command to pull files, and replace 'get' with 'put' to upload a file.</p>
            <p>We can pull <i>all</i> available files from the ftp server with a 'wget' command.</p>
            <code>$ wget -m --no-passive ftp://anonymous:anonymous@&gt;target&lt;</code>
            <p>We can also use nmap to interact with our FTP server, activating aggressive scan mode as well as scripts will take a lot of the manual work out of assessing the service, however it may become necessary to use other methods to interact with the service.</p>
            <p>We can directly connect to the port with netcat, doing so in this manner results in a banner revealing the software version of the server.</p>
            <p>Should the FTP server use SSL, we can use the ssl client tool to interact with it.</p>
            <code>$ openssl s_client -connect 10.0.0.3:21 -startttls ftp</code>
        </section>
    </main>

    <!-- Including footer here -->
    <?php
    require_once(ROOT_PATH . '/resources/footer.php')
    ?>

</body>