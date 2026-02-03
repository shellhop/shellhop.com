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

                <h1>Discovery, tools and techniques</h1>
        <section>
            <h3>Building an information base</h2>
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
            <p>Okay, enough preamble. Let's take a look at how we can start building up a repository of hosts belonging to an organization.</p>
        </section>

        <section>
            <h3>WHOIS</h3>
            <h4>What is WHOIS?</h4>
            <p>Across the internet, there are several databases that contain metadata about internet resources, mainly focusing on domain names. WHOIS is a protocol designed to interact with these databases to retrieve said information.</p>
            <p>We can make a simple WHOIS request from with the 'whois' tool.</p>
            <code>$ whois shellhop.com</code>
            <p>Output:</p>
            <code>Domain Name: SHELLHOP.COM ...</code>
            <p>As we can see, there is lots of valuable information stored here, however some of it is redacted. Some of the most valuable information we can find with WHOIS is contact details. We can find email addresses, administrator details, phone numbers etc. for technical staff. Pay key attention to the email addresses, as they may reveal subdomains such as 'dev.domain.com' or 'admin.domain.com'. Another useful piece of information is the name server section, as this can reveal more about a target's infrastructure, such as if they manage their own dns infrastructure.</p>
        </section>

        <section>

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
            <p>Let's grab a simple api request from crt.sh, we can use the url "https://crt.sh/?q=&ltdomain&gt.com&output=json" with a simple curl command to get a result. Let's try this with 'shellhop.com'.</p>
            <code>$ curl -s https://crt.sh/?q=shellhop.com&output=json</code><br>
            <code>["issuer_ca_id":295813,"issuer_name":"C=US, O=Let's Encrypt, CN=E7","common_name":"shellhop.com","name_value":"*.shellhop.com\nshellhop.com","id":21975830168,"entry_timestamp":"2025-10-24T18:07:24.655","not_before":"2025-10-24T17:08:54","not_after":"2026-01-22T17:08:53","serial_number":"061989340abe79d ...</code><br>
            <p>That's quite overwhelming! But don't fear, as we can pipe this into a simple JSON parser like 'jq' for linux to clean things up.</p>
            <code>$ curl -s "https://crt.sh/?q=shellhop.com&output=json" | jq -r</code>
            <p>Although this is significantly more readable, we would prefer if we only displayed the information we personally want, in this case the 'name_value' data.</p>
            <code>$ curl -s "https://crt.sh/?q=shellhop.com&output=json" | jq -r '.[] | select(.name_value)' | sort -u</code>
            <p>output:</p>
            <code>*.shellhop.com<br>shellhop.com<br>www.shellhop.com</code>
            <p>There we have it, a list of domains derived from certificate logs. In real engagements, simply running this line of code is an extremely efficient way of passively aquiring a number of domains, and although shellhop is a small domain, in larger organizations this may yield extremely valuable results. This is only one way to find domains however, there are several more active methods that may yield more information.</p>
        </section>

        <section>
            <h3>DNS Record digging</h3>
            <p>DNS, simply put, is the system that translates domain names into ip addresses. When we want to look for a particular resource, instead of typing the IP address directly, we can type the domain name and our computer will convert this into a real IP address through a variety of steps. It may check it's own internal hosts file in the case of Linux and Windows, or interact with the internet-wide DNS service.</p>
            <p>DNS servers are the physical machines that map the records that contain IP information. These servers are what our browser or our operating system will try to reach out to when we query for a resource via its domain name.</p>
            <p>Domain names are the easily recognisable sequence of phrases seperated by a dot when we type in our browser. Google.com for example is a domain name, www.shellhop.com is also a domain name. Domains are seperated into several sections; you have the top level domain which is one of several, (.com, .co.uk, .gg, .ca etc.) and our root domain name which we purchase from a DNS provider such as cloudflare or google. In the case of 'www.shellhop.com', our top level domain is '.com', and our root domain is 'shellhop'.<br>If our root domain is 'shellhop' however, what is 'www.shellhop.com'. The 'www' in our domain is a <b>subdomain</b>. We are free to create as many subdomains of a root domain as we like, and can name these arbitrarily as we see fit, although there are several conventions that we can use later to predictively discover subdomains of a root.</p>
            <p>Some clarifications should be made here. A TLD or a Top Level Domain does have to conform to the conventions of .com, .co.uk etc. only in the case of publically accessible resources. This is simply because there are several 'maintainers' of the dns servers that host massive amounts of records, and each of the recognized top level domains are assigned to each of these maintainers. However, if we create our own environment with our own DNS server, we are free to use whatever top level domain we like. 'shellhop.lab' for instance is the name of my internal ethical hacking labratory environment, and all dns resolution works perfectly in that instance within the contained environment.</p>
            <p>That being said, when we begin an engagement, we are typically given a root domain, or several subdomains as part of our scope, meaning it becomes our job to discover all the possible devices and resources that fall under that domain. Typically, subdomains are used to logically break an organizations resources, for example 'dev.shellhop.com' would be the root domain for the development team and all associated resources, like 'dev1.dev.shellhop.com'.</p>
            <p>On a dns server, there will be records that map the 'domain name' of a device to it's IP address. This means DNS becomes one of the most valuable resources when it comes to host discovery, as we can ask the dns server of an organization about it's subdomains and recieve the results in the form of IP addresses.</p>
            <h4>Record types</h4>
            <p>There are several 'record types' that a DNS server supoprts, the following is a list of the most important ones for our purposes.</p>
            <table>
                <tr>
                    <th>Record type</th>
                    <th>Long name</th>
                    <th>Description</th>
                    <th>Example</th>
                </tr>
                <tr>
                    <td>A</td>
                    <td>Adress record</td>
                    <td>Maps a hostname to an IPv4 address</td>
                    <td>www.shellhop.com IN A 209.38.171.191</td>
                </tr>
                <tr>
                    <td>AAAA</td>
                    <td>IPv6 Record</td>
                    <td>Maps a hostname to an IPv6 address</td>
                    <td>www.shellhop.com IN AAAA xxxx:xxxx:xxxx:xxxx</td>
                </tr>
                <tr>
                    <td>SOA</td>
                    <td>Start Of Authority</td>
                    <td>Contains metadata about a domain, primary name server, responsible persons, etc.</td>
                    <td>shellhop.com. IN SOA adelaide.ns.cloudflare.com. dns.cloudflare.com. 2388953581 10000 2400 60</td>
                </tr>
                <tr>
                    <td>NS</td>
                    <td>Name Server</td>
                    <td>Specifies an authoritative name server</td>
                    <td>shellhop.com IN NS adelaide.ns.cloudflare.com.</td>
                </tr>
                <tr>
                    <td>CNAME</td>
                    <td>Canonical Name Recort</td>
                    <td>Creates an alias for a record</td>
                    <td>shellhop.com IN CNAME www.shellhop.com</td>
                </tr>
                <tr>
                    <td>MX</td>
                    <td>Mail Record</td>
                    <td>Contains the address for the mail record associated with this domain</td>
                    <td>shellhop.com. IN MX alt1.aspmx.l.google.com.</td>
                </tr>
                <tr>
                    <td>TXT</td>
                    <td>Text Record</td>
                    <td>Contains miscellanious information, often used for domain ownership verification or security.</td>
                    <td>shellhop.com . IN TXT 'v=spf mx -all'</td>
                </tr>
                <tr>
                    <td>SRV</td>
                    <td>Service</td>
                    <td>Defines the host and port for a service</td>
                    <td>_sip._tcp.shellhop.com. 86400 IN SRV 0 5 5060 server.shellhop.com.</td>
                </tr>
                <tr>
                    <td>PTR</td>
                    <td>Pointer</td>
                    <td>Reverse lookup record, mapps instead an IP address to a hostname</td>
                    <td>192.168.1.50 IN PTR www.shellhop.lab</td>
                </tr>
            </table>

            <h4>Querying DNS Logs</h4>
            <p>We can use a tool called 'dig' to query DNS servers in a similar way to our browsers. However, we are given far more control with dig, and recieve far more valuable outputs.</p>
            <p>We can call 'dig' and provide a record type, as well as a hostname or domain.</p>
            <code>$ dig A www.shellhop.com</code>
            <p>output:</p>
            <code>; <<>> DiG 9.18.39-0ubuntu0.24.04.2-Ubuntu <<>> A shellhop.com...;; ANSWER SECTION:<br>shellhop.com.           207     IN      A       209.38.171.191<br></code>
            <p>This is a lot of output; we can see debugging information, message size, query response codes and more. This is not all very useful to us, so to shorten our output to something more manageable we can use the '+noall +answer' flag combo. '+noall' completely removes all output, and the '+answer' puts the answer section back in.</p>
            <code>$ dig A www.shellhop.com +noall +answer</code>
            <p>output:</p>
            <code>shellhop.com.           300     IN      A       209.38.171.191</code>
            <p>We can further shorten our output with the '+short' flag on it's own, that will only show us the <i>value</i> of our returned answer.</p>
            <code>$ dig A www.shellhop.com +short</code>
            <p>output:</p>
            <code>209.38.171.191</code>

            <p>We can use dig to test for domains that may exist, however without a few more skills under our belt, this alone will not be very useful.</p>
        </section>

        <section>
            <h2>Targetting DNS for host discovery</h3>
            <p>In the previous techniques, we have looked at <i>passive</i> or at the very least none-invasive enumeration of hosts. This means we have, up till now, avoided any direct interaction with the target machines. In the instances where we have interacted directly with the target hosts, we have avoided interacting in a way that is unexpected or unusual for those services. A DNS database exists specifically to respond to DNS queries, meaning when we have made simple DNS queries with dig for known sites we are simply interacting correctly with the service.</p>
            <p>This next section will cover techniques that are considered more <i>active</i>, meaning we will be interacting directly with the target machines and attempt to manipulate the systems into interacting with us in a way that is <i>not</i> expected. These techniques are easier to detect, and can be recognized as malicious resulting in us being blacklisted from the target. That being said, let's begin.</p>
            <h3>Subdomain bruteforcing</h3>
            <p>A DNS server will likely only respond with a record matching a domain if the domain itself is recognized, meaning if we can make educated guesses about possible existing subdomains, we can leverage a DNS server to confirm or deny the existence of our guess.</p>
            <p>This approach is called subdomain bruteforcing, or a dictionary attack. We can use a pre-compiled wordlist of popular subdomains, or create our own, and have a DNS server verify if a record matching the guessed domain exists. We automate this process with usually in the form of a script or pre-built tools. One such tool is gobuster, which is a versatile tool for a number of purposes.</p>
            <code>$ gobuster dns -w &ltpath to wordlist&gt -d &ltdomain&gt</code>
            <p>These flags are the basic minumum syntax for a dns bruteforce attempt with gobuster. We can add additional flags such as '-o &ltoutput file&gt' and '-r &ltname server&gt' for more control.</p>
            <p>Let's try a simple brute force against shellhop.</p>
            <code>$ gobuster dns -w ../SecLists/Discovery/DNS/subdomains-top1million-5000.txt -d shellhop.com -r adelaide.ns.cloudflare.com. -o results.txt</code>
            <p>Contents of results.txt:</p>
            <code>Found: www.shellhop.com</code>
            <p>Another useful tool is dnsenum, this is a comprehensive and recursive subdomain discovery tool. The syntax is similar to gobuster however the key difference is recursion, which is not a default feature of gobuster.</p>
            <code>$ dnsenum --enum &ltdomain&gt -f &ltPath To wordlist&gt</code><br>
            <p>dnsenum is extremely efficient, with an output that cleanly seperates name servers, mail servers etc., and will automate much of the previous reconnaissance such as whois requests and other passive techniques.</p>

            <h3>Zone Tranfer</h3>
            <p>There exists a mechanism within DNS that allows for complete transfer of all records from one server to another. This is called a domain transfer. In the modern age, this is unlikely to be effective, however, it's worth mentioning due to how easy it is to test for and how lucrative the results can be.</p>
            <p>To complete a zone transfer request with dig we can simply apply the 'axfr' record type, specify our name server with the '@' symbol and provide our domain.</p>
            <code>$ dig axfr shellhop.com</code>
        </section>
        <h1>Web application enumeration</h1>
        <section>
            <h2>Introduction</h2>
            <p>Web applications are extremely technology-dense. There are thousands of potential components that can make up web applications, and so they are usually targetted as part of their own sub-project within a penetration test. The following techniques are designed to enumerate web application technologies and associated resources.</p>
            
        </section>
        <section>
            <h2>VHOST Enumeration</h2>
            <p>A web server may host several websites for an organization. In order to seperate these resources and have all hosted sites be individually accessible they implement VHOST technology. Each site root and associated domain are configured seperately on the server. When a client requests a resource, they provide the target domain within the host header of the request, and this is what the server will check before providing a site.</p>
            <p>We can test for VHOSTS or 'hidden sites' on a web server with tools we have used before, namely gobuster.</p>
            <code>gobuster vhost -u &lturl&gt -w &ltpath to wordlist&gt --append-domain</code>
            <p>This will provide results similar to those seen in the dns brute force attack we used earlier, however targets a web server as opposed to a DNS server.</p>
        </section>

        <section>
            <h2>Well known URL's</h2>
            <p>There exists a convention in web application design known as 'well known URL's'. These are essentially pages that contain useful information about the website.</p>
            <h3>/security.txt</h3>
            <p>This page contains information that security researchers can use to report vulnerabilities. It contains useful contact information and can reveal what security measures are in place.</p>
            <h3>.well-known/change-password</h3>
            <p>This provides a centralised password change location.</p>
            <h3>openid-configuration</h3>
            <p>This provides information about OpenId connect, which exists as a layer atop oauth</p>
            <h3>assetlinks.json</h3>
            <p>Used for digital asset ownership verification</p>
            <h3>mta-sts.txt</h3>
            <p>Specifies the policy for SMTP MTA string transport security as an added email security measure.</p>
        </section>

        <section>
            <h2>Crawling</h2>
            <p>Crawling a website is the act of extracting information from the content of the webpage. Simple crawlers, (or spiders as they are often called), scrape the content on a page for links recursively, and build a sitemap to quickly learn the structure of a web application.</p>
            <p>Other crawling techniques can be used to find email addresses, contact numbers, images, videos etc.</p>
        </section>

        <section>
            <h2>robots.txt</h2>
            <p>This is a page used to outline specifications for web crawlers, basically whitelisting and blacklisting certain locations from excessive crawling. It is considered good practice to follow robots.txt when using automated tools, and failure to do so in certain juristictions may actually be illegal. The most common use case is to prevent certain pages from being scraped by search engines.</p>
        </section>

        <section>
            <h2>Search engine discovery</h2>
            <p>Search engines are extremely powerful tools, and often can be used in many ways most people never think of. Search engines come with several 'magic phrases' that can be used to narrow results down or exclude/include certain results.</p>
            <table>
                <thead>
                    <tr>
                        <th>Operator</th>
                        <th>Desc</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div>site:&lt;site name&gt;</div>
                        </td>
                        <td>Only returns results within that site</td>
                    </tr>
                    <tr>
                        <td>inurl:&lt;phrase&gt;</td>
                        <td>Limits results to those that contain the phrase in their URL</td>
                    </tr>
                    <tr>
                        <td>filetype:&lt;extension&gt;</td>
                        <td>
                            <div>Searches for a specific file type</div>
                        </td>
                    </tr>
                    <tr>
                        <td>intitle:&lt;phrase&gt;</td>
                        <td>limits results for those with the given phrase in the title</td>
                    </tr>
                    <tr>
                        <td>intext/inbody:&lt;phrase&gt;</td>
                        <td>limits results to those that contain the phrase within their body element</td>
                    </tr>
                    <tr>
                        <td>cache:&lt;page&gt;</td>
                        <td>Displays a cached version of the website</td>
                    </tr>
                    <tr>
                        <td>link:&lt;page&gt;</td>
                        <td>Finds pages that link to another site</td>
                    </tr>
                    <tr>
                        <td>related:&lt;site&gt;</td>
                        <td>finds websites related to a specific webpage</td>
                    </tr>
                    <tr>
                        <td>info:&lt;site&gt;</td>
                        <td>Returns a summary of data about a certain webpage</td>
                    </tr>
                    <tr>
                        <td>define:&lt;word&gt;</td>
                        <td>Defines a word</td>
                    </tr>
                    <tr>
                        <td>numrange:&lt;num1-num2&gt;</td>
                        <td>provides results that contain a number in their content</td>
                    </tr>
                    <tr>
                        <td>allintitle:&lt;phrase1 phrase2 phrase3&gt;</td>
                        <td>Allows for a logical 'and' search for the intitle: term</td>
                    </tr>
                    <tr>
                        <td>allinurl:</td>
                        <td>as above</td>
                    </tr>
                    <tr>
                        <td>allintitle:</td>
                        <td>as above</td>
                    </tr>
                    <tr>
                        <td>AND</td>
                        <td>acts as a logical AND</td>
                    </tr>
                    <tr>
                        <td>OR</td>
                        <td>acts as a logical OR</td>
                    </tr>
                    <tr>
                        <td>NOT</td>
                        <td>Excludes the next operator</td>
                    </tr>
                    <tr>
                        <td>*</td>
                        <td>Wildcard to represent any word or character</td>
                    </tr>
                    <tr>
                        <td>"&lt;phrase&gt;"</td>
                        <td>Quotes search for an exact phrase</td>
                    </tr>
                    <tr>
                        <td>-&lt;phrase&gt;</td>
                        <td>Removes results containing the following operator or phrase</td>
                    </tr>
                </tbody>
            </table>
        </section>

    </main>

    <!-- Including footer here -->
    <?php
    require_once(ROOT_PATH . '/resources/footer.php')
    ?>

</body>