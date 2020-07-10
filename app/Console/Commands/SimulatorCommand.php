<?php
namespace App\Console\Commands;

use Prinx\Rejoice\Console\SmileCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MyCustomCommand extends SmileCommand
{
    public function configure()
    {
        $this->setName('namespace:command')
            ->setDescription('This is a sample command')
            ->setHelp('This command shows how simple it is to create a command with rejoice.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>It works :D</info>');

        return SmileCommand::SUCCESS;
    }
}
