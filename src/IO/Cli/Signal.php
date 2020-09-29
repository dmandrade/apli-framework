<?php
/**
 *  Copyright (c) 2018 Danilo Andrade.
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file Signal.php
 *
 * @author Danilo Andrade <danilo@webbingbrasil.com.br>
 * @date 27/08/18 at 10:27
 */

namespace Apli\IO\Cli;

use Apli\Support\Enum;

/**
 * Class Signal.
 *
 * @method static Signal HUP()
 * @method static Signal INT()
 * @method static Signal QUIT()
 * @method static Signal ILL()
 * @method static Signal TRAP()
 * @method static Signal ABRT()
 * @method static Signal IOT()
 * @method static Signal BUS()
 * @method static Signal FPE()
 * @method static Signal USR1()
 * @method static Signal SEGV()
 * @method static Signal USR2()
 * @method static Signal PIPE()
 * @method static Signal ALRM()
 * @method static Signal TERM()
 * @method static Signal STKFLT()
 * @method static Signal CLD()
 * @method static Signal CHLD()
 * @method static Signal CONT()
 * @method static Signal TSTP()
 * @method static Signal TTIN()
 * @method static Signal TTOU()
 * @method static Signal URG()
 * @method static Signal XCPU()
 * @method static Signal XFSZ()
 * @method static Signal VTALRM()
 * @method static Signal PROF()
 * @method static Signal WINCH()
 * @method static Signal POLL()
 * @method static Signal IO()
 * @method static Signal PWR()
 * @method static Signal SYS()
 * @method static Signal BABY()
 * @method static Signal BLOCK()
 * @method static Signal UNBLOCK()
 * @method static Signal SETMASK()
 */
class Signal extends Enum
{
    const HUP = 'SIGHUP';
    const INT = 'SIGINT';
    const QUIT = 'SIGQUIT';
    const ILL = 'SIGILL';
    const TRAP = 'SIGTRAP';
    const ABRT = 'SIGABRT';
    const IOT = 'SIGIOT';
    const BUS = 'SIGBUS';
    const FPE = 'SIGFPE';
    const USR1 = 'SIGUSR1';
    const SEGV = 'SIGSEGV';
    const USR2 = 'SIGUSR2';
    const PIPE = 'SIGPIPE';
    const ALRM = 'SIGALRM';
    const TERM = 'SIGTERM';
    const STKFLT = 'SIGSTKFLT';
    const CLD = 'SIGCLD';
    const CHLD = 'SIGCHLD';
    const CONT = 'SIGCONT';
    const TSTP = 'SIGTSTP';
    const TTIN = 'SIGTTIN';
    const TTOU = 'SIGTTOU';
    const URG = 'SIGURG';
    const XCPU = 'SIGXCPU';
    const XFSZ = 'SIGXFSZ';
    const VTALRM = 'SIGVTALRM';
    const PROF = 'SIGPROF';
    const WINCH = 'SIGWINCH';
    const POLL = 'SIGPOLL';
    const IO = 'SIGIO';
    const PWR = 'SIGPWR';
    const SYS = 'SIGSYS';
    const BABY = 'SIGBABY';
    const BLOCK = 'SIG_BLOCK';
    const UNBLOCK = 'SIG_UNBLOCK';
    const SETMASK = 'SIG_SETMASK';
}
