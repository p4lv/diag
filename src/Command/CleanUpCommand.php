<?php

namespace Diag\Command;

use Diag\Storage\CanCleanUp;
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
                null
            )
            ->setDescription('Clean DB');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $storage = $this->getContainer()->get('log.mapper.storage');
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
