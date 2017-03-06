<?php

include_once __DIR__ . '/../../../public/index.php';

$citiesFiles = (include __DIR__ . '/../config/module.config.php')['fixtures']['cities'];

foreach ($citiesFiles as $citiesFile) {

    // Format below
    // Country,City,AccentCity,Region,Population,Latitude,Longitude
    $file = new SplFileObject($citiesFile, 'r');

    $columns = $file->fgetcsv();
    /** @var \Application\Model\Image $image */
    $image = \Application\Module::entityManager()->getRepository(\Application\Model\Image::class)->findOneBy([]);

    /** @var \Application\Model\Area $areaAround */
    $areaAround = \Application\Module::entityManager()
        ->getRepository(\Application\Model\Area::class)
        ->find(1);

    while (true === $file->valid()) {
        $row = array_combine($columns, $file->fgetcsv());

        if ($row['Country'] !== 'de') {
            continue;
        }

        $employee = new \Application\Model\Employee();
        $employee->setName($row['City']);
        $employee->setSurname($row['Country']);
        $employee->setEmail('city@mail.com');
        $employee->setUpdated(new DateTime());
        $employee->setCreated(new DateTime());
        $employee->setHash(\Application\Model\Employee::hashKey());
        $employee->setImage($image);
        $employee->setJobStatus('active');

        $employee->setAreaAround($areaAround);
        $employee->setAddress($row['Region']);
        $employee->setZip(123123);

        \Application\Module::entityManager()->persist($employee);
        \Application\Module::entityManager()->flush();

        $coordinate = new \Application\Model\Coordinates();
        $coordinate->setEmployee($employee);
        $coordinate->setLatitude((float)$row['Latitude']);
        $coordinate->setLongitude((float)$row['Longitude']);

        \Application\Module::entityManager()->persist($coordinate);
        \Application\Module::entityManager()->flush();
    }
}