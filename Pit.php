<?php
/**
 * Pit
 *
 * @url http://openpear.org/package/Spyc
 * @url http://code.google.com/p/spyc/
 *
 * $ sudo pear install openpear/Spyc 
 */

require_once './vendor/Symfony/Component/Yaml/Yaml.php';
require_once './vendor/Symfony/Component/Yaml/Parser.php';
require_once './vendor/Symfony/Component/Yaml/Inline.php';

use Symfony\Component\Yaml\Yaml;

//$array = Yaml::parse($file);
//print Yaml::dump($array);

require_once 'spyc.php';

class Pit
{
    private $directory = '~/.pit/';
    private $config = 'pit.yaml';
    private $profile = 'default.yaml';

    /**
     * Pit
     *
     */
    public function Pit()
    {
        if (strpos($this->directory, '~') === 0) {
            $this->directory = str_replace('~', getenv('HOME'), $this->directory);
        }

        if (!is_dir($this->directory)) {
            mkdir($this->directory);
        }
        
        if (!is_file($this->directory . $this->config)) {
            touch($this->directory . $this->config);
            chmod($this->directory . $this->config, 0600);
        }
        
        if (!is_file($this->directory . $this->profile)) {
            touch($this->directory . $this->profile);
            chmod($this->directory . $this->profile, 0600);
        }
    }

    /**
     * set
     *
     */
    public function set($name, $options = array())
    {
        if (isset($options['data'])) {
            $result = $options['data'];
        } else {
            if (isset($options['config'])) {
                $yaml = Spyc::YAMLDump($options['config']);
            } else {
                $config_data = $this->get($name);
                var_dump($config_data);exit();
                $yaml = Spyc::YAMLDump($config_data);
            }

            $tfilename = tempnam(sys_get_temp_dir(), 'pit');

            $yaml = file_get_contents($tfilename) . $yaml; 
            file_put_contents($tfilename, $yaml); 

            $ph = popen(getenv("EDITOR") . ' ' . $tfilename, 'w');
            pclose($ph);

            $result = Spyc::YAMLLoad($tfilename);
            unset($result[0]);
            unlink($tfilename);
        }

        //@todo implement load
        $config = $this->load();
        $config[$name] = $result;

        file_put_contents($this->directory . $this->profile, Spyc::YAMLDump($config));

        return $config[$name];
    }

    /**
     * get
     *
     */
    public function get($name, $options = array())
    {
        $load_data = $this->load();

        $result = array(); 
        if (isset($load_data[$name])) {
            $result = $load_data[$name];
        }

        if ((count($result) == 0) && isset($options['require'])) {
            foreach ($options['require'] as $key => $item) {
                if (isset($result[$key])) {
                } else {
                    $result[$key] = $item;
                }
            }
            $result = $this->set($name, array('config' => $result));
        }

        if (count($result) == 0) {
            return array('username' => '', 'password' => '');
        } else {
            return $result;
        }
    }

    public function switchProfile($name, $options = array())
    {
        $this->profile = $name . '.yaml';
        $config = $this->config();
        $config['profile'] = $name;

        file_put_contents($this->directory . $this->config, Spyc::YAMLDump($config));
    }

    public function load()
    {
        $config = $this->config();
        $this->switchProfile($config['profile']);
        $array = Yaml::parse($this->directory . $this->profile);
        var_dump($array); 
        exit;
        unset($array[0]);
        return $array;
    }

    public function config()
    {
        $yaml = Spyc::YAMLLoad($this->directory . $this->config);
        unset($yaml[0]);
        return $yaml;
    }

}

/*
$pit = new Pit();
$re = $pit->get('none', array('require' =>
    array(
        'mail' => 'your mail',
        'pass' => 'your pass'
    )
));
var_dump($re);
 */
?>
