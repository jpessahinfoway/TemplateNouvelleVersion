<?php


namespace App\Service;


class DotEnvParser
{

    /**
     * Read .env file and return array which will contain .env data
     *
     * @param string $env_path
     * @return array
     * @throws \Exception
     */
    public static function envToArray(string $env_path)
    {

        $array = [];

        if(file_exists($env_path))
        {

            $content = file_get_contents($env_path);

            $lines = explode(PHP_EOL, $content);

            foreach ($lines as $line)
            {

                // If not an empty line then parse line
                // and if line is not commented
                if($line !== "" AND strpos($line, '#') !== 0)
                {

                    // Get position of equals symbol
                    $equalsLocation = strpos($line, '=');

                    // Get everything to the left of the first equals
                    $key = substr($line, 0, $equalsLocation);

                    // Get everything to the right from the equals to end of the line
                    $value = substr($line, ($equalsLocation + 1), strlen($line));

                    $array[$key] = $value;

                }

            }

        }
        else
            throw new \Exception("Attempt to read '" . $env_path . "' but this file does not exist !");

        return $array;
    }

    /**
     * Write array content in .env
     *
     * @param array $array
     * @param string $env_path
     * @return bool
     * @throws \Exception
     */
    public static function arrayToEnv(array $array, string $env_path)
    {

        if(file_exists($env_path))
        {

            $env = "";
            $position = 0;
            foreach($array as $key => $value)
            {
                $position++;
                // If value isn't blank, or key isn't numeric meaning not a blank line, then add entry
                if($value !== "" || !is_numeric($key))
                {
                    // If passed in option is a boolean (true or false) this will normally
                    // save as 1 or 0. But we want to keep the value as words.
                    if(is_bool($value))
                    {
                        if($value === true)
                        {
                            $value = "true";
                        }
                        else
                        {
                            $value = "false";
                        }
                    }

                    // Always convert $key to uppercase
                    $env .= strtoupper($key) . "=" . $value;

                    // If isn't last item in array add new line to end
                    if($position != count($array))
                    {
                        $env .= "\n";
                    }
                }
                else
                {
                    $env .= "\n";
                }
            }

            if(file_put_contents($env_path, $env) !== false)
                return true;

            return false;

        }

        else
            throw new \Exception("Attempt to read '" . $env_path . "' but this file does not exist !");


    }

}