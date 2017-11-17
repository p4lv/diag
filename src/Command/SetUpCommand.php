<?php

namespace Diag\Command;

use Diag\Storage\CanSetUp;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetUpCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('maintenance:set-up')
            ->setDescription('Prepare DB;');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $storage = $this->getContainer()->get('log.mapper.storage');
        if (!($storage instanceof CanSetUp)) {
            $output->writeln('storage does not support set up');
            return 1;
        }
        $output->writeln('setting up...');
        $storage->setup();
        $output->writeln('set up successful');
    }
}
