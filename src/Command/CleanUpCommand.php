<?php

namespace Diag\Command;

use Diag\Storage\CanCleanUp;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class CleanUpCommand extends Command//ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('maintenance:clean-up')
            ->addOption(
                'storage',
                's',
                InputArgument::OPTIONAL,
                'storage engine to use',
                getenv('DIAG_DEFAULT_STORAGE')
            )
            ->addOption(
                'now',
                null,
                InputArgument::OPTIONAL,
                'time string to use as now',
                null
            )

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        global $container;
        $storageClassName = 'Diag\\Storage\\' . $input->getOption('storage');
        if (!class_exists($storageClassName)) {
            throw new \RuntimeException('storage not supported');
        }

        $storage = $container->get($storageClassName);
        if (!($storage instanceof CanCleanUp)) {
            $output->writeln('storage does not support clean up');
            return 1;
        }
        $output->writeln('cleaning up...');

        if ($storage->cleanup(
            $input->getOption('now') ? new \DateTime($input->getOption('now')) : null
            )) {
            $output->writeln('clean up successful');
        } else {
            $output->writeln('clean up failed');
        }
    }
}
