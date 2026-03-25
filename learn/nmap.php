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
        <h1 class="page_header">NMAP Enumeration</h1>
        <section class="introduction_section">
            <h2>What is NMAP?</h2>
            <p>Nmap is the most popular, and comprehensive network port scanner available. It is on of the most staple and essential tools in infosec, and so a high level of proficiency with the tool is nearly essential in this field.</p>
            <p>Nmap is a port scanner. It can detect the state of ports on a machine, which ports are open, what services those ports are running, what versions etc. There is a vast range of scan types that are more or less invasive, resulting in more or less reliable information. We have fine-tunable settings to set our balance of detail, speed, anonymity, and nature of results.</p>
            <p>Learning how to set our scans up is essential, as depending on different environments, we may be required to vastly modify our approach to not crash our targets, avoid detection, or extract more precise information from our target.</p>
        </section>

        <section>
            <h2>Basics</h2>
            <p>Let's get started with some of the most basic syntax of nmap.</p>
            <p>We start our scans by initializing our tool and selecting a target.</p>
            <code>$ nmap &lt;target&gt;</code>
            <p>For the following examples, we will use the network range 10.0.0.1-10/24, so our final command for scanning the first target in that range will be:</p>
            <code>$ nmap 10.0.0.1</code>
            <p>We can also scan an entire network range with common cidr notation.:</p>
            <code>$ nmap 10.0.0.0/24</code>
            <p>We can specify a port we wish to scan with the '-p' flag.</p>
            <code>$ nmap -p 22 10.0.0.1</code>
            <p>We can output our results with the '-o' flag, and although there are different output formats, it </p>
            <p>If we want to see <i>why</i> nmap has come to a conclusion about a port or host with the '--reason' flag.</p>
        </section>

        <section>
            <h2>Host discovery</h2>
            <p>The first step in conducting your scan-based enumeration will often be host discovery. By default, nmap will scan a number of ports on each device in the network range you specify, however we can disable the port scanning entirely and simply try to verify the existence of a device with '-sn'.</p>
            <code>$ nmap -sn 10.0.0.0/24</code>
            <p>We can scan a list of ip addresses or ranges with the '-iL flag'</p>
            <code>$ nmap -sn -iL &lt;path to list file&gt;</code>
            <p>We can also scan a number of passed targets as follow:</p>
            <code>$ nmap -sn 10.0.0.1 10.0.0.2 10.0.0.3</code>
            <p>We can also scan a range of adjacent IP addresses as follows:</p>
            <code>$ nmap -sn 10.0.0.1-10</code>
            <p>These above scans have been sending an <b>ARP</b> request and recieving an <b>arp</b> reply, however another more common method of host detection is through ICMP echo request and reply messages.</p>
            <p>If we wish to gaurantee an icmp message is sent, we can use the '-PE' flag, and to confirm the tool is behaving as expected, we can add a useful '--packet-trace' option to see the raw packets being sent and recieved.</p>
            <code>$ nmap -PE --packet-trace 10.0.0.2</code>
            <p>We can also disable the arp ping entireley, with the '--disable-arp-ping' flag.</p>
        </section>



        <section>
            <h2>Port Scanning</h2>
            <p>Nmap will present several 'states' that ports can fall into when scanning a host. For now, let's just scan a port we know is open on a host that is up.</p>
            <code>$ nmap -p 22 10.0.0.3</code>
            <p>The output will read 'STATE: OPEN' in some way. This means we sent a 'SYN' packet to the machine, and the machine replied with a 'SYN ACK' message, and using the '--reason' flag confirms this.</p>
            <p>THere are several states that may show up when a port is scanned.</p>
            <table>
                <tr>
                <th><b>State</b></th>
                <th>Desc</th>
                </tr>
                <tr>
                    <td>Open</td>
                    <td>This means there has been a connection made by the port. UDP, TCP or other method of transmision control.</td>
                </tr>
                <tr>
                    <td>Closed</td>
                    <td>The machine sent a 'RST' response to the scanning machine.</td>
                </tr>
                <tr>
                    <td>Filtered</td>
                    <td>Nmap is unable to confirm the status of a port for some reason or another. Either there was no response, or we recieve some kind of error message.</td>
                </tr>
                <tr>
                    <td>Unfiltered</td>
                    <td>This state only appears when conducting an 'ACK' scan, and means the port is accessible but Nmap is unsure wether the port is open or closed.</td>
                </tr>
                <tr>
                    <td>Open/Filtered</td>
                    <td>If no response is recieved, we may get a 'open/filtered' result.</td>
                </tr>
                <tr>
                    <td>Closed/Filtered</td>
                    <td>This state occurs during IP ID Idle scans and indicates it was impossible to determine the nature of the port.</td>
                </tr>
            </table>

            <p>We can scan the top most common ports with the '--top-ports' flag.</p>
            <code>$ nmap --top-ports=10 10.0.0.3</code>
        </section>

        <section>
            <h2>Types of Scans</h2>
            <p>There are several useful scan types, the first of which is '-sS' or 'Syn Scan', where the machine only sends a SYN packet to the device and awaits a 'SYN ACK' packet, and then does not complete the three way handshake. This way, we can potentially reduce logs on the target system from detecting us, and increase the speed of our scans.</p>
            <p>There is '-sT' which is a full TCP handshake, where we send a final 'ACK' packet back to the server.</p>
            <p>There is '-sU' which conducts a scan of UDP ports, not TCP ports.</p>
        </section>

        <section>
            <h2>Dealing with filtered ports</h2>
            <p>Many times, a port will be viewed as 'filtered', however this does not mean we are without hope in learning more about the nature of the port.</p>
            <p>We can look at the timestamps for certain packets, packets that are 'dropped' by a firewall will result in a much larger time difference between our packets being sent, than if a packet is being rejected. We may see in our packet logs certain error codes, or a far faster retransmission of our own packet that could indicate packets are being rejected as opposed to dropped. This may warrant farther investigation later.</p>
        </section>

        <section>
            <h2>Service detection</h2>
            <p>Another helpful scan type is '-sV'. With this flag, Nmap will attempt to discern the service instance and version running on a port. It may be able to detect the type and version of a web server running on our target host, or what version of FTP is being employed.</p>
            <p>Despite the above being extremely useful, Nmap is sometimes presented with information it doesn't fully understand. This can occur when banners are configured or modified on the target machine. If we use TCPDump and read through packets manually we are likely to find information that nmap did not interpret, and make further inferences based on this.</p>
        </section>

        <section>
            <h2>NMAP Output formats</h2>
            <p>We can output the results of our scans in many formats, for example xml, or html, or a greppable format useful for further disection.</p>
            <p>We use flags to decide which version of nmap we want to present.</p>
            <p>'-oG' is greppable output, '-oN' is the normal output with a '.nmap' extension, '-oX' is xml output, and '-oA' is all of the above.</p>
            <p>We can take our xml output and convert it to HTML with a simple tool 'xsltproc'</p>
            <code>$ xsltproc target.xml -o target.html</code>
            <p>And then view our new file with a web browser or HTML doc reader.</p>
            
        </section>


    </main>

    <!-- Including footer here -->
    <?php
    require_once(ROOT_PATH . '/resources/footer.php')
    ?>

</body>