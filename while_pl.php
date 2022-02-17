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

class AST{

}

class BinOp extends AST{
    public $left;
    public $token;
    public $op;
    public $right;

    function __construct($left,$op,$right) {
        $this->op=$op;
        $this->token=$token;
        $this->left=$left;
        $this->right=$right;
    }
}

class Num extends AST{
    public $left;
    public $token;
    public $right;
    function __construct($token) {
        $this->op=$token->type;
        $this->value=$token->value;
    }
}

class Compound extends AST{
    public $children;
    function __construct(){
        $this->children=[];
    }
}

class Assign extends AST{
    public $left;
    public $op;
    public $token;
    public $right;
    function __construct($left,$op,$right){
        $this->left=$left;
        $this->token=$op;
        $this->op=$op;
        $this->right=$right;
    }
}

class Variable extends AST{
    public $op;
    public $value;
    function __construct($token){
        $this->op=$token->type;
        $this->value=$token->value;
    }
}

class Boolean extends AST{
    public $value;
    public $type;
    function __construct($token){
        $this->value=$token->value;
        $this->op=$token->type;
    }
}

class Semi extends AST{
    public $left;
    public $right;
    public $op;
    function __construct($left,$right,$op){
        $this->left=$left;
        $this->right=$right;
        $this->op=$op;
    }
}

class Not extends AST{
    public $op;
    public $ap;

    function __construct($node){
        $this->op="NOT";
        $this->ap=node;
    }
}

class BoolOp extends AST{
    public $left;
    public $right;
    public $op;

    function __construct($left,$right,$op){
        $this->left=$left;
        $this->right=$right;
        $this->op=$op;
    }
}

class Skip extends AST{
    public $type;
    public $value;
    function __construct($token){
        $this->value=$token->value;
        $this->op=$token->type;
    }
}

class NoOp extends AST{

}

class If_condition extends AST{
    public $if_true;
    public $if_false;
    public $condition;
    public $op;
    function __construct($condition,$if_true,$if_false){
        $this->condition=$condition;
        $this->if_true=$if_true;
        $this->if_false=$if_false;
        $this->op="if";
    }
}

class While_condition extends AST{
    public $condition;
    public $while_true;
    public $while_false;
    function __construct($condition,$while_true,$while_false){
        $this->condition=$condition;
        $this->while_true=$while_true;
        $this->while_false=$while_false;
        $this->op="while";
    }
}

class Parser{
    public $lexer;
    public $state;
    public $current_token;
    function __construct(lexer){
        $this->lexer=$lexer;
        $this->state=$lexer->state;
        $this->current_token=$lexer->get_next_token();
    }
    function error() {
        throw new Exception('Invalid syntax');
    }
    function factor(){
        public $token = $this->current_token;
        public $node;
        if($token->type==Constants::MINUS){
            $this->current_token = $this->lexer->get_next_token();
            $token=$this->current_token;
            $token->value= -1*token->value;
            $node= new Num($token);
        }
        else if($token->type==Constants::INTEGER){
            $node= new Num($token);
        }
        else if($token->type==Constants::ID){
            $node=new Variable(token);
        }
        else if($token->type==Constants::NOT){
            $this->current_token=$this->lexer->get_next_token();
            if($this.current_token==Constants::LPAREN){
                $this->current_token=$this->lexer->get_next_token();
                $node=$this->boolean_expression();
            }
            else if($this->current_token->type==Constants::TRUE || $this->current_token->type==Constants::FALSE){
                $node=new Boolean(token);
            }
            else{
                $this->error();
            }
            $node=new Not(node);
        }
        else if($token->type==Constants::TRUE || $token->type==Constants::FALSE){
            $node=new Boolean(token);
        }
        else if($token->type==Constants::LPAREN){
            $this->current_token=$this->lexer->get_next_token();
            $node=$this->boolean_expression();
        }
        else if($token->type==Constants::RPAREN){
            $this->current_token=$this->lexer->get_next_token();
        }
        else if($token->type==Constants::LBRACE){
            $this->current_token=$this->lexer->get_next_token();
            $node=$this->statement_expression();
        }
        else if($token->type==Constants::SKIP){
            $node=new Skip(token);
        }
        else if($token->type==Constants::While){
            $this->current_token=$this->lexer->get_next_token();
            $condition=$this->boolean_expression();
            $while_false=new Skip(new Token('skip','skip'));
            if($this->current_token->type==Constants::DO){
                $this->current_token=$this->lexer->get_next_token();
                if($this->current_token==Constants::LBRACE){
                    $while_true=$this->statement_expression();
                }
                else{
                    $while_true=$this->statement_term();
                }
            }
            return new While($condition,$while_true,$while_false);
        }
        else if($token->type==Constants::IF){
            $this->current_token=$this->lexer->get_next_token();
            $condition=$this->boolean_expression();
            if($this->current_token->type==Constants::THEN){
                $this->current_token=$this->lexer->get_next_token();
                $if_true=$this->statement_expression();
            }
            if($this->current_token->type==Constants::ELSE){
                $this->current_token=$this->lexer->get_next_token();
                $if_false=$this->statement_expression();
            }
            return new If_condition($condition,$if_true,$if_false);

        }

        else {
            $this->syntax_error();
        }

        $this->current_token=$this->lexer->get_next_token();
        return $node;
    }

    function arith_term(){
        $node=$this->factor();
        while($this->current_token->type==Constants::MUL){
            $type_name=$this->current_token->Type;
            $this->current_token=$this->lexer->get_next_token();
            $node=new BinOp($node,$this->factor(),$type_name);
        }
        return $node;
    }

    function arith_expression(){
        $node=$this->arith_term();
        while($this->current_token->type==Constants::PLUS || $this->current_token->type==Constants::MINUS){
            $type_name=$this->current_token->type;
            $this->current_token=$this->lexer->get_next_token();
            $node= new BinOp(node,$this->arith_term(),type_name);
        }
        return $node;
    }

    function arith_parse(){
        return $this->arith_expression();
    }

    function boolean_term(){
        $node=$this->arith_expression();
        if($this->current_token->type==Constants::EQUAL || $this->current_token==Constants::LESSTHAN){
            $type_name=$this->current_token->type;
            $this->current_token=$this->lexer->get_next_token();
            $node=new BoolOp($node,$this->arith_expression);
        }
        return $node;
    }
    function boolean_parse(){
        return $this->boolean_expression();
    }
    function statement_term(){
        $node=$this->boolean_expression();
        if($this->current_token->type==Constants::ASSIGN){
            $type_name=$this->current_token->type;
            $this->current_token=$this->lexer->get_next_token();
            $node=new Assign($node,$this->boolean_expression(),$type_name);
        }
    }

    function statement_expression(){
        $node=$this->statement_term();
        while($this->current_token->type==Constants::SEMI){
            $type_name=$this->current_token->type;
            $this->current_token=$this->lexer->get_next_token();
            $node= new Semi($node,$this->statement_term(),$type_name);

        }
        return $node;
    }

    function statement_parse(){
        return $this->statement_expression();
    }
}

function dictionary($var,$val){
    $dict[$var]=[$val];
    return $dict;
}

function to_print($node){
    if($node->op == Constants::INTEGER || $node->op == Constants::ID || $node->op == Constants::SKIP){
        return $node->value;
    }
    else if($node->op == Constants::TRUE || $node->op == Constants::FALSE){
        return strtolower(str($node->val));
    }
    else if($node->op==Constants::PLUS || $node->op==Constants::MINUS || $node->op==Constants::MUL || $node->op ==Constants:: EQUAL || $node->op==Constants::LESSTHAN || $node->op==Constants::AND || $node->op==Constants::OR){
        return "(".str(to_print($node->left)).definitions($node->op).str(to_print($node->right)).")";
    }
    else if($node->op==Constants::NOT){
        return definitions($node->op).str(to_print($node->ap));
    }
    else if($node->op==Constants::ASSIGN){
        return str(to_print($node->left)).definitions($node->op).str(to_print($node->right));
    }
}



// echo get($Reserved_Keywords['if'],'nope')->toprint();
// $foo= new Token("asd","adda");
// $foo->toprint();

// echo $Reserved_Keywords["if"]->toprint();

?>