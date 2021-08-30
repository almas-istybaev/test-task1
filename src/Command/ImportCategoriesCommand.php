<?php

namespace App\Command;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use JsonMachine\JsonMachine;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ImportCategoriesCommand extends Command
{
    protected static $defaultName = 'app:import-categories';
    protected static $defaultDescription = 'Import categories from json';
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(string $name = null, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->validator = $validator;
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filePath = __DIR__ . "/../../public/load/categories.json";
        $fileSize = filesize($filePath);
        $categories = JsonMachine::fromFile($filePath);

        $io->writeln([
            'Category importing',
            '============',
            '',
        ]);

        $io->writeln('Start parsing file: ' . $filePath);
        $io->writeln('categories file size: ' . $fileSize . ' bites');

        foreach ($categories as $category) {
            $newCategory = new Category();
            $newCategory->setEId($category['eId']);
            $newCategory->setTitle($category['title']);

            $errors = $this->validator->validate($newCategory);

            if (count($errors) > 0) {
                $io->note('No valid category title: ' . ($category['title']));
                $io->section((string)$errors);
            } else {
                $this->em->persist($newCategory);
                $this->em->flush();
            }

            $io->info('Progress: ' . (int)($categories->getPosition() / $fileSize * 100) . ' %');
        }

        $io->success('Yahoo!');

        return Command::SUCCESS;
    }
}
