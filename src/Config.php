<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.05.18
 * Time: 0:51
 */

namespace SSHParser;


class HostException extends \Exception{

}

class HostNotFoundException extends HostException {

}



class Config
{
    public $source;
    public $globals;
    public $hosts;

    function __construct(string $soruce, array $globals=array(), array $hosts=array()){
        $this->source=$soruce;
        $this->globals=$globals;
        $this->hosts=$hosts;
    }

    function getSource(){
        return $this->source;
    }
    function __toString()
    {

        //todo: implement
        return "";

    }


    /**
     * @param string $hostname
     * @return Host
     * @throws HostNotFoundException
     */
    function findByHostname(string $hostname): Host{
        foreach ($this->hosts as $k=>$host ){
            foreach ($host['hostnames'] as $hostnameIndex=>$currentHostname){
                if ($currentHostname == $hostname) {
                    return $host;
                }
            }
            // проверим алиасы
            if ($host->getParam(SSHParser::$keywords['HostNameKeyword'])){
                foreach ($host->hostnames as $hostnameIndex=>$currentHostname){
                    if ($currentHostname == $hostname){
                        return $host;
                    }
                }
            }
        }


        throw new HostNotFoundException("Host not found");
    }



// func (config *Config) GetParam(keyword string) *Param {
// 	for _, param := range config.Globals {
// 		if param.Keyword == keyword {
// 			return param
// 		}
// 	}
// 	return nil
// }

// func (config *Config) GetHost(hostname string) *Host {
// 	for _, host := range config.Hosts {
// 		for _, hn := range host.Hostnames {
// 			if hn == hostname {
// 				return host
// 			}
// 		}
// 	}
// 	return nil
// }


}