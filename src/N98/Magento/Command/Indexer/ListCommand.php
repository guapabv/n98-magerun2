<?php

namespace N98\Magento\Command\Indexer;

use N98\Util\Console\Helper\Table\Renderer\RendererFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends AbstractIndexerCommand
{
    protected function configure()
    {
        $this
            ->setName('index:list')
            ->setDescription('Lists all magento indexes')
            ->addOption(
                'format',
                null,
                InputOption::VALUE_OPTIONAL,
                'Output Format. One of [' . implode(',', RendererFactory::getFormats()) . ']'
            )
        ;

        $help = <<<HELP
Lists all Magento indexers of current installation.
HELP;
        $this->setHelp($help);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->detectMagento($output, true);
        if (!$this->initMagento()) {
            return;
        }

        $table = array();
        foreach ($this->getIndexerList() as $index) {
            $table[] = array(
                $index['code'],
                $index['title'],
                $index['status'],
                $index['last_runtime'],
            );
        }

        $this->getHelper('table')
            ->setHeaders(array('code', 'title', 'status', 'time'))
            ->renderByFormat($output, $table, $input->getOption('format'));
    }
}
