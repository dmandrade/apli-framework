<?php
/**
 *  Copyright (c) 2018 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file Build.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 27/07/18 at 10:11
 */

use Apli\Application\AbstractCliApplication;

include_once __DIR__.'/../vendor/autoload.php';

define('APLI_ROOT', realpath(__DIR__.'/..'));

/**
 * Class Build to build subtrees.
 */
class Build extends AbstractCliApplication
{
    /**
     * Organization.
     *
     * @var string
     */
    protected $organization = 'dmandrade';

    /**
     * Code license.
     *
     * @var string
     */
    protected $license = 'GNU Lesser General Public License version 3 or later.';

    /**
     * Property lastOutput.
     *
     * @var mixed
     */
    protected $lastOutput = null;

    /**
     * Property lastReturn.
     *
     * @var mixed
     */
    protected $lastReturn = null;

    /**
     * Code branch.
     *
     * @var string
     */
    protected $branch = null;

    /**
     * Code version.
     *
     * @var string
     */
    protected $version = null;

    /**
     * Property subtrees.
     *
     * @var array
     */
    protected $subtrees = [
        'application' => 'Application',
        'data'        => 'Data',
        'environment' => 'Environment',
        'filter'      => 'Filter',
        'io'          => 'IO',
        'session'     => 'Session',
        'support'     => 'Support',
    ];

    /**
     * Method to run this application.
     *
     * @return bool
     */
    protected function doExecute()
    {
        if ($this->io->getOption('h')) {
            return $this->help();
        }

        $this->version = $tag = $this->io->getOption('t') ?: $this->io->getOption('tag');

        $branch = $this->io->getOption('b') ?: $this->io->getOption('branch', 'master');

        $force = $this->io->getOption('f') ?: $this->io->getOption('force', false);

        $force = $force ? ' -f' : false;

        if ($this->version && !$this->io->getOption('no-replace')) {
            $this->replaceDocblockTags();
        }

        $this->branch = $branch;

        $this->exec('git fetch origin');

        $this->checkoutBranch();

        if ($this->version) {
            $this->exec('git tag -d '.$tag);

            $this->exec('git push origin :refs/tags/'.$tag);

            $this->exec('git tag '.$tag);

            $this->exec(sprintf('git push origin %s'.$force, $this->version));
        }

        $masterBranch = ($branch != 'master') ? 'master:master' : '';
        $this->exec(sprintf('git push origin %s %s:%s %s '.$force, $tag, $branch, $branch, $masterBranch));

        $allows = $this->io->getArguments();

        foreach ($this->subtrees as $subtree => $namespace) {
            if ($allows && !in_array($subtree, $allows)) {
                continue;
            }

            $this->splitTree($subtree, $namespace);
        }

        $this->exec('git checkout master');

        $this->out()->out('Split finish.');

        return true;
    }

    /**
     * help.
     *
     * @return bool
     */
    protected function help()
    {
        $help = <<<'HELP'
Apli Build Command.

Will run subtree split and push every packages to it's repos.

Usage: php build.php [packages] [-t] [-b=test] [-f] [--dry-run] [--no-replace]

-t | --tags     Git tag of this build, will push to main repo and every subtree.
-b | --branch   Get branch to push, will  push to main repo and every subtree.
-f | --force    Override commits or not.
--dry-run       Do not real push, just run the subtree split process.
--no-replace    Do not replace the docblock variables.

HELP;

        $this->out($help);

        return true;
    }

    /**
     * replaceDocblockTags.
     *
     * @return void
     */
    protected function replaceDocblockTags()
    {
        $this->out('Replacing Docblock');

        $files = new RecursiveIteratorIterator(new \RecursiveDirectoryIterator(APLI_ROOT.'/src',
            \FilesystemIterator::SKIP_DOTS));

        /** @var \SplFileInfo $file */
        foreach ($files as $file) {
            if ($file->isDir() || $file->getExtension() != 'php') {
                continue;
            }

            $content = file_get_contents($file->getPathname());

            $content = str_replace(
                ['{DEPLOY_VERSION}', '__DEPLOY_VERSION__', '__LICENSE__', '{ORGANIZATION}'],
                [$this->version, $this->version, $this->license, $this->organization],
                $content
            );

            file_put_contents($file->getPathname(), $content);

            $this->out('[Replace Docblock] '.$file->getPathname());
        }

        $this->exec('git checkout master');
        $this->exec(sprintf('git commit -am "Prepare for %s release."', $this->version));
        $this->exec('git push origin master');
    }

    /**
     * Exec a command.
     *
     * @param string $command
     * @param array  $arguments
     * @param array  $options
     *
     * @return string
     */
    protected function exec($command, $arguments = [], $options = [])
    {
        $arguments = implode(' ', (array) $arguments);
        $options = implode(' ', (array) $options);

        $command = sprintf('%s %s %s', $command, $arguments, $options);

        $this->out('>> '.$command);

        if ($this->io->getOption('dry-run')) {
            return '';
        }

        $return = exec(trim($command), $this->lastOutput, $this->lastReturn);

        $this->out($return);
    }

    protected function checkoutBranch()
    {
        if ($this->branch = 'master') {
            $this->exec('git checkout '.$this->branch);

            return;
        }

        $this->exec('git branch -D '.$this->branch);

        $this->exec('git checkout -b '.$this->branch);

        $this->exec('git merge master');
    }

    /**
     * Split Git subTree.
     *
     * @param string $subtree
     * @param string $namespace
     *
     * @return void
     */
    protected function splitTree($subtree, $namespace)
    {
        $this->out()->out(sprintf('@ Start subtree split (%s)', $subtree))
            ->out('---------------------------------------');

        // Do split
        $this->exec(sprintf('git branch -D sub-%s', $subtree));
        $this->exec('git subtree split -P src/'.$namespace.' -b sub-'.$subtree);

        // Create a new branch
        $this->exec(sprintf('git branch -D %s-%s', $this->branch, $subtree));

        // Add remote repo
        $this->exec(sprintf('git remote add %s git@github.com:%s/apli-%s.git', $subtree, $this->organization,
            $subtree));

        $force = $this->io->getOption('f') ?: $this->io->getOption('force', false);

        $force = $force ? ' -f' : false;

        if (!$force) {
            $this->exec(sprintf('git fetch %s', $subtree));

            $this->exec(sprintf('git checkout -b %s-%s --track %s/%s', $this->branch, $subtree, $subtree,
                $this->branch));

            $this->exec(sprintf('git merge sub-%s', $subtree));
        }

        $this->exec(sprintf('git push %s sub-%s:%s '.$force, $subtree, $subtree, $this->branch));

        if ($this->version) {
            $this->exec('git checkout sub-'.$subtree);

            $this->exec(sprintf('git tag -d %s', $this->version));

            $this->exec(sprintf('git push %s :refs/tags/%s', $subtree, $this->version));

            $this->exec(sprintf('git tag %s', $this->version));

            $this->exec(sprintf('git push %s %s', $subtree, $this->version));
        }

        $this->exec('git checkout '.$this->branch);
    }

    /**
     * stop.
     *
     * @param string $msg
     *
     * @return void
     */
    protected function stop($msg = null)
    {
        if ($msg) {
            $this->out($msg);
        }

        $this->close();
    }
}

$app = new Build();

$app->execute();
