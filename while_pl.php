<?php
class Constants{

    const "INTEGER" = "INTEGER";
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

    public static function getMinValue(){
        return self::WHILE;
  }

    
}

$min = Constants::WHILE;

echo $min;

?>