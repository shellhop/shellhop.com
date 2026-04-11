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
        <h1>SMB</h1>
        <section>
            <h2>Introduction</h2>
            <p>SMB, or server message block, is a client-server protocol that manages access across a network to resources like files, directories, routers and more. SMB can also be used to manage cross-process information transfer across different devices.</p>
            <p>SMB is has become most commonly used with Windows machines, however there is a free Linux suite available called 'samba' which allows cross-platform interaction with Linux and Windows machines.</p>
            <p>The protocol relies on an existing TCP connection between two devices, each needing to have the SMB protocol configured as well.</p>
            <p>SMB uses access control lists, or ACL's to make arbitrary parts of it's file system available over the network. They can be configured with specific execute, read and write permissions based on individual users or groups.</p>
        </section>

        <section>
            <h2>Samba</h2>
            <p>Samba is the free Linux utility used to interact with SMB. Samba uses CIFS, or 'Common Internet File System', which is a permutation of SMB. This means Samba can be used to interact with newer versions of SMB used by Windows systems.</p>
            <p>SMB has gone through several iterations, notable SMB 2 and 3. The older versions did not support many key security measures that newer versions implemented.</p>
            <p>End to end encryption was only added in SMB 3, and AES-128 encryption in 3.1.1.</p>
            <p>Samba can now directly connect to Active Directory Domains, as well as function as a DC in AD environments.</p>
            <p>In a SMB network, the group of devices that are 'connected' is called a 'workgroup'. There can be multiple workgroups on a network at any given time.</p>
            <p></p>
        </section>
    </main>

    <!-- Including footer here -->
    <?php
    require_once(ROOT_PATH . '/resources/footer.php')
    ?>

</body>