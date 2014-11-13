<?php
namespace SmartData\Factory\RegionDatabase\CountryRegionList;

class CountryRegionList
{
    /**
     * @var array
     */
    private $countries = [
        'us' => [
            'states' =>
                'http://en.wikipedia.org/w/api.php?action=query&titles=List_of_states_and_territories_of_the_United_States&prop=revisions&rvprop=content&rvsection=1&format=xml&continue',
            'federal_districts' =>
                'http://en.wikipedia.org/w/api.php?action=query&titles=List_of_states_and_territories_of_the_United_States&prop=revisions&rvprop=content&rvsection=2&format=xml&continue',
            'territories' =>
                'http://en.wikipedia.org/w/api.php?action=query&titles=List_of_states_and_territories_of_the_United_States&prop=revisions&rvprop=content&rvsection=3&format=xml&continue',
        ],
        'ca' => [
            'provinces' =>
                'http://en.wikipedia.org/w/api.php?action=query&titles=Provinces_and_territories_of_Canada&prop=revisions&rvprop=content&rvsection=2&format=xml&continue',
            'territories' =>
                'http://en.wikipedia.org/w/api.php?action=query&titles=Provinces_and_territories_of_Canada&prop=revisions&rvprop=content&rvsection=4&format=xml&continue'
        ]
    ];

    /**
     * @return array
     */
    public function getCountryRegionList()
    {
        return $this->countries;
    }
}
