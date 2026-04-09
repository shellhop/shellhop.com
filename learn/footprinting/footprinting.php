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
        <h1>Footprinting</h1>
        <section>
            <h2>Introduction</h2>
            <p>Footprinting services is the act of interacting with a service in order to get more information out of it. For example, we can make certain network requests to a FTP port to view potential open shares. We will split each common service into its own section, discuss default settings and common implementations of the service, the language/client used to interact with the service at an application level, and some common techniques to exploit vulnerabilites that we may find.</p>
        </section>

        <section>
            <h2>Basics of enumerating services</h2>
            <p>Enumeration is often used as a 'catch all' phrase in cyber security. It most commonly refers to the gathering of information through active techniques and direct scans on the target machine.</p>
            <p>Information is widely spread accross many sources, such as domain services, IP addresses, public services etc. It is important to know how to get the most out of each of these sources and build a comprehensive knowledge base for our target.</p>
            <p>Now that we have gathered targets using our previous techniques, it becomes important to examine the individual services running on the targets.</p>
            <p>It is important to be thorough, and calm during our enumeration. If we get too excited and jump on a brute force attack as soon as we find some authentication mechanism or login page, we may end up blacklisted making further testing impossible.</p>
            <p>The goal isn't to get into the system, but discover all the possible ways we could get to the system.</p>
            <p>We need to carefully map out our targets infrastructure before we start trying to poke holes in it.</p>
            <p>We need to try to understand our target in as many ways as we can, such as considering what we <i>cant</i> see versus what we <i>can</i> see. We must ask ourselves <i>why</i> certain things are visible and others are not.</p>
        </section>

        <section>
            <h2>An approach/methodology for enumeration</h2>
            <p>Penetration testing is a dynamic process, meaning we may revisit steps we have already completed once new information is discovered. However, we must start somewhere, and therefore having a basic approach to follow could be useful in ensuring we are thorough with our investigation.</p>
            <p>This methodology is split into 3 sections, <b>infrastructure based</b>, <b>host-based</b> and <b>OS-based</b> enumeration.</p>
            <p>We can break these sections down further, but broadly speaking we should be looking into the following layers of infrastructure from an <b>outside-in</b> view.</p>
            <table>
                <tr>
                    <th>Layer</th>
                    <th>Desc</th>
                    <th>Information Categories</th>
                </tr>
                <tr>
                    <td>1. Internet presence</td>
                    <td>What is the externally accessible infrastructure belonging to the organization?</td>
                    <td>Domains, subdomains, VHosts, ASN, netblocks, cloud instances, IP Addresses, security measures</td>
                </tr>
                <tr>
                    <td>2. Gateway</td>
                    <td>Identify what endpoints are in place to protect the organization.</td>
                    <td>Proxies, firewalls, DMZ, IPS/IDS, EDR, NAC, segmentation, VPN's, cloudflare</td>
                </tr>
                <tr>
                    <td>3. Accessible services</td>
                    <td>What individual services are hosted publically, or potentially internally.</td>
                    <td>Service type, functionality, configuration, port, version, interface</td>
                </tr>
                <tr>
                    <td>4. Processes</td>
                    <td>What processes internally manage these services, on a more 'operating system' level, as well as the source/destination for processed data.</td>
                    <td>PID, processed data, tasks, source, destination</td>
                </tr>
                <tr>
                    <td>5. Privileges</td>
                    <td>What internal permissions and privilages do these services have, or are required to interact with them.</td>
                    <td>Groups, users, permissions, restrictions, environment</td>
                </tr>
                <tr>
                    <td>6. OS Setup</td>
                    <td>Identifying internal conponents and systems setup</td>
                    <td>OS Type, patch level, network config, OS environment, configuration files, sensitive private files.</td>
                </tr>
            </table>
        </section>

        <section>
            <h2>Service level footprinting</h2>
            <p>Now that we have discovered hosts, and services on those hosts with previous techniques, we can start interacting more directly with the services.</p>
            <a href="/learn/footprinting/ftp_footprinting.php">FTP Footprinting</a>
        </section>


    </main>

    <!-- Including footer here -->
    <?php
    require_once(ROOT_PATH . '/resources/footer.php')
    ?>

</body>