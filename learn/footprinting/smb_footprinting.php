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
            <h2>Forword</h2>
            <p>I would like to preface this section with a small note. I got about 1 paragraph into writing this seciton before I asked myself, 'what is smb? where does it sit?'. I've a relatively strong understanding of protocols, their purpose and implementation across different technologies, however so many protocols seemed to interact with SMB in Windows environments and I struggled to put together a total picture. This then sent me down a multiple day rabbit hole where I attempted to make sense of everything. How TCP, Kerberos, LDAP, RPC, SMB, NTLM, NFS and more all fit together to create a cohesive network. There are so many deprecated technologies that are <i>still</i> available by default that pose <i>serious</i> security risks it is baffling. All I can say is to the Windows systems administrator, godspeed.</p>
        </section>
        <section>
            <h2>Introduction</h2>
            <p>SMB, or server message block, is a client-server protocol that manages access across a network to resources like files, directories, routers and more. SMB can also be used to manage cross-process information transfer across different devices.</p>
            <p>SMB is has become most commonly used with Windows machines, however there is a free Linux suite available called 'samba' which allows cross-platform interaction with Linux and Windows machines.</p>
            <p>The protocol relies on an existing TCP connection between two devices, each needing to have the SMB protocol configured as well.</p>
            <p>SMB uses access control lists, or ACL's to make arbitrary parts of it's file system available over the network. They can be configured with specific execute, read and write permissions based on individual users or groups.</p>
            <p>When a share is accessed via SMB, there is an exchange between the server and the client. This exchange is to authenticate the user. This authentication can occur via Kerberos, or NTLM. Once authenticated, the SMB command can be executed and a response provided.</p>
            <p>There are many protocols that piggy-back off of SMB, expanding its functionality. RPC is one of these, which is how Windows executes certain operating system commands like changing users or passwords on remote machines.</p>
            <p>Print spooling is also wrapped in an SMB session, where a file is sent to the printer queue.</p>
            <p>MS-RAP, (Remote administration protocol) is an older administration protocol that uses an SMB wrapper for the purpose of network discovery and basic management.</p>
            <p>SMB provides the fundamental language for file operations like 'read', 'write', 'delete', 'create', 'open', 'close', and 'rename'.</p>
            <p>SMB also provides metadata and attributes, as in it carries flags that represent the files status, access control lists and security descriptors, creation/modification dates etc. of a file.</p>
            <p>Despite its many functions, SMB focuses on files, and creates a standardised landscape by which files should be handled in a network.</p>

        </section>
        <section>
            <h2>RPC and named pipes</h2>
            <p>For the case of RPC, things run slightly differently, but the same logic still applies. Within windows there are 'virtual files', where instead of a physical location on the hard drive, there exists an application or service that will 'catch' the data being sent.</p>
            <p>Think of it this way, when SMB opens a 'file' like 'C:\\Users\Boss\Documents\Secrets.txt', you are just opening the file.</p>
            <p>When utilizing a remote procedure call or RPC command, you are 'opening' what <i>looks</i> like a file: 'C:\\Server\IPC$\netlogon', but this file only exists <i>in memory</i>.</p>
            <p>The 'ICP$' folder is a hidden, built in share built specifically for these virtual files, often called 'named pipes' in Windows. A similar concept exists in linux, with virual files found in the '/proc/' directory.</p>
            <p>This system offers many advantages for administrators, such as centralising adminstration over a single SMB port, utilizing SMB's built-in signing and encryption features to secure commands, and allowing for standard ACL's to be used when authenticating commands. e.g. only administrators can access these named pipes.</p>
            <p>Let's run through some of these named pipes, their function and how the are commonly interacted with. Keep in mind these pipes will always have an associated 'service' that listens internally.</p>
            
            <div>
                <h3><u>\pipe\lsarpc</u></h3>
                <h4>Connected Service</h4>
                <p>LSA: Local Seurity Authority</p>
                <h4>Purpose</h4>
                <p>This handles <b>local</b> security policies, and the translation between SID's and usernames.</p>
                <h4>Admin Example</h4>
                <p>When you are presented with a 'Permission Denied' error, it is because the server has communicated with this pipe, and deemed your SID does not have permission to log in.</p>
                <h4>Attacker Example</h4>
                <p>We can communicate with this pipe to resolve SID's into account names to find where the 'Administrator' or 'Domain Admin' accounts are.</p>
            </div>

            <div>
                <h3><u>\pipe\samr</u></h3>
                <h4>Connected Service</h4>
                <p>SAM: Security Account Manager</p>
                <h4>Purpose</h4>
                <p>This is the interface for the database of users and groups. It is used to create, delete, and modify users and groups as well as look up group memberships.</p>
                <h4>Admin Example</h4>
                <p>When you use 'net user' the OS is sending RCP calls through this pipe to modify the SAM database.</p>
                <h4>Attacker Example</h4>
                <p>While LDAP is used to 'find' users on a domain, 'samr' is used to modify those users or manage users on a local workgroup machine.</p>
            </div>

            <div>
                <h3><u>\pipe\netlogon</u></h3>
                <h4>Connected Service</h4>
                <p>Netlogon service</p>
                <h4>Purpose</h4>
                <p>This is used for 'passthrough' authentication, the domain controller uses this pipe to verify if NTLM responses are valid.</p>
                <h4>Admin Example</h4>
                <p>This is a key secure channel between a workstation and the domain controller. If this pipe fails, trust breaks between the two devices and you cannot perform a network logon.</p>
            </div>

            <div>
                <h3><u>\pipe\srvsvc</u></h3>
                <h4>Connected Service</h4>
                <p>Server Service</p>
                <h4>Purpose</h4>
                <p>This is where network shares are managed. This pipe is used to enumerate what filders are shared, create new shares and view currently connected users.</p>
                <h4>Admin Example</h4>
                <p>When you open the 'shared folders' in Computer Management, your PC queries this pipe.</p>
                <h4>Attacker Example</h4>
                <p>This is the first pipe an attacker will interact with. It is used to enumerate shares and find sensitive data.</p>
            </div>

            <div>
                <h3><u>\pipe\svcctl</u></h3>
                <h4>Connected Service</h4>
                <p>SCM: Service Control</p>
                <h4>Purpose</h4>
                <p>This is used to start, stop and configure windows services remotely.</p>
                <h4>Admin Example</h4>
                <p>This is used to restart a service on a remote machine, such as a stuck printer with a hanging spooler. When you hit restart on the remote service, you are interacting with this pipe over RPC through SMB.</p>
                <h4>Attacker Example</h4>
                <p>This is extremely valuable as it can be used to remotely create and launch services on lateral machines in the network.</p>          
            </div>

            <h2>The bigger picture</h2>
            <p>This all fits together in the 'IPC$' share on Windows machines. This share is accessed remotely via SMB, and each pipe is interacted with depending on the RPC call. The client writes the RPC command into the relevant pipe, and the server executes the command and passes the status of the command back into the pipe, which of course can be read by client.</p>

        </section>

        <section>
            <h2>Samba</h2>
            <p>Samba is the free Linux utility used to interact with SMB. Samba uses CIFS, or 'Common Internet File System', which is a permutation of SMB. This means Samba can be used to interact with newer versions of SMB used by Windows systems.</p>
            <p>SMB has gone through several iterations, notable SMB 2 and 3. The older versions did not support many key security measures that newer versions implemented.</p>
            <p>End to end encryption was only added in SMB 3, and AES-128 encryption in 3.1.1.</p>
            <p>Samba can now directly connect to Active Directory Domains, as well as function as a DC in AD environments.</p>
            <p>Samba is even so advanced as to allow standard RPC calls to interact with linux machines in the same way Windows machines are affected with user create, delete and modify commands.</p>
        </section>


        <section>
            <h2>Setting up an example share</h2>
            <p>I have taken the time to set up an example share on Linux for SMB using samba. The share is in '/srv/samba/test_share/'.</p>
            <p>I have a few key settings enabled, and my config looks like this:</p>
            <code>[test_share]<br>    comment = a test share<br>    path = /srv/samba/test_share/<br>    browsable = yes<br>    guest ok = yes<br>    read only = no<br>    writable = yes<br>    enable privileges = yes<br>    create mask = 0777<br>    directory mask = 0777</code>
        </section>

        <section>
            <h2>Interacting with an SMB share structure</h2>
            <p>There are a number of tools we can use to interact with SMB shares. The first of which is Nmap, we can discover if an SMB share is being presented on the usual port with:</p>
            <code>$ sudo nmap 10.0.0.3 -p 139</code>
            <p>Once a port has been discovered, we can use the SMBClient tool to see if anonymous login is enabled.</p>
            <code>$ smbclient -N -L //10.0.0.3</code>
            <p>The '-N' flag is for 'no login', and the '-L' flag is used to list all browsable shares.</p>
            <p>If anonymous login <i>is</i> enabled, we can connect to a share with the same client tool.</p>
            <code>$ smbclient //10.0.0.3/sharename</code>
            <p>We can then use 'ls' as normal to list our directories.</p>
            <code>$ ls</code>
            <p>Many standard navigation commands work here, but to download a file, we use 'get'.</p>
            <code>$ get secrets.txt</code>
            <p>A handy tip when in the smbclient console, we can use '!' to preface a command we want to run on our command shell outside of the smb console. Meaning we can download files and view their contents easily.</p>
            <code>$ !cat secrets.txt</code>
            <p>We can use the 'smbstatus' command to see the current connections from our PC to any shares or vice versa.</p>
            <code>$ smbstatus</code>
        </section>

        <section>
            <h2>Footprinting the service</h2>
            <p>We can use nmap scripts to quickly enumerate a SMB server.</p>
            <code>$ sudo nmap -sC -p445,139 -sV 10.0.0.3</code>
            <p>This is one rare instance where nmap won't be extremely useful. We would be better off utilizing the handy 'RPC' protocol that comes with Windows and now Samba, built into SMB.</p>
            <p>The client we use to make RPC calls is 'rpcclient'.</p>
            <code>$ rpcclient -U "" 10.0.0.3</code>
            <p>By leaving the '-U' or 'user' flag empty, we log in as guest.</p>
            <p>Despite not having privileged access, we can still gather a lot of extremely valuable information using RPC.</p>
            <p>Let's take a look at some of the most useful queries we can make. Remember, RPC interacts with the operating system itself through virtual files, it does not query a database with LDAP.</p>
            <table>
                <tr>
                    <th>Query</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>srvinfo</td>
                    <td>Server information</td>
                </tr>
                <tr>
                    <td>enumdomains</td>
                    <td>Enumerate all domains that are deployed on the network</td>
                </tr>
                <tr>
                    <td>querydominfo</td>
                    <td>Enumerate domain, user, and server information for deployed domains</td>
                </tr>
                <tr>
                    <td>netshareenumall</td>
                    <td>Enumerate all available shares</td>
                </tr>
                <tr>
                    <td>netsharegetinfo &lt;share&gt;</td>
                    <td>Provides info on a certain share</td>
                </tr>
                <tr>
                    <td>enumdomusers</td>
                    <td>Enumerate domain users</td>
                </tr>
                <tr>
                    <td>queryuser &lt;RID&gt;</td>
                    <td>Provide information on a user</td>
                </tr>
            </table>

            <p>Each user will have a 'RID', we can query for information on RID's if we already know an RID. However, as the RID values are predictable, we can use brute force methods to discover users on the domain.</p>
            <code>$ for i in $(seq 500 1100);do rpcclient -N -U "" 10.0.0.2 -c "queryuser 0x$(printf '%x\n' $i)" | grep "User Name\|user_rid|group_rid" && echo "";done</code>
            <p>The above code will enumerate users from RID '0x500' to '0x11000', if we have credentials we can use those to make requests too.</p>
        </section>

        <section>
            <h2>Automating SMB Enumeration</h2>
            <p>Many of these techniques can be automated with several tools. Tools like 'smbmap' and 'crackmapexec' are commonly used for quickly enumerating SMB shares. Let's take a quick look at some of these tools.</p>
            <h3>SMBMap</h3>
            <p>SMBmap is a tool built in python that was designed to automate SMB and RPC enumeration. The basic syntax is as follows:</p>
            <code>$ smbmap -H 10.0.0.2</code>
            <p>The above code will anonymously scan a host for shares, as well as display our available permissions for each share. If we have credentials for a Domain Account, we can use built-in authentication.</p>
            <code>$ smbmap -u &lt;username&gt; -p &lt;password&gt; -H 10.0.0.2</code>
            <p>Even with very limited Domain credentials, we will likely find extremely valuable domain information as opposed to being an anonymous user.</p>
            <h3>NetExec</h3>
            <p>NetExec is the modern alternative to 'crackmapexec', which was deprecated in 2023. It supports a very wide host of features, and should be looked into in more detail at a later time.</p>
        </section>
    </main>

    <!-- Including footer here -->
    <?php
    require_once(ROOT_PATH . '/resources/footer.php')
    ?>

</body>