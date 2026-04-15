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
        <h1>NFS</h1>    
        <section>
            <h2>Introduction</h2>
            <p>NFS is to Linux what SMB is to Windows. It is the default, natively supported method of remote file access. The goal of NFS was to access remote files as if they where local files.</p>
            <p>NFS has gone through many iterations in the past, each implementing new features and support. The most recent iteration supports Kerberos, through-firewall support, ACL support, and improved performance and security.</p>
            <p>NFS is a client-server architecture, that utilised remote procedure calls and a common data encoding format called 'XDR' or External Data Representation. These remote procedure calls differ from the Windows implementation however.</p>
            <p>In Windows, SMB is the file share protocol, however you can 'nest' an RCP call within the smb data block to perform different actions like reading server information or modifying users. In Linux, these RPC calls act as the flat communication method for all file operations, and these calls are things like 'read', 'write', 'delete' etc.</p>
            <p>These requests are sent to the 'nfsd' daemon on the server, which interprets the request and makes the changes to the files.</p>
            <p>Authentication can be done via kerberos, or through 'trust' systems where the UID and GID are sent in the RPC header. The latter is easily spoofable and must be combined with more layers of security such as IP rules to reduce risk.</p>
        </section>

        <section>
            <h2>Default NFS configuration</h2>
            <p>There are far fewer settings in our NFS config file than SMB. After installing 'nfs-kernel-server', we have a file called '/etc/exports' where we can configure our shares.</p>
            <p>We seperate each line in our exports file into two main sections, first, the mount location, second, the subnet and permissions of the share.</p>
            <code>/srv/nfs_share    10.0.0.0/24(sync,no_subtree_check)</code>
            <p>The above line can be added to the end of our existing exports file to make the '/srv/nfs_share' available, and we restart our kernel server.</p>
            <code>$ echo '/srv/nfs_share    10.0.0.0/24(sync,no_subtree_check)'' >> /etc/exports <br>
                  $ systemctl restart nfs-kernel-server</code>
        </section>

        <section>
            <h2>Footprinting NFS</h2>
            <p>We can get started with some NMAP scripts. These scripts let us view available shares and information on the NFS server.</p>
            <code>$ sudo nmap 10.0.0.3 -sV --script=nfs* -p111,2049</code>
            <p>Another method of listing NFS shares is 'showmount'.</p>
            <code>$ showmount -e 10.0.0.3</code>
        </section>

        <section>
            <h2>Mounting an nfs share</h2>
            <p>We can mount an nfs share to view its user and group contents with mount:</p>
            <code>$ mkdir target-NFS <br>
        $ sudo mount -t nfs 10.0.0.3:/ ./target-NFS/ -o nolock</code>
        <p>We can now enter the mounted directory and browse as we see fit.</p>
        </section>
    </main>

    <!-- Including footer here -->
    <?php
    require_once(ROOT_PATH . '/resources/footer.php')
    ?>

</body>