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

        <section>
            <h2>Nmap Scripting Engine</h2>
            <p>Nmap also consists of an extremely advanced scripting engine. Many scripts have been built into Nmap for various purposes, however they are split into 14 categories.</p>
            <table>
                <tr>
                    <th>Category</th>
                    <th>Desc</th>
                </tr>
                <tr>
                    <td>auth</td>
                    <td>Authentication credential discovery or determination</td>
                </tr>
                <tr>
                    <td>broadcast</td>
                    <td>Primarily for host discovery with broadcast techniques</td>
                </tr>
                <tr>
                    <td>brute</td>
                    <td>Brute force attack based scripts</td>
                </tr>
                <tr>
                    <td>default</td>
                    <td>Default scripts that run when the '-sC' flag is used</td>
                </tr>
                <tr>
                    <td>dos</td>
                    <td>Denial of service based attack scripts</td>
                </tr>
                <tr>
                    <td>exploit</td>
                    <td>Exploit scripts that can be used to exploit known vulnerabilities</td>
                </tr>
                <tr>
                    <td>external</td>
                    <td>Scripts that use external resources for further processing</td>
                </tr>
                <tr>
                    <td>fuzzer</td>
                    <td>Scripts that are used to fuzz for existing vulnerabilities and packet handling issues by sending different data</td>
                </tr>
                <tr>
                    <td>intrusive</td>
                    <td>Intrusive scripts that could negatively impact the target machine</td>
                </tr>
                <tr>
                    <td>safe</td>
                    <td>Scripts that do not perform invasive or potentially harmful actions.</td>
                </tr>
                <tr>
                    <td>version</td>
                    <td>Version detection scripts</td>
                </tr>
                <tr>
                    <td>malware</td>
                    <td>Scripts that detect if any malware effects the system.</td>
                </tr>
                <tr>
                    <td>vuln</td>
                    <td>Vulnerability scanners</td>
                </tr>
            </table>
            <p>We can define which categories of scripts to use with different flags.</p>
            <p>To perform a basic script scan with the default parameters we can use the '-sC' flag.</p>
            <code>$ sudo nmap -sC 10.0.0.2</code>
            <p>We can select a particular category with the following:</p>
            <code>$ sudo nmap --script &lt;category&gt;</code>
            <p>We can define specific scripts to run by providing the path to certain nmap script files:</p>
            <code>$ sudo nmap 10.0.0.2 --script &lt;script name&gt;,&lt;script 2 name&gt;</code>
            <p>The '-A' flag means 'aggressive', where nmap will scan with several default options such as scripts, OS detection, service detection and others.</p>
        </section>

        <section>
            <h2>Performance settings</h2>
            <p>Nmap scans can be extremely slow. There are however, many options we have for tinkering in order to achieve faster or more efficient results.</p>
            <h3>Timeouts</h3>
            <p>A round trip, or our round trip time, can be adjusted in our nmap command. By default, our round trip timeout is set to 100ms. We can set our own RTT with the '--max-rtt-timeout' and '--initial-rtt-timeout' flags.</p>
            <code>$ sudo nmap 10.0.0.2 --initial-rtt-timeout 50ms --max-rtt-timeout 100ms</code>
            <p>This will drastically increase our speed, however may cause us to overlook certain hosts or services with a slower response time.</p>
            <h3>Retries</h3>
            <p>We can set our max retries from the default of 10, to whatever number we like with '--max-retries'. We can even set this to zero. This will prevent nmap from retransmitting packets if it did not recieve a response, which will decrease reliability in return for speed and creating less network traffic.</p>
            <code>$ sudo nmap 10.0.0.2 --max-retries 0</code>
            <h3>Rate</h3>
            <p>If we are working with a known network bandwidth, we can set nmap to send packets simultaneously accross the network according to that rate.</p>
            <code>$ sudo nmap 10.0.0.2 --min-rate 300</code>
            <h3>Timing presets</h3>
            <p>Nmap comes with 6 total timing presets. We can use '-T[1-5]', with 5 being the fastest and most aggressive, and 0 being extremely slow. We must evaluate the purpose of our scan and our environment before we decide how to customise our parameters and achieve the best results.</p>
        </section>

        <section>
            <h2>Firewall and IDS Evasion</h2>
            <p>This is an extremely important section, as evading firewalls and avoiding the creation of logs from which we can be detected is paramount in any serious penetration test.</p>
            <p>Luckily, Nmap has many useful features that we can use to avoid detection in complex environments.</p>
            <h3>Detecting the firewall</h3>
            <p>Before we begin our evasion tactics, we need to determine what the nature of the firewall is. Packets often show up as 'filtered', which means we did not get a response from our target port. This can be that our packets are 'dropped' and ignored, or outright rejected. The traffic looks slightly different for both. If our packet is dropped, we will see nothing in the logs, and our machine will wait its retransmission period before trying again. If our packet is rejected, we may see certain error logs like 'net unreachable' or 'host prohibited' returned. This stresses the importance of traffic analysis alongside our basic port scanning, as these small differences can make or break our enumeration.</p>
            <h3>TCP Ack Scan</h3>
            <p>The Nmap '-sA' or 'ACK' scan will send an acknowledgement packet to the target. This is extremely difficult to defend against when compared to our other scan options. Firewalls often prevent the initiation of connections by looking for the three way handshake, however, if we make it look like our machine already has a connection with the target via a 'ACK' packet, and the firewall cannot determine the original origin of our 'connection'. This often leads to our packet slipping by any firewall and reaching our target, triggering some kind of response. The response we are likely to recieve if the port is open, is RST, and the response will not exist if our packets are being dropped.</p>
            <p>It is important to, once we are past the main firewall, analyse our data to look for any IDS or IPS in place. We can scan from a single IP and look for when we are blocked. If we lose our access through a certain IP address, we can then continue our test from another VPS, knowing we are dealing with some kind of IDS/IPS.</p>
            <h3>Decoy IP's</h3>
            <p>We can use decoys to reduce the chances of our IP address being blocked, by randomly sending packets from a number of IP addresses. This way, an IDS may have trouble blocking the correct machine. It is important that all devices are alive.</p>
            <code>$ sudo nmap -D RND:5</code>
            <h3>Source IP and port manipulation</h3>
            <p>Administrators often use a firewall whitelist to allow certain important traffic into the network. If a company expects DNS requests to be sent via TCP port 53, we can spoof our source port to 53 and potentially bypass certain firewall rules.</p>
            <code>$ sudo nmap -sS -Pn -n --disable-arp-ping --source-port 53 10.0.0.2</code>
            <p>If we are able to bypass certain firewall rules with a source port of 53, we may be able to manually connect to certain ports with ncat.</p>
            <code>$ nc -nv --source-port 53 10.0.0.2 50000</code>
            

        </section>

    </main>

    <!-- Including footer here -->
    <?php
    require_once(ROOT_PATH . '/resources/footer.php')
    ?>

</body>