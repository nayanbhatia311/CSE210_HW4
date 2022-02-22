<?php
function definitions($operand){
    $cases = [
        "PLUS" => "+",
        "MINUS" => "-",
        "MUL" => "*",
        "EQUAL" => "=",
        "LESSTHAN" => "<",
        "AND" => '∨',
        'OR' => '∧',
        'ASSIGN' => ':=',
        'SEMI' => ';',
        'NOT' => '¬'
    ];
    return $cases[$operand];
}

class Token{
    public $type;
    public $value;
    function __construct($type, $value) {
        $this->type=$type;
        $this->value=$value;
    }
    public function __toString(){
    	return "Token(".$this->type.",".$this->value.")";
    }

    public function toprint() {
        echo "Token(".$this->type.",".$this->value.")";
    }
}

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
    const LPAREN = "(";
    const RPAREN = ")";
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
    const IF = "if";
    const THEN = "then";
    const ELSE = "else"; 
    const LBRACE = "{";
    const RBRACE = "}";
    const WHILE = "while";
    const DO = "do";
    const TRUE = "true";
    const FALSE = "false";
    const SKIP = "skip";
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
        $this->current_char=$this->text[$this->pos];
    }
    

    function error() {
    	echo var_dump($this->pos)."this pos\n".$this->current_char."this current_char";
        throw new Exception('Invalid syntax');
    }

    function advance(){
        $this->pos +=1;
        if($this->pos > (strlen($this->text)-1) ){
            $this->current_char = NULL;
        }
        else{
            $this->current_char=$this->text[$this->pos];
        }
    }

    function peek(){
        $peek_pos= $this->pos + 1;
        if($peek_pos > strlen($this->text)-1){
            return NULL;
        }
        else{
            return $this->text[$peek_pos];
        }
    }

    function skip_whitespace(){
        while($this->current_char!=NULL && IntlChar::isspace($this->current_char)){
            $this->advance();

        }
    }

    function integer_function(){
        $result="";
        while($this->current_char!=NULL && IntlChar::isdigit($this->current_char)){
            $result=$result.$this->current_char;
            $this->advance(); 
        }
        return (int)$result;
    }
    function get($var) {
        return in_array($var, $GLOBALS['Reserved_Keywords']) ? $GLOBALS['Reserved_Keywords'][$var] : new Token(Constants::ID, $var);
    }

    function _id(){
        $result="";
        while($this->current_char!=NULL && ctype_alnum($this->current_char)){
            $result=$result.$this->current_char;
            $this->advance();
        }
        $token= $this->get($result);
        return $token;
    }

    function get_next_token(){
        while($this->current_char!=NULL){
            if(IntlChar::isspace($this->current_char)){ //sudo apt-get install php-intl
                $this->skip_whitespace();
                continue;
            }
            if(ctype_alpha($this->current_char)){
                return $this->_id();
            }
            if(IntlChar::isdigit($this->current_char)){
                return new Token(Constants::INTEGER,$this->integer_function());
            }
            if($this->current_char == ":" && $this->peek() == "="){
                $this->advance();
                $this->advance();
                return new Token(Constants::ASSIGN,":=");
            }
            if($this->current_char == ";"){
                $this->advance();
                return new Token(Constants::SEMI,";");
            }
            if($this->current_char=="+"){
                $this->advance();
                return new Token(Constants::PLUS,"+");
            }
            if($this->current_char=="-"){
                $this->advance();
                return new Token(Constants::MINUS,"-");
            }
            if($this->current_char=="*"){
                $this->advance();
                return new Token(Constants::MUL,"*");
            }
            if($this->current_char=="/"){
                $this->advance();
                return new Token(Constants::DIV,"/");
            }
            if($this->current_char=="("){
                $this->advance();
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
                $this->advance();
                return new Token(Constants::LESSTHAN,"<");
            }
            if($this->current_char==">"){
                $this->advance();
                return new Token(Constants::GREATERTHAN,">");
            }
            if($this->current_char=="∧"){
                $this->advance();
                return new Token(Constants::AND,"∧");
            }
            if($this->current_char=="∨"){
                $this->advance();
                return new Token(Constants::AND,"∨");
            }
            if($this->current_char=="¬"){
           	echo "here";
                $this->advance();
                return new Token(Constants::NOT,"¬");
            }
            if($this->current_char=="{"){
                $this->advance();
                return new Token(Constants::LBRACE,"{");
            }
            if($this->current_char=="}"){
                $this->advance();
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
        $this->token=$op;
        $this->left=$left;
        $this->right=$right;
    }
}

class Num extends AST{
    public $left;
    public $token;
    public $right;
    public $op;
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
    public $token;
    public $op;
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
    public $op;
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
        $this->ap=$node;
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
    public $op;
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
    public $op;
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
    public $node;
    function __construct($lexer){
        $this->lexer=$lexer;
        $this->state=$lexer->state;
        $this->current_token=$lexer->get_next_token();
    }
    function error() {
        throw new Exception('Invalid syntax');
    }
    function factor(){
        $token = $this->current_token;
        if($token->type==Constants::MINUS){
            $this->current_token = $this->lexer->get_next_token();
            $token=$this->current_token;
            $token->value= -1*$token->value;
            $node= new Num($token);
        }
        else if($token->type==Constants::INTEGER){
            $node= new Num($token);
        }
        else if($token->type==Constants::ID){
            $node=new Variable($token);
        }
        else if($token->type==Constants::NOT){
            $this->current_token=$this->lexer->get_next_token();
            if($this->current_token==Constants::LPAREN){
                $this->current_token=$this->lexer->get_next_token();
                $node=$this->boolean_expression();
            }
            else if($this->current_token->type==Constants::TRUE || $this->current_token->type==Constants::FALSE){
                $node=new Boolean($token);
            }
            else{
                $this->error();
            }
            $node=new Not($node);
        }
        else if($token->type==Constants::TRUE || $token->type==Constants::FALSE){
            $node=new Boolean($token);
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
         else if($token->type==Constants::RBRACE){
            $this->current_token=$this->lexer->get_next_token();
          
        }
        else if($token->type==Constants::SKIP){
            $node=new Skip($token);
        }
        else if($token->type==Constants::WHILE){
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
            return new While_condition($condition,$while_true,$while_false);
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
            $this->error();
        }

        $this->current_token=$this->lexer->get_next_token();
        return $node;
    }

    function arith_term(){
        $node=$this->factor();
        while($this->current_token->type==Constants::MUL){
            $type_name=$this->current_token->type;
            $this->current_token=$this->lexer->get_next_token();
            $node=new BinOp($node,$type_name,$this->factor());
        }
        return $node;
    }

    function arith_expression(){
        $node=$this->arith_term();
        while($this->current_token->type==Constants::PLUS || $this->current_token->type==Constants::MINUS){
            $type_name=$this->current_token->type;
            $this->current_token=$this->lexer->get_next_token();
            $node= new BinOp($node,$type_name,$this->arith_term());
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
            $node=new BoolOp($node,$tpe_name,$this->arith_expression);
        }
        return $node;
    }
    
    function boolean_expression(){
    	$node=$this->boolean_term();
    	while($this->current_token->type == Constants::AND || $this->current_token->type==Constants::OR){
    		$type_name=$this->current_token->type;
    		$this->current_token=$this->lexer->get_next_token();
    		$node = new BinOp($node,$type_name,$this->boolean_term());
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
            $node=new Assign($node,$type_name,$this->boolean_expression());
        }
        return $node;
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
    if($node->op == Constants::INTEGER || $node->op == Constants::ID  || $node->op == Constants::SKIP){
        return $node->value;
    }
    else if($node->op == Constants::TRUE || $node->op == Constants::FALSE){
        return strtolower(strval($node->val));
    }
    else if($node->op==Constants::PLUS || $node->op==Constants::MINUS || $node->op==Constants::MUL || $node->op ==Constants:: EQUAL || $node->op==Constants::LESSTHAN || $node->op==Constants::AND || $node->op==Constants::OR){
        return "(".strval(to_print($node->left)).definitions($node->op).strval(to_print($node->right)).")";
    }
    else if($node->op==Constants::NOT){
        return definitions($node->op).strval(to_print($node->ap));
    }
    else if($node->op==Constants::ASSIGN){
        return strval(to_print($node->left))." ".definitions($node->op)." ".strval(to_print($node->right));
    }
    else if($node->op == Constants::SEMI){
        return strval(to_print($node->left))." ".definitions($node->op)." ".strval(to_print($node->right));
    }
    else if($node->op == Constants::WHILE){
        return "while ".strval(to_print($node->condition))." do { ".strval(to_print($node->while_true)." }");
    }
    else if($node->op == Constants::IF){
        return  "if ".strval(to_print($node->condition))." then { ".strval(to_print($node->if_true))." }";
    }
    else{
        throw new Exception('Invalid syntax');
    }
}

class SubString{
    public $strings;
    
    function str_replace_first($search, $replace, $subject){
    	
    	$search = '/'.preg_quote($search, '/').'/';
    	return preg_replace($search, $replace, $subject, 1);
    }

    function __construct($strings){
        $this->strings=$strings;
    }

    function subtract($other){
        $empty_str = "";
        $one = 1;
        return $this->str_replace_first($other->strings, $empty_str, $this->strings);
    }
}

function evaluate($ast, &$state, &$variables, &$immediate_state, &$print_ss, &$first_step){
    $node=$ast;
    if($node->op == Constants::INTEGER || $node->op == Constants::TRUE || $node->op == Constants::FALSE){
        return $node->value;
    }
    else if($node->op==Constants::ID){
        if(array_key_exists($node->value,$state)){
            return $state[$node->value];
        }
        else{
            $state[$node->value]=0;
            return 0;

        }
    }
    else if($node->op==Constants::SKIP){
        $temp_variable=array_unique(variables);
        $temp_state = clone state;
        for($i=0;$i<count(temp_variable);$i++){
            $temp_state[$temp_variable[$i]]=$temp_state[$temp_variable[$i]];
        }

        array_push($immediate_state,$temp_state);
        $temp_step = new SubString(str(to_print($node)));
        $sub_tempstep_first_step=new Substring($first_step);
        $temp_result=$sub_tempstep_first_step->subtract($temp_step);
        $semi_colon=new Substring("; ");
        $temp_result=$temp_result->subtract($semi_colon);

        array_push($print_ss,array(new SubString($semi_colon->subtract($temp_result))));
        
    }
    else if($node->op==Constants::SEMI){
        evaluate($node->left, $state, $variables, $immediate_state, $print_ss, $first_step);
        $temp_variable=array_unique($variables);
        $temp_state = $state;
        for($i=0;$i<count($temp_variable);$i++){
            $temp_state[$temp_variable[$i]]=$temp_state[$temp_variable[$i]];
        }

        array_push($immediate_state,$temp_state);
        $temp_step = new SubString(strval(to_print($node)));
        $first_step_substring=new Substring($first_step);
        $temp_result=$first_step_substring->subtract($temp_step);
        $semi_colon=new Substring("; ");
        $first_step=array($semi_colon->subtract($temp_result));
        array_push($print_ss,$first_step);
        evaluate($node->right, $state, $variables, $immediate_state, $print_ss, $first_step);
    }
    else if($node->op==Constants::ASSIGN){
        $var=$node->left->value;
        array_push($variables,$var);
        
        $state[$var] = evaluate($node->right, $state, $variables, $immediate_state, $print_ss, $first_step);
        $temp_variable=array_unique($variables);
        $temp_state = $state;
        for($i=0;$i<count($temp_variable);$i++){
            $temp_state[$temp_variable[$i]]=$temp_state[$temp_variable[$i]];
        }

        array_push($immediate_state,$temp_state);
        $temp_step = new SubString(strval(to_print($node)));
        $semi_colon=new Substring("; ");
        $first_step_substring=new Substring($first_step);
        $temp_result=new Substring($first_step_substring->subtract($temp_step));
        $semi_colon_temp_subtract=$semi_colon->subtract($temp_result);
        $first_step=array('skip; '.$semi_colon_temp_subtract);
        array_push($print_ss,$first_step);   
    }
    else if ($node->op==Constants::PLUS){
        return evaluate($node->left,$state,$variables,$immediate_state,$print_ss,$first_step);
    }

    else if ($node->op == Constants::MINUS){
        return evaluate($node->left, $state, $variables, $immediate_state, $print_ss, $first_step) - evaluate($node->right, $state, $variables, $immediate_state, $print_ss, $first_step);
    }
    else if($node->op == Constants::MUL){
        return evaluate($node->left, $state, $variables, $immediate_state, $print_ss, $first_step) * evaluate($node->right, $state, $variables, $immediate_state, $print_ss, $first_step);
    }
    else if ($node->op == Constants::NOT){
        return !evaluate($node->ap, $state, $variables, $immediate_state, $print_ss, $first_step);
    }
    else if ($node->op == Constants::EQUAL){
        return evaluate($node->left, $state, $variables, $immediate_state, $print_ss, $first_step) == evaluate($node->right, $state, $variables, $immediate_state, $print_ss, $first_step);
    }
    else if ($node->op == Constants::LESSTHAN){
        return evaluate($node->left, $state, $variables, $immediate_state, $print_ss, $first_step) < evaluate($node->right, $state, $variables, $immediate_state, $print_ss, $first_step);
    }
    else if ($node->op == Constants::AND){
        return evaluate($node->left, $state, $variables, $immediate_state, $print_ss, $first_step) && evaluate($node->right, $state, $variables, $immediate_state, $print_ss, $first_step);
    }
    else if ($node->op == Constants::OR) {
        return evaluate($node->left, $state, $variables, $immediate_state, $print_ss, $first_step) || evaluate($node->right, $state, $variables, $immediate_state, $print_ss, $first_step);
    }
    else if($node->op == Constants::WHILE) {
        $condition=$node->conditions;
        $while_true = $node->while_true;
        $while_false=$node->while_false;
        $break_while = 0;
        
        while(evaluate($node->left, $state, $variables, $immediate_state, $print_ss, $first_step)){
            $break_while+=1;
            if($break_while>=10000){
                break;
            }
            $temp_variable=array_unique(variables);
            $temp_state = clone state;
            for($i=0;$i<count(temp_variable);$i++){
                $temp_state[$temp_variable[$i]]=$temp_state[$temp_variable[$i]];
            }
            array_push($immediate_state,$temp_state);
            $first_step=str_replace(to_print($node),str(to_print($node->while_true))."; ".to_print($node),$first_step);
            push_array($print_ss,array($first_step));
            evaluate($while_true, $state, $variables, $immediate_state, $print_ss, $first_step);
            $temp_variable=array_unique(variables);
            $temp_state = clone state;
            for($i=0;$i<count(temp_variable);$i++){
                $temp_state[$temp_variable[$i]]=$temp_state[$temp_variable[$i]];
            }
            array_push($immediate_state,$temp_state);
            $temp_step=new SubString(str(to_print($node->while_true)));
            $semi_colon=new Substring("; ");
            $first_step_substring=new Substring($first_step);
            $first_step_substring_temp_size=$first_step_substring->subtract($temp_step);
            $temp_var=$first_step_substring_temp_size->subtract($semi_colon);
            array_push($print_ss,array($temp_var));
            
            $first_step_substring=new SubString($first_step);
            $first_step_substring_temp_size=new Substring($first_step_substring->subtract($temp_step));
            $first_step= $first_step_substring_temp_size->subtract($semi_colon);

        }
        $temp_variable=array_unique($variables);
        $temp_state = clone $state;
        for($i=0;$i<count($temp_variable);$i++){
            $temp_state[$temp_variable[$i]]=$temp_state[$temp_variable[$i]];
        }

        array_push($immediate_state,$temp_state);
        $temp_step = new SubString(str(to_print($node)));
        $first_step_substring=new SubString($first_step);
        $temp_result=$first_step_substring->subtract($temp_step);
        $semi_colon=new SubString('; ');
        
        $first_step=array('skip; '.$semi_colon->subtract($temp_result));
        array_push(print_ss($first_step));
       

        }
        else if($node->op==Constants::IF){
            $condition=$node->condition;
            $if_true=$node->if_true;
            $if_false=$node->if_false;
            if(evaluate($condition, $state, $variables, $immediate_state, $print_ss, $first_step)){
                $temp_variable=array_unique($variables);
                $temp_state = clone $state;
                for($i=0;$i<count($temp_variable);$i++){
                    $temp_state[$temp_variable[$i]]=$temp_state[$temp_variable[$i]];
                }
        
                array_push($immediate_state,$temp_state);
                
                $temp_step = new SubString(str(to_print($node)));
                $first_step_substring= new SubString($first_step);
                $first_step_subtract_temp=$first_step_substring->subtract($temp_step);
                array_push($print_ss,str(to_print($node->if_true)).$first_step_subtract_temp);
                $first_step=str(to_print($node->if_true)).($first_step_substring->subtract($temp_step));
                evaluate($if_true, $state, $variables, $immediate_state, $print_ss, $first_step);

            }
            else{
                $temp_variable=array_unique($variables);
                $temp_state = clone $state;
                for($i=0;$i<count($temp_variable);$i++){
                    $temp_state[$temp_variable[$i]]=$temp_state[$temp_variable[$i]];
                }
        
                array_push($immediate_state,$temp_state);
                $temp_step = new SubString(str(to_print($node)));
                $first_step_substring= new SubString($first_step);
                $first_step_subtract_temp=$first_step_substring->subtract($temp_step);
                array_push($print_ss,str(to_print($node->if_false)).$first_step_subtract_temp);
                $first_step_subtract_temp=$first_step_substring->subtract($temp_step);
                $first_step=str(to_print($node->if_false)).$first_step_subtract_temp;
                evaluate($if_false, $state, $variables, $immediate_state, $print_ss, $first_step);
            }
        
        }
        else{
            throw new Exception("Error");
        }
}


class Interpreter{
    public $state;
    public $ast;
    public $variables;
    public $immediate_state;
    public $print_ss;
    public $first_step;
    public $strings;
    function __construct($parser){
        $this->strings=$parser->state;
        $this->ast=$parser->statement_parse();
        $this->variables=[];
        $this->immediate_state=$parser->state;
        $this->print_ss=[];
        $this->state=[];
        $this->first_step=to_print($this->ast);
    }

    function visit(){
        return evaluate($this->ast,$this->state,$this->variables,$this->immediate_state,$this->print_ss,$this->first_step);
    }
}




    $contents=[];
    $line = trim(fgets(STDIN));
    array_push($contents,$line);
    $user_input = join(" ",$contents);
    $user_input = join(" ",explode(" ",$user_input));
    $lexer = new Lexer($user_input);
    $parser = new Parser($lexer);
    $interpreter = new Interpreter($parser);
    $interpreter->visit();
    $steps = $interpreter->print_ss;
    $steps_temp = [];
    $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($steps));
    foreach($it as $v) {
        array_push($steps_temp,$v); //https://stackoverflow.com/questions/1319903/how-to-flatten-a-multidimensional-array
      }
    $steps_temp=$steps;
    $states = $interpreter->immediate_state;
    if(substr($user_input,0,5) == 'skip;' || substr($user_input,0,6) == 'skip ;'){
        $steps=array_shift($steps);
        $states=array_shift($states);
    }
    $steps[count($steps)-1] = 'skip';
    if (count($states) > 10000){
        $states = array_slice($states,0,10000);
        $steps = array_slice($steps,0,10000);
    }
    if(count($states) == 1 && $states[0] == [] && substr($user_input,0,4) == 'skip'){
        echo '';
    }
    else{
        for($i=0;$i<count($states);$i++){
            $output_string=[];
            foreach ($states[$i] as $key => $value){
                array_push($output_string,strval($key).' → '.strval($value));
            }
            $state_string=join("",array('{', join(', ',$output_string), '}'));
            $step_string = join("",array('⇒', " ",$steps[$i]));

            echo $step_string.", ".$state_string;
        }
    }


//print_r($contents);



// echo get($Reserved_Keywords['if'],'nope')->toprint();
// $foo= new Token("asd","adda");
// $foo->toprint();

// echo $Reserved_Keywords["if"]->toprint();

?>
