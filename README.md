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
$env->isUnix();
$env->getKernelName(); // OPENBSD, WIN32, DARWIN
$env->getOsName(); // Windows, Linux, Mac OSX, BSD, Sun OS
```

### Complete list of methods

- [x] $env->getKernelName();
- [x] $env->getOsFamily();
- [x] $env->getOsName();
- [x] $env->isUnix();
- [x] $env->isUnixOnWindows();
- [x] $env->isWindows();
- [x] $env->isOsx();
- [x] $env->isWeb();
- [x] $env->isCli();
- [ ] $env->getPhpVersion();
- [ ] $env->getRoot( $full = true );
- [ ] $env->getEntry( $full = true );
- [ ] $env->getWorkingDirectory();
- [ ] $env->getServerParam( $key, $default = null );
- [ ] $env->getServerPublicRoot();
- [ ] $env->getRequestUri( $withParams = true );
- [ ] $env->getHost();
- [ ] $env->getPort();
- [ ] $env->getScheme();
