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
            <h3>Certificate Logs</h3>
            <p>The internet is build up of millions of resources owned by an equally massive number of organizations. As a result of the sensitive nature of our interactions with these resources, (transferring passwords when logging into online accounts etc.), there was a clear need to secure and encrypt these interactions. The method we eventually settled on for the encrpytion of online web-based communication was SSL. SSL is considered a layer 6 protocol, as it encrypts the application data of a web request. When we make a web request, most commonly a GET request when requesting a web page, or a POST request when sending user-generated data such as credentials to a web server, the actual data itself is encrypted with a complex, one way algorithm. (there is more to it, technically only the initial request and key exchange is asymettric, but for the purpose of this lesson this isn't important).<br>So great! We have our encrypted connection, there is now no way that someone listening in on our communication could read/modify our data if they managed to intercept it. However, how can we also be sure that the website we are actually sending our credentials to <i>is</i> the legitimate website of our intended recipient. <br>For example, let's say you send your critical information to 'shellhop.com', how do you know that someone sneaky on your network has created a false 'shellhop.com' that you are being redirected to, and even though our communication is encrypted, the attacker is at the other end anyway, and so they will recieve our data regardless with no need to decrypyt. <br>To solve this issue, extremely clever mathematicians and cryptographic experts created the concept of 'SSL Certificates'. Using complex mathematics, someone with the private decryption key within a public/private key pairing can <i>prove</i> that they are indeed in possession of said key without sharing it. This allows a third party to validate ownership of an SSL certificate, if they too are in possession of said key.<br>In the modern day, SSL certificates for public websites will obtain their private/public key, not through generating these themselves, but from a third party key authority. These authorities keep records of issued SSL certificates, and more importantly, which <i>domain</i> these keys have been assigned to. More importantly, due to the purpose of these records, they must be <i>publically accessible</i>.<br>This very brief explanation of SSL certificates culminates to these key points from an attackers perspective:</p>
            <ul>
                <li>All domains have a publically accessible record that contains key information about the SSL certificate.</li>
                <li>We can reverse lookup a particular SSL certificate to see what <i>other</i> domains it may be linked to.</li>
            </ul>
            <p>By examining certificate logs for a particular domain, it may be possible to discover other domains, or typically subdomains of a particular organization.</p>
            <p>Lets look at how we would go about this ourselves.</p>
            <p>We can start by looking at <a href='https://crt.sh' target='_blank'>crt.sh</a>. This is a website that contains SSL certificate records. We can take a look at any record we like here. Be aware that in some states/regions these activities may be considered illegal, even if our intent is that of education and the information is public. You are free to perform any passive intelligence gathering technique you like to any system that falls under the shellhop.com domain, so although I don't <i>have</i> an ssl certificate for this site (yet), there may still be some interesting information in my certificate logs. <br>Give it a try now! Simply type 'shellhop.com' into crt.sh and see what results we can find.</p>
            <img src='/images/learn/enumeration/shellhop_crt_results.png' alt='<results of a crt.sh query for shellhop.com>'></img>
            <p>As we can see, there are several results for "Let's encrypt" as well as several other issuers. This is because although no <i>active</i> SSL key pair exists, all <i>previous</i> certificates to this domain are visible. This is <i>extremely</i> valuable, as we can use these logs to discover subdomains that may no longer be publically accessible. There may exist leftover domains that were forgotten about and are still accessible, and the logs never cleared up. There may also exist clues as to possible other subdomains that exist; in our shellhop example, we can see clearly that 'www.shellhop.com' is a subdomain we have previously had a certificate issued for, despite the fact that we began the engagement only with 'shellhop.com', the root domain, as part of our knowledge base.</p>
            <p>We can also increase our productivity in this area with a simple script. 'crt.sh' actually supplies an API that let's us make calls and recieve JSON data in return, this way we can quickly scan the certificate logs for domains, strip out just the domains themselves, and then pull only the unique domains to end up with a very convenient list.</p>
        </section>

    </main>

    <!-- Including footer here -->
    <?php
    require_once(ROOT_PATH . '/resources/footer.php')
    ?>

</body>