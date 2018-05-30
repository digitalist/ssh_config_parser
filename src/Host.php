<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29.05.18
 * Time: 23:41
 */

namespace SSHParser;




class Host
{
    public $comments;
    public $hostnames;
    public $params;

    function __construct(array $comments=array(), array $hostnames=array(), array $params=array()){
        $this->comments=$comments;
        $this->hostnames=$hostnames;
        $this->params=$params;
    }

    function getParam(string $keyword){
        foreach ($this->params as $k=>$param){
            if ($param->$keyword == $keyword){
                return $param;
            }
        }
        return null;
    }

    function __toString()
    {
        $buf = array("\n");

        foreach ($this->comments as $k=>$comment){
            if ( substr($comment, 0, 1) !== "#") {
                $comment = "#".$comment;
            }
            $buf[]=$comment."\n";
        }


        $buf[] = SSHParser::$keywords['HostKeyword'] ."  ". implode(" ", $this->hostnames);
        print_r($this->hostnames);
        foreach ($this->params as $k=>$param){
            $buf[].="\t".$param;

            //$buf[] .= implode(" ", $param);
        }
        return implode("\n", $buf);
    }

    /**
     * @param Param $param
     */
    public function addParam(Param $param): void
    {
        $this->params[] = $param;
    }

};

//Go code


/* func NewHost(hostnames []string, comments []string) *Host {
 	return &Host{
 		Comments:  comments,
 		Hostnames: hostnames,
 	}
 }


*/
