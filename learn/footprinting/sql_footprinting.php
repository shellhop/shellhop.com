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
        <h1>SQL Footprinting</h1>
        <section>
            <h2>Introduction</h2>
            <p>SQL is a database management language commonly used in the backend of dynamic web applications.</p>
            <p>Database files are often stored in a '.sql' file, and the infrastructure is client-server based.</p>
            <p>An SQL database will be passed values to conduct a query. Often, these values come from the user of the website, and if not sanitized propperly, can result in code being 'injected' into the server.</p>
        </section>

        <section>
            <h2>Dangerous settings</h2>
            <p>There are several dangerous settings within a MySQL server configuration file.</p>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Desc</th>
               </tr>
               <tr>
                    <td>user</td>
                    <td>Sets the user for the MySQL server to run as.</td>
                </tr>
                <tr>
                    <td>password</td>
                    <td>Sets password for MySQL user.</td>
                </tr>
                <tr>
                    <td>admin_address</td>
                    <td>Sets the IP address on which to listen for incoming connections on the admin network interface.</td>
                </tr>
                <tr>
                    <td>debug</td>
                    <td>Sets current debug settings</td>
                </tr>
                <tr>
                    <td>sql_warnings</td>
                    <td>Controls if informative warning strings show on single-row INSERT statements.</td>
                </tr>
                <tr>
                    <td>secure_file_priv</td>
                    <td>Used to limit the effect of data import and export operations</td>
                </tr>
            </table>
        </section>

        <section>
            <h2>Footprinting</h2>
            <p>We can footprint the service by using nmap on port 3306.</p>
            <code>$ nmap -p3306 &lt;target&gt; -sV -sC --script mysql*</code>
            <p>This will run a series of scripts on the target and pull lots of useful information for us.</p>
            <p>We can also use an SQL client to perform our interaction. If we have credentials, we can login with the mysql client.</p>
            <code>$ mysql -u root -pP455word -h 10.0.0.2</code>
            <p>From there, we have serveral useful commands available to us.</p>
            <code>$ show databases;</code>
            <p>This will show all databases we can access.</p>
            <code>$ select version();</code>
            <p>This will produce a version number for the instance.</p>
            <code>$ use mysql;</code>
            <p>This will select a particular sql database for further interaction.</p>
            <code>$ show tables;</code>
            <p>This will show several tables that exist in the database.</p>
            <p>The 'sys' table and 'information_schema' contain metadata about the tables themselves. These are invaluable at for finding sensitive information.</p>
            <code>$ use sys;<br>$ show tables</code>
            <p>This may result in some interesting tables, we can view collumns from a table with 'select'.</p>
            <code>$ select username, password from users;</code>
            <p>We can also use wildcards and boolean statements in our statements.</p>
            <code>$ select * from &lt;table&gt;;<br>$ select username from &lt; where username="&lt;string&gt;</code>

        </section>
    </main>

    <!-- Including footer here -->
    <?php
    require_once(ROOT_PATH . '/resources/footer.php')
    ?>

</body>