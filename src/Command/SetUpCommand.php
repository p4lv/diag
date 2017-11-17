<?php

namespace Diag\Command;

use Diag\Storage\CanSetUp;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class SetUpCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('maintenance:set-up')
            ->addOption(
                'storage',
                's',
                InputArgument::OPTIONAL,
                'storage engine to use',
                'Sqlite'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        global $container;
        $storagename = ucfirst(strtolower($input->getOption('storage')));
        $storageClassName = 'Diag\\Storage\\' . $storagename;

        if (!class_exists($storageClassName)) {
            throw new \RuntimeException('storage not supported');
        }

        $storage = $container->get($storageClassName);
        if (!($storage instanceof CanSetUp)) {
            $output->writeln('storage does not support set up');
            return 1;
        }
        $output->writeln('setting up...');
        $storage->setup();
        $output->writeln('set up successful');
    }
}
