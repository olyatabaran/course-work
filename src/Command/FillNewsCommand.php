<?php

namespace App\Command;


use App\Entity\News;
use App\Service\NewsGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class FillNewsCommand extends Command
{
// the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:fill-news';
    protected $container;
    protected $newsGenerator;

    public function __construct(NewsGenerator $newsGenerator, ContainerInterface $container)
    {
        parent::__construct();
        $this->newsGenerator = $newsGenerator;
        $this->container = $container;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $news = $this->container->get('doctrine')
            ->getRepository(News::class)
            ->findAll();

        $this->newsGenerator->fillNews($news);
        return 0;
    }
}