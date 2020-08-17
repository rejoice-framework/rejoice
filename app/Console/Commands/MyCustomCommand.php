<?php

namespace App\Console\Commands;

use Rejoice\Console\Commands\SmileCommand;

class MyCustomCommand extends SmileCommand
{
    public function configure()
    {
        $this->setName('namespace:command')
            ->setDescription('This is a sample command')
            ->setHelp('This command shows how simple it is to create a command with rejoice.');
    }

    public function fire()
    {
        $this->success('It works :D');

        return SmileCommand::SUCCESS;
    }
}
