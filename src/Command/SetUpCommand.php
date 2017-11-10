<?php

namespace Diag\Command;

use Diag\Config;
use Diag\Storage\CanCleanUp;
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
                getenv('DIAG_DEFAULT_STORAGE')
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = new Config();

        $storageClassName = '\\Diag\\Storage\\' . $input->getOption('storage');
        if (
            !preg_match('/^[a-z]+$/i', $input->getOption('storage')) ||
            !$config->hasStorage($input->getOption('storage')) ||
            !class_exists($storageClassName)
        ) {
            throw new \RuntimeException('storage not supported');
        }

        $storage = new $storageClassName($config);
        if (!($storage instanceof CanSetUp)) {
            $output->writeln('storage does not support set up');
            return 1;
        }
        $output->writeln('setting up...');
        $storage->setup();
        $output->writeln('set up successful');
    }
}