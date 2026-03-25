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
        <h1>Git</h1>
        <section>
            <h2>Introduction</h2>
            <p>What is git?<br>Git is a wonderful comprehensive tool for versioning software, AKA, managing the development and updating of software over time accross different platforms. It allows you to store versions of software in a single repository, push updates, revert updates, make incremental changes, branch off to different software versions and merge the code of several developers.</p>
            <p>Here you will learn the very basics of git. How to set up an environment, how to set up some essential details like your name and email, how to link to a remote repository like github, etc.</p>     
        </section>
        
        <section>
            <h2>Setting up global details</h2>
            <p>After installing the git command-line tool, we need to start adding a few details about ourselves that will be recorded when we make changes to a code repository.</p>
            <p>We can set our names with the following:</p>
            <code>$ git config --global user.name "name here"</code><br>
            <p>We can set our email address with:</p>
            <code>$ git config --global user.email "youremailhere@email.com</code>
            
       </section>
       <section>
        <h2>Getting started with git</h2>
            <p>We can initialize our local code repo into a git managed repo with:</p>
            <code>$ git init</code>
            <p>Now we have our profile, and initialized our repository to be managed by git, we need to start including and excluding files to our git project.</p>
            <p>We can add all the files in our repository to the git project with the following:</p>
            <code>$ git add *</code>
            <p>We can replace our wildcard operator with the name or path to any file in our repo, now git will be monitoring these files for name changes, file changes, and other details.</p>
            <p>When we are happy with the progress we have made, wether it be setting up our file structure, removing a bug, or adding a feature, we can commit these changes into a 'code version'.</p>
            <code>$ git commit -m "Message about the changes you made here"</code>
            <p>We can also add and remove files with the git command line. To remove a file from our folder <i>and</i> the git repo we use the following:</p>
            <code>$ git rm &lt;filename&gt;</code>
            <p>To simply remove the file from the git tracker we use the following:</p>
            <code>$ git rm --cached &lt;filename&gt;</code>
       </section>

       <section>
            <h2>Working with remote repository's</h2>
            <p>We can send our file changes to a remote repository, or remote origin with git. This is handy to keep your code consistent accross multiple devices and working with other developers.</p>
            <p>To add a remote origin, we use the following:</p>
            <code>$ git remote add origin &lt;github repository.git&gt;</code>
            <p>And push our changes of the most recent commit with:</p>
            <code>$ git push origin &lt;branch name&gt;</code>
            <p>A 'branch' in this context is like a version history split from a previous version of the software, with its own bugfixes and features. It is useful to branch off from the main branch when working on experimental features as to not disturb the stability of the main branch and for testing. However, in 9/10 cases, the branch you will be pushing to for simple single-user git projects is 'main'</p>
            <p>To actually push our repo to a remote origin, we need to authenticate with the remote server with our, in this case, github account. We can do this with the following command:</p>
            <code>git config --global credential.helper store</code>
            <p>Next time we run a git command that requires authentication, we can enter our remote repository credentials and they will be saved to a local file, allowing us to make changes conventiently.</p>
       </section>
    </main>

    <!-- Including footer here -->
    <?php
    require_once(ROOT_PATH . '/resources/footer.php')
    ?>




</body>