<?php

namespace Diag\Command;

use Diag\Storage\CanCleanUp;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class CleanUpCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('maintenance:clean-up')
            ->addOption(
                'now',
                null,
                InputArgument::OPTIONAL,
                'time string to use as now',
                'now'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $storageClassName = $this->container->getParameter('diag.storage');
        $storage = $this->container->get($storageClassName);

        if (!($storage instanceof CanCleanUp)) {
            $output->writeln('storage does not support clean up');
            return 10;
        }
        $output->writeln('cleaning up...');

        $now = new \DateTimeImmutable($input->getOption('now'));

        if (!$storage->cleanup($now)) {
            $output->writeln('clean up failed');
            return 2;

        }

        $output->writeln('clean up successful');

        return 0;
    }
}
