Go
==

Go is a easy-to-use PHP deployment manager

Go is under heavy development and it's probably a very bad idea to use it for your killer mega huge application ;)

Init
-----

This tool is experimental, so there is no installation process for now. Git clone it and `ln -vs the go executable in your path.

    $ go init [config dir]

This will create all the files needed by go to deploy your application.

Config
------

Edit the deploy.yml and Deploy.php files in your config dir (config/go by default)

Usage
-----

    $ go deploy [environment] [--go]

Deployment strategies
---------------------

Go supports multiple deployment strategies. For now, there's two :

<dl>
    <dt>Rsync</dt>
    <dd>Deploys your code in a remote directory via rsync</dd>
    <dt>VersionedRsync</dt>
    <dd>Like Rsync but puts code into a subdirectory (no ln, you can do it :))</dd>
</dl>

SSH
---
Go uses the (alpha) OOSSH lib, and allows you to run commands on your remote server.
The available methods are, in the Deploy.php class :

 * $this->exec($command)
 * $this->sudo($command)
 * $this->copy($from, $to, $recursive = false)

Note that the copy commands call `cp -p` and preserves owners, groups, and horodating.
The classic usage of it is in the pre/post deploy commands (needs to clean cache ?)

Pre/post deploy hooks
---------------------

You can execute some code before and/or after your deployment by overriding the pre/postDeploy methods tour {config_dir}/Deploy.php.
These methods are also env-duplicated. If your environment is `production` you can override pre/postDeployProduction.

These methods takes a `$go` boolean as parameter to execute or not some commands when dry-run.

Help
----

you can have (a bit of) help about go commands by typing

    $ go help [command]

You can also mail me at yohan@giarel.li
