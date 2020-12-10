<?php

namespace App\Command;

use App\Entity\Region;
use App\Repository\RegionRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ListRegionsCommand extends Command
{
    
    /**
     * @var RegionRepository
     */
    private $regionRepository;
    
    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->regionRepository = $container->get('doctrine')->getManager()->getRepository(Region::class);
    }
    
    
    
    protected static $defaultName = 'app:list-regions';

    protected function configure()
    {
        $this
            ->setDescription('affiche la liste des regions')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $regions = $this->regionRepository->findAll();
        if(!$regions) {
            $output->writeln('<comment>no regions found<comment>');
            exit(1);
        }
        
        foreach($regions as $region)
        {
            $output->writeln($region);
        }
    }
}
