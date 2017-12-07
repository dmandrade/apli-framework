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
$env->getOs(); // WIN, LIN, CYG, DAR, etc..
```

### Complete list of methods

- [x] $env->getUname();
- [x] $env->setUname($uname);
- [x] $env->getOS();
- [x] $env->setOS( $os );
- [x] $env->isUnix();
- [x] $env->isWin();
- [x] $env->isLinux();
- [ ] $env->isWeb();
- [ ] $env->getPhpVersion();
- [ ] $env->getRoot( $full = true );
- [ ] $env->getEntry( $full = true );
- [ ] $env->getWorkingDirectory();
- [ ] $env->getServerParam( $key, $default = null );
- [ ] $env->isCli();
- [ ] $env->getServerPublicRoot();
- [ ] $env->getRequestUri( $withParams = true );
- [ ] $env->getHost();
- [ ] $env->getPort();
- [ ] $env->getScheme();
