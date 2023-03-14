<?php

namespace App\Command;

use Exception;
use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Pimcore\Model\DataObject\Country;
use Pimcore\Model\DataObject\Movie;
use Pimcore\Model\DataObject\FilmDistributor as Distributor;
use NormanHuth\HelpersPimcore\DataObject;

class ImportMoviesCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('import:movies')
            ->setDescription('Import movies');
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $reader->setDelimiter(';');

        $spreadsheet = $reader->load(base_path('storage/movies.csv'));
        $items = $spreadsheet
            ->getSheet(0)
            ->toArray(null, true, true, true);
        unset($items[array_key_first($items)]);

        foreach ($items as $item) {
            $item = array_map('trim', $item);
            $title = $item['A'];
            $titleDe = $item['B'];
            $year = $item['C'];
            $countryCodes = $item['D'];
            $distributorName = $item['E'];

            $movie = Movie::getByPath('/Movies/'.$year.'/'.$distributorName.'/'.$title);
            $folder = DataObject::getFolder('Movies/'.$year.'/'.$distributorName);
            if (!$movie) {
                $movie = new Movie();
                $movie->setParentId($folder->getId());
            }

            $countries = [];
            foreach (explode(';', $countryCodes) as $code) {
                $code = trim($code);
                $country = Country::getByPath('/Countries/'.$code);
                $countries[] = $country;
            }

            $distributor = Distributor::getByPath('/Film Distributor/'.$distributorName);
            if (!$distributor) {
                $distributorFolder = DataObject::getFolder('Film Distributor');
                $distributor = (new Distributor())
                    ->setKey($distributorName)
                    ->setName($distributorName)
                    ->setParentId($distributorFolder->getId())
                    ->setPublished(true)
                    ->save();
            }

            $movie
                ->setKey($title)
                ->setTitle($title, 'en')
                ->setTitle($titleDe, 'de')
                ->setPublished(true)
                ->setParentId($folder->getId())
                ->setCountries($countries)
                ->setDistributor([$distributor])
                ->setYear($year);
            $movie->save();
        }

        return Command::SUCCESS;
    }
}
