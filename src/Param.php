<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29.05.18
 * Time: 23:41
 */

namespace SSHParser;


class Param
{
    public $comments;
    public $keyword;
    public $args;


    function __construct(array $comments=array(), string $keyword='', array $args=array()){
        $this->comments=$comments;
        $this->keyword=$keyword;
        $this->args=$args;
    }

    function __toString()
    {
        $buf = array();
        if (count($this->comments)){
            $buf[]="\n";
        }

        foreach ($this->comments as $k=>$comment){
            if ( substr($comment, 0, 1) !== "#") {
                $comment = "#".$comment;
            }
            $buf[]=$comment."\n";
        }

        $buf[]= $this->keyword . " " . implode(" ", $this->args);
        return implode("\n", $buf);
    }

    /**
     * @return string
     */
    public function getKeyword(): string
    {
        return $this->keyword;
    }

    /**
     * @return array
     */
    public function getComments(): array
    {
        return $this->comments;
    }

    /**
     * @param string $comment
     */
    public function addComment(string $comment){
        $this->comments[]=$comment;
    }

    /**
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * @param string $arg
     */
    public function addArg(string $arg): void
    {
        $this->args[]=$arg;
    }

    /**
     * @param string $keyword
     */
    public function setKeyword(string $keyword): void
    {
        $this->keyword = $keyword;
    }
};

//Go code
/*
 func (param *Param) String() string {

 	buf := &bytes.Buffer{}

 	if len(param.Comments) > 0 {
 		fmt.Fprintln(buf)
 		for _, comment := range param.Comments {
 			if !strings.HasPrefix(comment, "#") {
 				comment = "# " + comment
 			}
 			fmt.Fprintln(buf, comment)
 		}
 	}

 	fmt.Fprintf(buf, "%s %s\n", param.Keyword, strings.Join(param.Args, " "))

 	return buf.String()

 }


func NewParam(keyword string, args []string, comments []string) *Param {
 	return &Param{
 		Comments: comments,
 		Keyword:  keyword,
 		Args:     args,
 	}
 }



 func (param *Param) Value() string {
 	if len(param.Args) > 0 {
 		return param.Args[0]
 	}
 	return ""
 }*/