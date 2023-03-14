<?php

namespace App\Command;

use Exception;
use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Pimcore\Model\DataObject\Country;
use NormanHuth\CountryList\Helper;
use NormanHuth\HelpersPimcore\DataObject;

class ImportCountriesCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('import:countries')
            ->setDescription('Import Countries');
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $english = Helper::get('en');
        $german = Helper::get('de');

        $folder = DataObject::getFolder('Countries');

        foreach ($english as $code => $countryName) {
            $country = Country::getByPath('/Countries/'.$code);
            if (!$country) {
                $country = new Country();
            }

            $country
                ->setKey($code)
                ->setName($countryName, 'en')
                ->setName($german[$code], 'de')
                ->setPublished(true)
                ->setParentId($folder->getId());

            $country->save();
        }

        return Command::SUCCESS;
    }
}
