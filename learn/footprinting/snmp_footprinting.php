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
        <h1>SNMP Footprinting</h1>
        <section>
            <h2>Introduction</h2>
            <p>SNMP, or 'simple network management protocol', uses UDP port 161 to issue commands, and UDP port 161 to set 'traps', (more later).</p>
            <p>This protocol is another way to handle configuration tasks and monitor machines over a network. Although MSRPC and Linux's 'Samba' achieve similar goals, (you can query for the same information for example), the underlying technologies are completely different.</p>
            <p>SNMP can be configured on standard computers and servers, as well as IoT devices like printers, sensors, cameras, etc.</p>
            <p>The current standard of SNMP is 'SNMP v3' which introduces complexity as well as security measures for the protocol.</p>
            <p>SNMP can also exchange control commands using agents over port 161. The client can set certain values in the device and change certain settings.</p>
            <p>Although traditionally, no information is exchanged without specific initiation from an SNMP client, we can configure 'traps' to activate and send information to the client without being prompted over UDP port 162. These are usually triggered when a type of event occurs on the computer.</p>
            <p>In order to communicate, the client and server need to both have specific addresses for each SNMP object that is queryable.</p>
        </section>

        <section>
            <h2>MIB</h2>
            <p>The 'MIB' or 'Management Information Base' is a standardised format for storing device information. It ensures devices across many manufacturers can work out of the box with SNMP networks.</p>
            <p>The MIB contains at least one 'OID', or 'Object Identifier'. These are strings that contain the unique address and name for each queryable SNMP object, as well as the access rights, type and description of the object. These files are written in ASN.1, or 'Abstract Syntax Notation One' in ASCII text format.</p>
            <p>The MIBS do not contain data, but rather where to find the data when an object is queried.</p>
        </section>

        <section>
            <h2>OID</h2>
            <p>An 'OID' or 'Object Identifier' represents a node in a hierarchical namespace. This is a sequence of numbers that uniquely identify each node. The nodes position can be determined from these numbers. A long chain of numbers represents very specific information. There are many nodes in an OID tree contain nothing but references to OID's below them. OID's are integer based, concatenated with '.' marks. We can look up many MIB's to find the unique OIDs in the 'Object Identifier Registry'.</p>
            <p></p>
        </section>

        <section>
            <h2>SNMP Versions</h2>
            <h3>SNMPv1</h3>
            <p>The first version of SNMP was used for management and monitoring networks. The key issue however is that there was zero authentication supported, meaning anyone with network access can modify network data. Encryption was also not supported here, meaning network sniffing could result in serious sensitive data exposure.</p>
            <h3>SNMPv2</h3>
            <p>This version introduced 'community strings' as a form of authentication. However, these strings were transmitted in plaintext, meaning they could be sniffed and used against the network as before.</p>
            <h3>SNMPv3</h3>
            <p>Version 3 of SNMP introduces significant security measures. Encryption is now enabled via pre-shared key, and username/password authentication. However, v3 also introduces significant complexity to the protocol with a much more involved configuration.</p>
        </section>

        <section>
            <h2>Community Strings</h2>
            <p>Community strings are used in SNMP to determine wether a piece of requested information can be accessed. They act almost as a password in this regard. However, many organizations still use SNMPv2, meaning these strings can be sniffed and used against the organization.</p>
        </section>

        <section>
            <h2>Footprinting the service</h2>
            <p>There are many tools that can be used to automatically enumerate the SNMP service. 'snmpwalk' is one such tool.</p>
            <code>$ snmpwalk -v2c -c public 10.0.0.2</code>
            <p>The above command specifies the version, (2c), and the string, in this case 'public'. This may reveal certain packages or information about the system. If we do not know the community string, we can guess using common wordlists and the 'onesixtyone' tool.</p>
            <code>$ sudo apt install onesixtyone</code><br>
            <code>$ onesixtyone -c /opt/seclists/Discovery/SNMP/snmp.txt</code>
        </section>

    </main>

    <!-- Including footer here -->
    <?php
    require_once(ROOT_PATH . '/resources/footer.php')
    ?>

</body>