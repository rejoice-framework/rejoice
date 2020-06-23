<?php

/*
 * This file is part of the Rejoice package.
 *
 * (c) Prince Dorcis <princedorcis@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Prinx\Rejoice;

/**
 * Provides an interface to respect by any created session driver
 *
 * @author Prince Dorcis <princedorcis@gmail.com>
 */
interface SessionInterface
{
    public function isPrevious();

    public function delete();

    public function reset();

    public function hardReset();

    public function retrievePreviousData();

    public function retrieveData();

    public function previousSessionNotExists();

    public function save();
}
