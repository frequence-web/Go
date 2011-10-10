<?php

namespace Go\Deployer;

use \Go\Deployer\Deployer;

use \OOSSH\SSH2\Authentication\PasswordAuthentication;

/**
 * Your deployment instruction
 */
 
class Deploy extends Deployer
{
    public function preDeploy()
    {

    }

    public function postDeploy()
    {

    }

    public function preDeployProduction()
    {

    }

    public function postDeployProduction()
    {

    }

    public function getSshAuthentication()
    {
        /*
         * Warning : this is an example, you'd better to use \OOSSH\SSH2\Authentication\PublicKeyAuthentication !
         * Please refer to the OOSSH configuration : https://github.com/frequence-web/OOSSH
         */
        return new PasswordAuthentication('deploy', 'deploy');
    }
}
