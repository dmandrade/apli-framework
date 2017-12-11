[![buddy pipeline](https://app.buddy.works/dmandrafe/apli-environment/pipelines/pipeline/66482/badge.svg?token=ac40fcb04e4ac8e6f132f5faee6df14e92ab5e74208dd573211f9c8d7f8d0943 "buddy pipeline")](https://app.buddy.works/dmandrafe/apli-environment/pipelines/pipeline/66482)

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
- [x] $env->getPhpVersion();
- [x] $env->isPHP();
- [x] $env->isHHVM();
- [x] $env->getRoot();
- [x] $env->getEntry();
- [x] $env->getWorkingDirectory();
- [x] $env->getServerParam( $key, $default = null );
- [ ] $env->getServerPublicRoot();
- [ ] $env->getRequestUri( $withParams = true );
- [ ] $env->getHost();
- [ ] $env->getPort();
- [ ] $env->getScheme();
