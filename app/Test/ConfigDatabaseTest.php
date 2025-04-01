<?php

use PHPUnit\Framework\TestCase;
use app\Config\ConfigDatabase;
require_once __DIR__ . '/../../config/ConfigDatabase.php';

class ConfigDatabaseTest extends TestCase 
{
    public function testGetConnectionString() 
    {
        $config = new ConfigDatabase('monserveur', 'mabase');
        
        $this->assertEquals('mysql:host=monserveur;dbname=mabase', $config->getConnectionString());
    }
    public function testGetters() 
    {
        $config = new ConfigDatabase('monserveur', 'mabase', 'utilisateur', 'motdepasse');
        
        $this->assertEquals('utilisateur', $config->getUsername());
        $this->assertEquals('motdepasse', $config->getPassword());
    }
    
    public function testOptions() 
    {
        $config = new ConfigDatabase();
        $options = $config->getOptions();
        
        $this->assertArrayHasKey(\PDO::ATTR_ERRMODE, $options);
        $this->assertEquals(\PDO::ERRMODE_EXCEPTION, $options[\PDO::ATTR_ERRMODE]);
    }
    
    public function testConstructeurPersonnalise() 
    {
        $options = [\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC];
        $config = new ConfigDatabase('serveur2', 'db2', 'user2', 'pass2', $options);
        
        $this->assertEquals('serveur2;dbname=db2', substr($config->getConnectionString(), 11));
        $this->assertEquals('user2', $config->getUsername());
        $this->assertEquals('pass2', $config->getPassword());
        
        $optionsResultat = $config->getOptions();
        $this->assertArrayHasKey(\PDO::ATTR_DEFAULT_FETCH_MODE, $optionsResultat);
        $this->assertEquals(\PDO::FETCH_ASSOC, $optionsResultat[\PDO::ATTR_DEFAULT_FETCH_MODE]);
    }
}
