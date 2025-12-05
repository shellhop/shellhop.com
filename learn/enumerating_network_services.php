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
        <h1 class="page_header">Enumeration</h1>
        <section class="introduction_section">
            <h2 class="">What is enumeration?</h2>
            <p>Enumeration can be a very vague term, and can refer to a very large number of activities in ethical hacking. To simplify, we will define enumeration as the "compilation of information about an organization or group". An 'organization' in this instance is simply a collection of digital/physical resources that make up a related set of infrastructure. Enumeration of physical resources like routers, machines, servers etc. as well as enumeration of digital resources like domain namespace. An organization could be 'shellhop.com' for instance, despite the fact that it is not considered a traded organization under the law. If we were to 'enumerate' shellhop.com as an organization, we would compile information on what domains and subdomains fall under shellhop.com, what physical servers does shellhop.com employ, what services shellhop provides and to whom.</p>
            <h2>Methodolgy</h2>
            <p>We will split our enumeration into two defined steps, however, much of eumeration is cyclical in nature, where newly discovered information may allow us to backstep and repeat a previous step with added context, allowing us to discover infrastructure or information we would not have discovered if we had treat enumeration with a more concrete 'waterfall' type process. Information on it's own is often not particularly valuable, however through compilation of lots of information we can draw relationships between devices and systems, discover hidden resources through inference and context clues, as well as increase the overall 'power' of previously mundane information with each new discovery. Take the following example: We discover a set of credentials, username: 'shelly', password: 'sh3llh0p'. This on it's own is not helpful information. Then let's say we discover a hidden administrator login page for a web application. Now all of a sudden this information becomes extremely valuable to us, and while each piece of information on its own is not particularly useful, it is the combination of information that provides us with extreme value.</p>
            <h3>Discovery</h3>
            <p>Discovery is the first step in our enumeration; this is where we discover the 'what' and 'where' of our target. The goal here is to throw our digital nets as widely as possible before slowly closing in on more intensive and precise techniques. We want as many 'quick wins' as possible so that we can build our information database as quickly as possible. With this approach, we can minimise wasted time, which of course will maximise our value to our client. We are not in this instance looking to learn the ins and outs of every discovered service, or the specifics of technologies, (we will use more targetted techniques for this later on), but instead trying to find the nature of services that face the internet that belong to our organization. For example, what subdomains are linked to the root domain? What services do the organization provide to their clients? Does the company have a website? Is there a remote access VPN that employees can use to access the network externally? What mail servers and name servers are the company using etc.?</p>
            <h3>Enumeration</h3>
            <p>Enumeration is a subset of reconnaissance techniques that focusses on more active information gathering of targets. These steps are often muddied, and you will find numerous less-than helpful definitions depending on where you look, but in essence, this step encompases diving deeper into the services, systems and machines that we found in the previous step in order to gather more 'systems-level' information about our organization. During the 'discovery' step, we may find 'www.shellhop.com', whereas during enumeration/fingerprinting/this current step, we would look to see what technologies are being used on the website, what server it is using, (apache2 in this case), what version of apache is running, (hopefully an up to date one for my sake), and what other languages/technolgies are being employed.</p>

        </section>

        <section>
            <h1>Discovery, tools and techniques</h1>
            <h2>Building an information base</h2>
            <p>It is a good idea to have an organized structure before beginning our assessment. A spreadsheet made up of IP addresses, associated domain names and services is a good start. An example table may look like the following:</p>
            <table>
                <tr>
                    <th>IP</th>
                    <th>Domain Name</th>
                    <th>Description</th>
                    <th>Services</th>
                </tr>
                <tr>
                    <td>10.10.10.10</td>
                    <td>real.domain</td>
                    <td>website of domain name</td>
                    <td>443: Apache web server<br>22: SSH</td>
                </tr>
            </table>

            <p>The above table can be added to over time, eventually filling out to a rather comprehensive information repository. More details can be added to each service in the services section once things like service versions are discovered, and other tables like VHOSTS may be added when appropriate.</p>
            <h2>Discovering Hosts</h2>
            <p>Okay, enough preamble. Let's take a look at how we can start building up a repository of hosts belonging to an organization.</p>
        </section>
    </main>

    <footer class="main_footer">
        <div class="footer_left">
            <a href="https://www.linkedin.com/in/louis-holmes-534a98390/" target="_blank">LinkedIn</a><a href="https://github.com/shellhop" target="_blank">GitHub</a><a href="https://doi.org/10.3390/jcp4030021" target="_blank">Publication</a>
        </div>
    </footer>
</body>