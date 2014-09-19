<?php
namespace SmartData\Factory\CountryDatabase;

use GuzzleHttp\Client;
use SmartData\Factory\HtmlParser;
use DOMElement;
use Symfony\Component\Console\Output\OutputInterface;

class CountryListParser
{
    const SOURCE_URL = 'http://en.wikipedia.org/w/api.php?action=parse&page=List_of_sovereign_states&format=json';

    /**
     * @var array
     */
    private $countries = [];

    /**
     * @var array
     */
    private $exceptions = ['Other_states'];

    /**
     * @param OutputInterface $output
     * @return array
     */
    public function parseCountryListPage(OutputInterface $output)
    {
        $output->write('Getting list of countries', true);

        $client = new Client();
        /** @var \GuzzleHttp\Message\ResponseInterface $response */
        $response = $client->get(self::SOURCE_URL);
        $text = $response->json()['parse']['text']['*'];

        $htmlParser = new HtmlParser($text);
        $list = $htmlParser->find('*/table[1]/tr');
        foreach ($list as $row) {
            $countryId = $htmlParser->find($row, '*/span[@id]');
            if ($countryId->length) {
                $countryId = $countryId->item(0);
                /** @var DomElement $countryId */
                if (!in_array($countryId->getAttribute('id'), $this->exceptions)) {
                    $this->parseCountryRow($row);
                }
            }
        }

        $countryParser = new CountryParser();
        foreach ($this->countries as $key => $country) {
            $output->write('Searching for information on ' . $country['name'] . ': ');
            $country = $countryParser->parseCountryPage($country);
            if ($country) {
                $this->countries[$key] = $country;
                $output->write("<fg=green>DONE</fg=green>", true);
            } else {
                unset($this->countries[$key]);
                $output->write("<fg=red>ERROR</fg=red>", true);
            }
        }

        return $this->countries;
    }

    /**
     * @param DOMElement $row
     */
    public function parseCountryRow(DOMElement $row)
    {
        $htmlParser = new HtmlParser($row);
        /** @var DomElement $country */
        $country = $htmlParser->find('td[1]/*/a[1]')->item(0);

        $link = trim($country->getAttribute('href'));
        $name = trim($country->nodeValue);

        $this->countries[] = [
            'link' => $link,
            'name' => $name,
        ];
    }
}
