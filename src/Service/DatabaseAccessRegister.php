<?php


namespace App\Service;


use App\Entity\Main\Enseignes;
use Symfony\Component\HttpFoundation\File\Exception\CannotWriteFileException;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\ORM\EntityManagerInterface;

class DatabaseAccessRegister
{

    /**
     * @var ParameterBagInterface
     */
    private  $parameterBagInterface;

    /**
     * @var EntityManagerInterface
     */
    private  $entityManagerInterface;

    public function __construct(ParameterBagInterface $parameterBagInterface, EntityManagerInterface $entityManagerInterface)
    {
        $this->parameterBagInterface = $parameterBagInterface;
        $this->entityManagerInterface = $entityManagerInterface;
    }

    public  function load()
    {

        try
        {

            $this->loadAllEnseignesDatabases();

            $env = DotEnvParser::envToArray(($this->parameterBagInterface->get('env_file_path')));

            $database_connections = Yaml::parse(file_get_contents($this->parameterBagInterface->get('database_connections_file_path')));

            //dd($database_connections, $env);

            foreach ($database_connections['ENSEIGNES'] as &$database)
            {

                // if yaml file say database is not copied in .env
                if(!$database['is_copied'])
                {

                    // if database info is already copied in .env
                    // update yaml file
                    if(array_key_exists(strtoupper($database['database_name'] . '_DATABASE_URL'), $env) OR
                        ( is_numeric($database['database_name'][0]) !== false AND array_key_exists(strtoupper(
                            $this->replaceNumberByCardinal(intval($database['database_name'][0])) . substr($database['database_name'], 1) . '_DATABASE_URL'), $env)))
                    {
                        $file_content = file_get_contents($this->parameterBagInterface->get('env_file_path'));
                        $database['is_copied'] = true;
                    }

                    // if database info is not copied in .env
                    else
                    {

                        // check if the first character is number
                        // if yes, change it, because of .env don't want it in first position of variable name
                        if(is_numeric($database['database_name'][0]) !== false)
                        {

                            if(!$this->replaceNumberByCardinal($database['database_name'][0]))
                                throw new \Exception("Error : Attempt to get cardinal value of '" . $database['database_name'][0] . "' but its cardinal value cannot be found !");

                            $temp = $this->replaceNumberByCardinal(intval($database['database_name'][0])) . substr($database['database_name'], 1);

                            $url = strtoupper($temp . '_DATABASE_URL') . "=" . $database_connections['SERVER_URL'] . $database['database_name'];
                        }

                        else
                          $url = strtoupper($database['database_name'] . '_DATABASE_URL') . "=" . $database_connections['SERVER_URL'] . $database['database_name'];

                        $database['is_copied'] = true;
                        //$database['url'] = str_replace(strtoupper(($temp ?? $database['database_name']) . '_DATABASE_URL') . "=", null, $url);
                        $database['url'] = substr($url, strpos($url,"=" ) +1);

                        if(!is_readable($this->parameterBagInterface->get('env_file_path')))
                            throw new \Exception("Error : The file '" . $this->parameterBagInterface->get('env_file_path') . "' is not readable !");

                        $file_content = file_get_contents($this->parameterBagInterface->get('env_file_path'));

                        $file_content .= PHP_EOL . $url;
                    }

                    if(!is_writable($this->parameterBagInterface->get('env_file_path')))
                        throw new CannotWriteFileException("Error : The file '" . $this->parameterBagInterface->get('env_file_path') . "' is not writable !");

                    file_put_contents($this->parameterBagInterface->get('env_file_path'), $file_content);
                    //dump($file_content);

                }

            }

            //dd(Yaml::dump($database_connections, 10));

            if(!file_exists($this->parameterBagInterface->get('database_connections_file_path')))
                throw new \Exception("Error : The file '" . $this->parameterBagInterface->get('database_connections_file_path') . "' not exist !");

            if(!is_writable($this->parameterBagInterface->get('database_connections_file_path')))
                throw new CannotWriteFileException("Error : The file '" . $this->parameterBagInterface->get('database_connections_file_path') . "' not writable !");

            file_put_contents($this->parameterBagInterface->get('database_connections_file_path'), Yaml::dump($database_connections, 10));

            //$env = DotEnvParser::envToArray(($this->parameterBagInterface->get('env_file_path')));

            //dd($database_connections, $env);

        }
        catch (\Exception $e)
        {
            //dd($e->getMessage());
            throw new \Exception($e->getMessage());
        }

    }


    /**
     * @return array
     */
    public function getAllDatabases(): array
    {
        $database_connections = Yaml::parse(file_get_contents($this->parameterBagInterface->get('database_connections_file_path')));

        return $database_connections['ENSEIGNES'];
    }


    private function loadAllEnseignesDatabases()
    {

        $database_connections = Yaml::parse(file_get_contents($this->parameterBagInterface->get('database_connections_file_path')));

        $doctrine = Yaml::parse(file_get_contents($this->parameterBagInterface->get('doctrine_file_path')));

        foreach ($this->entityManagerInterface->getRepository(Enseignes::class)->findAll() as $enseigne)
        {

            if($database_connections['ENSEIGNES'] !== null)
            {

                if(!array_key_exists($enseigne->getEnseigne(), $database_connections['ENSEIGNES']))
                {

                    $database_connections['ENSEIGNES'][$enseigne->getEnseigne()] = [
                        'database_name' => $enseigne->getBase(),
                        'is_copied' => false,
                        'url' => null
                    ];

                }

            }

            else
            {

                $database_connections['ENSEIGNES'][$enseigne->getEnseigne()] = [
                    'database_name' => $enseigne->getBase(),
                    'is_copied' => false,
                    'url' => null
                ];

            }


            if(!array_key_exists($enseigne->getBase(), $doctrine['doctrine']['dbal']['connections']))
            {

                if(is_numeric($enseigne->getBase()[0]) !== false)
                    $temp = $this->replaceNumberByCardinal(intval($enseigne->getBase()[0])) . substr($enseigne->getBase(), 1);

                $doctrine['doctrine']['dbal']['connections'][$enseigne->getBase()] = [
                    'url' => '%env(resolve:' . strtoupper($temp ?? $enseigne->getBase()) . '_DATABASE_URL)%',
                    'driver' => 'pdo_mysql',
                    'server_version' => '5.7',
                    'charset' => 'utf8mb4'
                ];

                if(isset($temp))
                    unset($temp);


            }


            if(!array_key_exists($enseigne->getBase(), $doctrine['doctrine']['orm']['entity_managers']))
            {

                if(!file_exists($this->parameterBagInterface->get('kernel.project_dir') . "/src/Entity/OldApp"))
                    mkdir($this->parameterBagInterface->get('kernel.project_dir') . "/src/Entity/OldApp", 0700);

                $doctrine['doctrine']['orm']['entity_managers'][$enseigne->getBase()] = [
                    'connection' => $enseigne->getBase(),
                    'mappings' => [
                        $enseigne->getEnseigne() => [
                            'is_bundle' => false,
                            'type' => 'annotation',
                            'dir' => '%kernel.project_dir%/src/Entity/OldApp',
                            'prefix' => 'App\Entity\OldApp',
                            'alias' =>  ucfirst($enseigne->getBase())
                        ]
                    ]

                ];

            }


        }

        //dd($database_connections, $doctrine);

        if(!file_exists($this->parameterBagInterface->get('doctrine_file_path')))
            throw new \Exception("Error : '" . $this->parameterBagInterface->get('doctrine_file_path') . "' file not exist !");

        if(!file_exists($this->parameterBagInterface->get('database_connections_file_path')))
            throw new \Exception("Error : '" . $this->parameterBagInterface->get('database_connections_file_path') . "' file not exist !");



        if(!is_writable($this->parameterBagInterface->get('doctrine_file_path')))
            throw new \Exception("Error : The file '" . $this->parameterBagInterface->get('doctrine_file_path') . "' is not writable !");

        if(file_put_contents($this->parameterBagInterface->get('doctrine_file_path'), Yaml::dump($doctrine, 10)) === false)
            throw new \Exception("Error : An error occurred during writing in '" . $this->parameterBagInterface->get('doctrine_file_path') . "' file !");



        if(!is_writable($this->parameterBagInterface->get('database_connections_file_path')))
            throw new \Exception("Error : The file '" . $this->parameterBagInterface->get('database_connections_file_path') . "' is not writable !");

        if(file_put_contents($this->parameterBagInterface->get('database_connections_file_path'), Yaml::dump($database_connections, 10)) === false)
            throw new \Exception("Error : An error occurred during writing in '" . $this->parameterBagInterface->get('database_connections_file_path') . "' file !");


    }


    /**
     * @param int $number
     * @return bool|string
     */
    private function replaceNumberByCardinal($number)
    {

        $number_to_cardinal = [
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten'
        ];

        if(array_key_exists($number, $number_to_cardinal))
            return $number_to_cardinal[$number];

        return false;

    }

}