[![StyleCI](https://github.styleci.io/repos/113188079/shield?branch=master)](https://github.styleci.io/repos/113188079)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/f219ba400ca2488c92d488c8d6945b53)](https://www.codacy.com/app/mandrade.danilo/apli-environment?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=dmandrade/apli-environment&amp;utm_campaign=Badge_Grade)

# Aplí Environment

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
$env->server()->platform()->isUnix();
$env->server()->platform()->getKernelName(); // OPENBSD, WIN32, DARWIN
$env->server()->platform()->getOsName(); // Windows, Linux, Mac OSX, BSD, Sun OS

$server = new Server();
$server->getHost();
$server->getPort();

$platform = new Platform();
$platform->getKernelName();
$platform->getOsName();

$systemDetector = new SystemDetector();
$systemDetector->detect('LINUX');

```

### Registering a new Operating System to SystemDetector

Create a operating system class, for example `Linux.php`
``` php
use Apli\Environment\OperatingSystem;
use Apli\Environment\SystemDetector;

class Linux implements OperatingSystem
{
    /**
     * Array ir kernel variant names
     *
     * @return array
     */
    public static function getVariants()
    {
        return [
            'LINUX',
            'GNU',
            'GNU/LINUX',
        ];
    }

    /**
     * Operating system name
     *
     * @return string
     */
    public function getName()
    {
        return 'Linux';
    }

    /**
     * Operating system family
     *
     * @return int
     */
    public function getFamily()
    {
        return SystemDetector::UNIX_FAMILY;
    }

    /**
     * Operating system name
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
```

Register class in SystemDetector

```php
SystemDetector::registerOperatingSystem(Linux::class);
```

### Complete list of methods

Environment methods

- [x] $env->isWeb();
- [x] $env->isCli();
- [x] $env->getPhpVersion();
- [x] $env->isPHP();
- [x] $env->isHHVM();
- [x] $env->server();

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
- [x] $env->server()->platform();

OS related methods

- [x] $env->server()->platform()->getKernelName();
- [x] $env->server()->platform()->getOsFamily();
- [x] $env->server()->platform()->getOsName();
- [x] $env->server()->platform()->isUnix();
- [x] $env->server()->platform()->isUnixOnWindows();
- [x] $env->server()->platform()->isWindows();
- [x] $env->server()->platform()->isOsx();
- [x] $env->server()->platform()->operatingSystem();
