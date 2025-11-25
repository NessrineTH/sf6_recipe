<?php

namespace App\Command;

use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


#[AsCommand(
    name: 'eps:failed-email-alert',
    description: 'Envoi un mail si des messages sont présents dans la queue failed',
)]
class FailedEmailAlertCommand extends Command
{
    private $em;
    private $EmailService;

    public function __construct(EntityManagerInterface $em, EmailService $EmailService)
    {
        $this->em = $em;
        $this->EmailService = $EmailService;
        // you *must* call the parent constructor
        parent::__construct();
    }

    protected function configure(): void
    {
        // $this
        //     ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
        //     ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $sql = "SELECT COUNT(*) AS NB FROM sf_messenger_messages WHERE queue_name = 'failed'";
        $stmt = $this->em->getConnection()->prepare($sql);
        $result = $stmt->executeQuery();
        $nb = $result->fetchOne();

        if ($nb > 0) {
            $io->warning('Il y a ' . $nb . ' messages dans la queue failed');
            $this->EmailService->sendToTechnique('Il y a ' . $nb . ' messages dans la queue failed');

            //@TODO implémenter quelque chose similaire à la commande php bin/console messenger:failed:show qui affiche l'erreur en clair

        } else {
            $io->success('Il n\'y a pas de messages dans la queue failed');
        }

        return Command::SUCCESS;
    }
}
