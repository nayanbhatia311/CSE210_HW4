<?php
$Reserved_Keywords = [
        "if" => new Token("if","if"),
        "then" => new Token("then","then"),
        "else" => new Token("else","else"),
        "while" => new Token("while","while"),
        "do" => new Token("do","do"),
        "true" => new Token("true","true"),
        "false" => new Token("false", "false"),
        "skip" => new Token("skip", "skip")
    ];
class Constants{
    const INTEGER = "INTEGER";
    const PLUS = "PLUS";
    const MINUS = "MINUS";
    const MUL = "MUL";
    const DIV = "DIV";
    const LPAREN = "LPAREN";
    const RPAREN = "RPAREN";
    const ID = "ID";
    const ASSIGN = "ASSIGN";
    const SEMI = "SEMI";
    const EOF = "EOF";
    const EQUAL = "EQUAL";
    const LESSTHAN = "LESSTHAN";
    const GREATERTHAN = "GREATERTHAN";
    const AND = "AND";
    const OR = "OR";
    const NOT = "NOT";
    const IF = "IF";
    const THEN = "THEN";
    const ELSE = "ELSE"; 
    const LBRACE = "LBRACE";
    const RBRACE = "RBRACE";
    const WHILE = "WHILE";
    const DO = "DO";
    const TRUE = "TRUE";
    const FALSE = "FALSE";
    const SKIP = "SKIP";
}

class Token{
    public $type;
    public $value;
    function __construct($type, $value) {
        $this->type=$type;
        $this->value=$value;
    }

    public function toprint() {
        echo "Token(".$this->type.",".$this->value.")";
    }
}

class Lexer {
    public $text;
    public $state;
    public $pos;
    public $current_char;
    
    function __construct($text) {
        $this->text=$text; 
        $this->state=[];
        $this->pos=0;
        $this->current_char=substr($this->text,$this->pos);
    }

    function error() {
        throw new Exception('Invalid syntax');
    }

    function advance(){
        $this->pos +=1;
        if($this->pos > (strlen($this->text)-1) ){
            $this->current_char = NULL;
        }
        else{
            $this->current_char=substr($this->text,$this->pos);
        }
    }

    function peek(){
        $peek_pos= $this->pos + 1;
        if($peek_pos > strlen($this->text)-1){
            return NULL;
        }
        else{
            return substr($this->text,$this->pos);
        }
    }

    function skip_whitespace(){
        while($this->current_char!=NULL && IntlChar::isspace($this->current_char)){
            $this->advance();

        }
    }

    function integer(){
        $result="";
        while($this->current_char!=NULL && IntlChar::isdigit($this->current_char)){
            $result=$result.$this->current_char;
            $this->advance(); 
        }
        return (int)result;
    }
    function get(&$var, $default=null) {
        return isset($var) ? $var : $default;
    }

    function _id(){
        $result="";
        while($this->current_char!=NULL && ctype_alnum($this->current_char)){
            $result=$result.$this.current_char;
            $this->advance();
        }
        $token= get($Reserved_Keywords[result],new Token(Constants::ID,result));
        return $token;
    }

    function get_next_token(){
        while($this->current_char!=NULL){
            if(IntlChar::isspace($this->current_char)){
                $this->skip_whitespace();
                continue;
            }

            if(ctype_alnum($this->current_char)){
                return $this->_id();
            }

            if(IntlChar::isdigit($this->current_char)){
                return new Token(Constants::INTEGER,$this->integer());
            }
            if($this->current_char == ":" && $this->peek() == "="){
                $this->advance();
                $this->advance();
                return new Token(Constants::ASSIGN,":=");
            }
            if($this->current_char == ";"){
                $this.advance();
                return new Token(Constants::SEMI,";");
            }
            if($this->current_char=="+"){
                $this.advance();
                return new Token(Constants::PLUS,"+");
            }
            if($this->current_char=="-"){
                $this.advance();
                return new Token(Constants::MINUS,"-");
            }
            if($this->current_char=="*"){
                $this.advance();
                return new Token(Constants::MUL,"*");
            }
            if($this->current_char=="/"){
                $this.advance();
                return new Token(Constants::DIV,"/");
            }
            if($this->current_char=="("){
                $this.advance();
                return new Token(Constants::LPAREN,"(");
            }
            if($this->current_char==")"){
                $this.advance();
                return new Token(Constants::RPAREN,")");
            }
            if($this->current_char=="="){
                $this.advance();
                return new Token(Constants::EQUAL,"=");
            }
            if($this->current_char=="<"){
                $this.advance();
                return new Token(Constants::LESSTHAN,"<");
            }
            if($this->current_char==">"){
                $this.advance();
                return new Token(Constants::GREATERTHAN,">");
            }
            if($this->current_char=="∧"){
                $this.advance();
                return new Token(Constants::AND,"∧");
            }
            if($this->current_char=="∨"){
                $this.advance();
                return new Token(Constants::AND,"∨");
            }
            if($this->current_char=="¬"){
                $this.advance();
                return new Token(Constants::NOT,"¬");
            }
            if($this->current_char=="{"){
                $this.advance();
                return new Token(Constants::LBRACE,"{");
            }
            if($this->current_char=="}"){
                $this.advance();
                return new Token(Constants::RBRACE,"}");
            }

            $this->error();
        }

        return new Token(Constants::EOF, NULL);
    }

}
// echo get($Reserved_Keywords['if'],'nope')->toprint();
// $foo= new Token("asd","adda");
// $foo->toprint();

// echo $Reserved_Keywords["if"]->toprint();

?>