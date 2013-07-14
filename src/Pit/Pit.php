<?php
/**
 * Pit
 *
 * @url http://openpear.org/package/Spyc
 * @url http://code.google.com/p/spyc/
 * $ sudo pear install openpear/Spyc
 */

namespace Pit;

use Symfony\Component\Yaml\Yaml;

/**
 * Pit
 *
 */
class Pit
{
    const VERSION = '1.0.2';

    private $directory = '~/.pit/';
    private $config = 'pit.yaml';
    private $profile = 'default.yaml';

    /**
     * Pit
     *
     */
    public function __construct()
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

            $tfh = fopen($tfilename, 'w+');
            fwrite($tfh, $yaml);
            fclose($tfh);

            $ph = popen(getenv("EDITOR") . ' ' . $tfilename, 'w');
            pclose($ph);

            $result = file_get_contents($tfilename);

            if ($result == $config_data) {
                //No Changes
            }

            //yaml 2 array
            $result = Spyc::YAMLLoad($tfilename);
            unset($result[0]);
            unlink($tfilename);
        }

        //@todo implement load
        $config = $this->load();
        $config[$name] = $result;

        file_put_contents($this->directory . $this->profile, Yaml::dump($config));

        return $config[$name];
    }

    /**
     * get
     *
     */
    public function get($name, $options = array())
    {
        $load_data = $this->load();
        $result = $load_data[$name];

        if (!is_array($result)) {
            $result = array();
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

        file_put_contents($this->directory . $this->config, Yaml::dump($config));
    }

    public function load()
    {
        $config = $this->config();
        $this->switchProfile($config['profile']);
        return Yaml::parse($this->directory . $this->profile);
    }

    public function config()
    {
        return Yaml::parse($this->directory . $this->config);
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
