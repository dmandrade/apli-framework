[![buddy pipeline](https://app.buddy.works/dmandrade/apli-environment/pipelines/pipeline/143627/badge.svg?token=4a2275d6858fa1a6ef485756ed5866894986f01ba3b8954a3d69cc94725ea11d "buddy pipeline")](https://app.buddy.works/dmandrade/apli-environment/pipelines/pipeline/143627)

# Apli Environment

Provide a set of methods to help us know information about server and php.

## Installation via Composer

Add this to the require block in your `composer.json`.

``` json
{
    "require": {
        "apli/environment": "~1.0"
    }
}
```

### Detect Running Environment

``` php
$env = new Environment;
$env->isWeb();
$env->isCli();
$env->server()->os()->isUnix();
$env->server()->os()->getKernelName(); // OPENBSD, WIN32, DARWIN
$env->server()->os()->getOsName(); // Windows, Linux, Mac OSX, BSD, Sun OS

$server = new Server();
$server->getHost();
$server->getPort();

$os = new Platform();
$os->getKernelName();
$os->getOsName();

```

### Complete list of methods

Environment methods

- [x] $env->isWeb();
- [x] $env->isCli();
- [x] $env->getPhpVersion();
- [x] $env->isPHP();
- [x] $env->isHHVM();

Server methods
- [x] $env->server()->getRoot();
- [x] $env->server()->getEntry();
- [x] $env->server()->getWorkingDirectory();
- [x] $env->server()->getServerParam( $key, $default = null );
- [x] $env->server()->getServerPublicRoot();
- [x] $env->server()->getRequestUri( $withParams = true );
- [x] $env->server()->getHost();
- [x] $env->server()->getPort();
- [x] $env->server()->getScheme();

OS related methods

- [x] $env->server()->os()->getKernelName();
- [x] $env->server()->os()->getOsFamily();
- [x] $env->server()->os()->getOsName();
- [x] $env->server()->os()->isUnix();
- [x] $env->server()->os()->isUnixOnWindows();
- [x] $env->server()->os()->isWindows();
- [x] $env->server()->os()->isOsx();
