# Introduction #

Budabot 3.0+ uses [Phing](http://www.phing.info/) as its build tool. With the tool you can:
  * [create a release archive](Creating_a_Release.md),
  * [generate api documentation](http://ci.budabot.com/userContent/budabot-api/index.html),
  * [generate wiki documentation](ApiIndex.md) and
  * also perhaps in future run unit tests, integration tests, etc...

Before you can use the build tool you need to install it first.

# Installing #

  1. Install PHP 5.3 or newer, for Windows users look at [here](http://windows.php.net/download/), for users of other OSes use your own package manager to install it,
  1. Install PHP's package manager Pear ([instructions](http://pear.php.net/manual/en/installation.getting.php)),
  1. Install Phing build tool with Pear ([instructions](http://www.phing.info/trac/wiki/Users/Installation)), you may need to install phing/phing-alpha if you're using Subversion 1.7 client or newer,
  1. Install command-line Subversion client, for Windows users you can use e.g. [TortoiseSvn](http://tortoisesvn.net/downloads.html) and make sure that you include the command-line client in the installer (required for build-target),
  1. Install PHP Subversion client wrapper with Pear ([instructions](https://pear.php.net/package/VersionControl_SVN/)), install VersionControl\_SVN-alpha, otherwise you get errors complaining that there are no stable release (required for build-target),
  1. Install ApiGen documentation generator with Pear ([instructions](http://apigen.org/##installation)), (required for wikidoc- and apidoc-targets)
  1. Install PHP SSH2 extension, Windows users can get it from [here](http://downloads.php.net/pierre/), for users of other OSes use your own package manager to install it (required for upload-targets),

# Usage #
Open command prompt (or bash, etc...) and cd to Budabot's branch or trunk folder, make sure that there is a file named **build.xml** in it.

To see what targets there are available, run:
```
phing -list
```
Prints something like this:
```
Buildfile: E:\PHP\budabot_svn\trunk\build.xml
Default target:
-------------------
 build

Subtargets:
-------------------
 apidoc
 apidoc+upload
 build
 build+upload
 wikidoc
```

## Build Target ##
This will create a release archive of the current branch. The created archive can be found afterwards from build folder.
To run this:
```
phing build
```
Or just:
```
phing
```

## Build+Upload Target ##
This will first run **build**-target and then it uploads the generated archive using SCP-protocol to remote server.
You need to provide properties for the target, specifying the remote server's **username**, **password**, **host**-address and **path** to folder at the remote server where the archive will be stored.
To run this:
```
phing -Dupload.username=username -Dupload.password=password -Dupload.hostname=host -Dbuild.upload_destination=path build+upload
```

## ApiDoc Target ##
This will create API documentation of Budabot's sources to docs/api folder. Continuous integration server will run this whenever trunk's sources change, but you can run this as well.
Run it like this:
```
phing apidoc
```
You can also pick another location for the documentation, like this:
```
phing -Dapidoc.destination=$JENKINS_HOME/userContent/budabot-api apidoc
```

## ApiDoc+Upload Target ##
This will first run **apidoc**-target and then it uploads the generated api documentation files using SCP-protocol to remote server.
You need to provide properties for the target, specifying the remote server's **username**, **password**, **host**-address and **path** to folder at the remote server where the documentation's files will be stored.
To run this:
```
phing -Dupload.username=username -Dupload.password=password -Dupload.hostname=host -Dapidoc.upload_destination=path apidoc+upload
```
NOTE: The uploading seems to be quite slow, this might take easily 20 minutes to execute.

## WikiDoc Target ##
This will create Google Wiki documentation of Budabot's sources to docs/wiki folder. Continuous integration server will run this whenever trunk's sources change and commit them to the Wiki, but you can run this as well.
Run it like this:
```
phing wikidoc
```
You can also pick another location for the documentation, like this:
```
phing -Dwikidoc.destination=../wiki wikidoc
```