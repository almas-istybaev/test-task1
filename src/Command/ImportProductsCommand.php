<?php

namespace App\Command;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use JsonMachine\JsonMachine;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ImportProductsCommand extends Command
{
    protected static $defaultName = 'app:import-products';
    protected static $defaultDescription = 'Import products from json';
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

        $filePath = __DIR__ . "/../../public/load/products.json";
        $fileSize = filesize($filePath);
        $products = JsonMachine::fromFile($filePath);

        $io->writeln([
            'Products importing',
            '============',
            '',
        ]);

        $io->writeln('Start parsing file: ' . $filePath);
        $io->writeln('Products file size: ' . $fileSize . ' bites');

        foreach ($products as $product) {

            $newProduct = $this->makeProduct($product);

            $errors = $this->validator->validate($newProduct);

            if (count($errors) > 0) {
                $io->note('No valid product title: ' . ($product['title']));
                $io->section((string)$errors);
            } else {
                $this->em->persist($newProduct);
                $this->em->flush();
            }

            $io->info('Progress: ' . (int)($products->getPosition() / $fileSize * 100) . ' %');

        }

        $io->success('Yahoo!');

        return Command::SUCCESS;
    }

    /**
     * @param array $product
     * @return Product
     */
    public function makeProduct(array $product): Product
    {
        $newProduct = new Product();
        $newProduct->setEId($product['eId']);
        $newProduct->setTitle($product['title']);
        $newProduct->setPrice($product['price']);

        $categoriesEID = [];
        if(in_array('categoryEId', $product, true)) {
            $categoriesEID = (array)$product['categoryEId'];
        } else if(in_array('categoriesEId', $product, true)) {
            $categoriesEID = (array)$product['categoriesEId'];
        }

        if ($categoriesEID && count($categoriesEID) > 0) {
            $this->setCategory($newProduct, $categoriesEID);
        }

        return $newProduct;

    }

    /**
     * @param Product $newProduct
     * @param array $categoriesEID
     */
    public function setCategory(Product $newProduct, array $categoriesEID): void
    {
        $categories = $this->em->getRepository(Category::class)->findBy(['eId' => $categoriesEID]);
        if ($categories) {
            /** @var Category $category */
            foreach ($categories as $category) {
                $newProduct->addCategory($category);
            }
        }
    }
}
