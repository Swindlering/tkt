<?php

namespace App\DataFixtures;

use App\Entity\Resultat;
use App\Entity\Society;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class SocietyAndResultFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $filename = './src/DataFixtures/data/mock_data.json';

        $aData = $this->getDataFronJsonFile($filename);
        foreach ($aData as $value) {
            $society = new Society();

            $society->setName($value->name)
                ->setSiren($value->siren)
                ->setSector($value->sector);
            // save the new society before insert result 
            // there is a constraint
            $manager->persist($society);
            $manager->flush();
            foreach ($value->results as $res) {
                $result = new Resultat();
                $result->setCa($res->ca)
                    ->setMargin($res->margin)
                    ->setEbitda($res->ebitda)
                    ->setLoss($res->loss)
                    ->setYear($res->year)
                    ->setSociety($society);
                $manager->persist($result);
                $manager->flush();
                $society->addResults($result);
            }
            $manager->persist($society);
            $manager->flush();
        }
    }
    
    /***
     * JSON read from file
     */
    private function getDataFronJsonFile($filename): ?array
    {
        $data = file_get_contents($filename);
        return json_decode($data);
    }
}
