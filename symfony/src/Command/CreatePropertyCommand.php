<?php

namespace App\Command;

use App\Entity\Product;
use App\Entity\Property;
use App\Entity\PropertyType;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreatePropertyCommand extends Command
{
    protected static $defaultName = 'property:create';

    private $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->container = $container;
    }

    protected function configure()
    {
        $this
            ->setDescription('Creates a new property.')
            ->setHelp('This command allows you to create a new property for a selected product')
            ->addOption(
                'product',
                'p',
                InputOption::VALUE_REQUIRED,
                'The product to add the property to.',
                null
            )
            ->addOption(
                'name',
                null,
                InputOption::VALUE_REQUIRED,
                'The property name.',
                null
            )
            ->addOption(
                'description',
                'd',
                InputOption::VALUE_REQUIRED,
                'The property description.',
                null
            )
            ->addOption(
                'type',
                't',
                InputOption::VALUE_REQUIRED,
                'The property type.',
                null
            )
            ->addOption(
                'value',
                null,
                InputOption::VALUE_REQUIRED,
                'The property val.',
                null
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Create a new property');

        $em = $this->container->get('doctrine')->getManager();

        $product = $input->getOption('product');
        if (null === $product) {

            $products = $em->getRepository(Product::class)->findAll();
            foreach ($products as $product) {
                $choices[$product->getId()] = $product->getName();
            }

            $product = $io->choice('Please select the product to add the property to.', $choices);
            unset($choices);
        }

        if (is_string($product)) {
            $product = $em->getRepository(Product::class)->findOneBy(array('name' => $product));
        } else if (is_int($product)) {
            $product = $em->getRepository(Product::class)->findOneBy(array('id' => $product));
        }

        if (null === $product) {
            $io->error('Could not find product');
            exit();
        }

        $name = $input->getOption('name');
        if (null === $name) {
            $name = $io->ask('Please enter the name of the property', 'My product');
        }

        $description = $input->getOption('description');
        if (null === $description) {
            $description = $io->ask('Please enter the description of the property', '');
        }

        $type = $input->getOption('type');
        if (null === $type) {

            $types = $em->getRepository(PropertyType::class)->findAll();
            foreach ($types as $type) {
                $choices[$type->getId()] = $type->getName();
            }

            $type = $io->choice('Please select the property type.', $choices);
        }

        if (is_string($type)) {
            $type = $em->getRepository(PropertyType::class)->findOneBy(array('name' => $type));
        } else if (is_int($type)) {
            $type = $em->getRepository(PropertyType::class)->findOneBy(array('id' => $type));
        }

        if (null === $product) {
            $type->error('Could not find type');
            exit();
        }

        $value = $input->getOption('value');
        if (null === $value) {
            $value = $io->ask('Please enter the value of the property', '');
        }

        $property = new Property();
        $property->setName($name)
            ->setDescription($description)
            ->setType($type)
            ->setValue($value);

        $em->persist($property);

        /** @var Product $product */
        $product->addProperty($property);

        $em->flush();

        $io->success('Property created successfully!');
    }
}