<?php

namespace App\Command;

use App\Service\NewsGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateNewsSearchCommand extends Command
{
// the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:make-news';
    protected $newsGenerator;

    public function __construct(NewsGenerator $newsGenerator)
    {
        parent::__construct();
        $this->newsGenerator = $newsGenerator;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $this->newsGenerator->createNewsIndex();
      return 0;
    }
}