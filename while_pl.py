
NUMBER="NUMBER"

ADDITION="ADDITION"
SUBTRACTION="SUBTRACTION"
MULTIPLICATION="MULTIPLICATION"
DIVISION="DIVISION"


ID="ID"
ASSIGN="ASSIGN"


IF="if"
THEN="then"
ELSE="else"



EQUAL="EQUAL"
LESSTHAN="LESSTHAN"
GREATERTHAN="GREATERTHAN"

AND="AND"
OR="OR"
NOT="NOT"


SEMICOLON="SEMICOLON"
END="END"



L_PAREN="("
R_PAREN=")"
L_BRACES="{"
R_BRACES="}"

WHILE="while"
DO="do"

TRUE="true"
FALSE="false"

SKIP="skip"

class Token(object):
    def __init__(self, type, value):
        self.type = type
        self.value = value
operand_dict = {
        'ADDITION': '+',
        'SUBTRACTION': '-',
        'MULTIPLICATION': '*',
        'EQUAL': '=',
        'LESSTHAN': '<',
        'AND': '∨',
        'OR': '∧',
        'ASSIGN': ':=',
        'SEMICOLON': ';',
        'NOT': '¬',
    }


MY_KEYWORDS = {
    'if': Token('if','if'),
    'then': Token('then','then'),
    'else': Token('else','else'),
    'while': Token('while','while'),
    'do': Token('do','do'),
    'true': Token('true',True),
    'false': Token('false',False),
    'skip': Token('skip','skip')
}


class Tokenizer(object):
    def __init__(self, code):
        self.code = code
        self.position = 0
        self.curr_char = self.code[self.position]
        self.state = {}
    def skipwhitespace(self):
        while self.curr_char != None and self.curr_char.isspace():
            self.forward()

    def error(self):
        raise Exception('Character is invalid !')

    def number(self):
        result_num = ""
        while self.curr_char != None and self.curr_char.isdigit():
            result_num += self.curr_char
            self.forward()
        return int(result_num)
    def id(self):
        result_id = ""
        while self.curr_char != None and self.curr_char.isalnum():
            result_id += self.curr_char
            self.forward()

        t = MY_KEYWORDS.get(result_id, Token(ID, result_id))
        return t

    def peek_next_token(self):
        pos = self.position + 1
        if pos <= len(self.code) - 1:
            return self.code[pos]
        else:
            return None

    def next_token(self):
        while self.curr_char != None:
            if self.curr_char.isspace():
                self.skipwhitespace()
                continue
            if self.curr_char.isalpha():
                return self.id()

            if self.curr_char.isdigit():
                return Token(NUMBER, self.number())

            if self.peek_next_token() == '=' and self.curr_char == ':':
                self.forward()
                self.forward()
                return Token(ASSIGN, ':=')

            if self.curr_char == '∧':
                self.forward()
                return Token(AND, '∧')

            if self.curr_char == '∨':
                self.forward()
                return Token(OR, '∨')

            if self.curr_char == '{':
                self.forward()
                return Token(L_BRACES, '{')

            if self.curr_char == '}':
                self.forward()
                return Token(R_BRACES, '}')

            if self.curr_char == '+':
                self.forward()
                return Token(ADDITION, '+')
            if self.curr_char == '-':
                self.forward()
                return Token(SUBTRACTION, '-')

            if self.curr_char == '/':
                self.forward()
                return Token(DIVISION, '/')
            
            if self.curr_char == '*':
                self.forward()
                return Token(MULTIPLICATION, '*')

            if self.curr_char == '(':
                self.forward()
                return Token(L_PAREN, '(')

            if self.curr_char == ')':
                self.forward()
                return Token(R_PAREN, ')')

            if self.curr_char == ';':
                self.forward()
                return Token(SEMICOLON, ';')

            if self.curr_char == '=':
                self.forward()
                return Token(EQUAL, '=')
            if self.curr_char == '<':
                self.forward()
                return Token(LESSTHAN, '<')

            if self.curr_char == '>':
                self.forward()
                return Token(GREATERTHAN, '>')
            if self.curr_char == '¬':
                self.forward()
                return Token(NOT, '¬')

            self.error()
        return Token(END, None)

    def forward(self):
        self.position += 1
        if self.position > len(self.code) - 1:
            self.curr_char = None
        else:
            self.curr_char = self.code[self.position]


class Ast(object):
    pass

class Arithmetic(Ast):
    def __init__(self, left, operator, right):
        self.left = left
        self.right = right
        self.token = self.operator = operator
class Int(Ast):
    def __init__(self, token):
        self.operator = token.type
        self.value = token.value


class Compound(Ast):
    def __init__(self):
        self.children = list()
class Assignment(Ast):
    def __init__(self, left, operator, right):
        self.left = left
        self.token = self.operator = operator
        self.right = right


class Variable(Ast):
    def __init__(self, token):
        self.operator = token.type
        self.value = token.value



class Boolean(Ast):
    def __init__(self, token):
        self.value = token.value
        self.operator = token.type


class SemiColon(Ast):
    def __init__(self, left, operator, right):
        self.left = left
        self.right = right
        self.operator = operator




class Not(Ast):
    def __init__(self, node):
        self.operator = NOT
        self.ref = node


class BoolOperator(Ast):
    def __init__(self, left, right, operator):
        self.operator = operator
        self.left = left
        self.right = right

class SkipOp(Ast):
    def __init__(self, token):
        self.value = token.value
        self.operator = token.type



class If(Ast):
    def __init__(self, cond, truecond, falsecond):
        self.cond = cond
        self.truecond = truecond
        self.falsecond = falsecond
        self.operator = IF

class While(Ast):
    def __init__(self, cond, while_true, while_false):
        self.cond = cond
        self.while_true = while_true
        self.while_false = while_false
        self.operator = WHILE

class Parser:
    def __init__(self, lexer):
        self.state = lexer.state
        self.lexer = lexer
        self.curr_token = lexer.next_token()

    def error(self):
        raise Exception('Something went wrong while parsing !')

    def factor(self):
        token = self.curr_token
        if token.type == SUBTRACTION:
            self.curr_token = self.lexer.next_token()
            token = self.curr_token
            token.value = -1 * token.value
            node = Int(token)
        elif token.type == ID:
            node = Variable(token)
        elif token.type == NUMBER:
            node = Int(token)
        elif token.type == NOT:
            self.curr_token = self.lexer.next_token()
            if self.curr_token.type == L_PAREN:
                self.curr_token = self.lexer.next_token()
                node = self.boolexp()
            elif self.curr_token.type == TRUE or self.curr_token.type == FALSE:
                node = Boolean(self.curr_token)
            else:
                self.error()
            node = Not(node)
        elif token.type == TRUE or token.type == FALSE:
            node = Boolean(token)
        elif token.type == L_PAREN:
            self.curr_token = self.lexer.next_token()
            node = self.boolexp()
        elif token.type == R_PAREN:
            self.curr_token = self.lexer.next_token()
        elif token.type == L_BRACES:
            self.curr_token = self.lexer.next_token()
            node = self.stateexpression()
        elif token.type == R_BRACES:
            self.curr_token = self.lexer.next_token()
        elif token.type == SKIP:
            node = SkipOp(token)
        elif token.type == WHILE:
            self.curr_token = self.lexer.next_token()
            cond = self.boolexp()
            while_false = SkipOp(Token('skip', 'skip'))
            if self.curr_token.type == DO:
                self.curr_token = self.lexer.next_token()
                if self.curr_token == L_BRACES:
                    while_true = self.stateexpression()
                else:
                    while_true = self.term()

            return While(cond, while_true, while_false)
        elif token.type == IF:
            self.curr_token = self.lexer.next_token()
            cond = self.boolexp()
            if self.curr_token.type == THEN:
                self.curr_token = self.lexer.next_token()
                truecond = self.stateexpression()
            if self.curr_token.type == ELSE:
                self.curr_token = self.lexer.next_token()
                falsecond = self.stateexpression()
            return If(cond, truecond, falsecond)
        else:
            self.syntax_error()
        self.curr_token = self.lexer.next_token()
        return node

    def arithterm(self):
        node = self.factor()
        while self.curr_token.type == MULTIPLICATION:
            typename = self.curr_token.type
            self.curr_token = self.lexer.next_token()
            node = Arithmetic(node, typename,self.factor())
        return node

    def arithexpr(self):
        node = self.arithterm()
        while self.curr_token.type in (ADDITION, SUBTRACTION):
            typename = self.curr_token.type
            self.curr_token = self.lexer.next_token()
            node = Arithmetic(node,typename, self.arithterm())
        return node

    def parse_arith(self):
        return self.arithexpr()

    def boolterm(self):
        node = self.arithexpr()
        if self.curr_token.type in (EQUAL, LESSTHAN):
            typename = self.curr_token.type
            self.curr_token = self.lexer.next_token()
            node = BoolOperator(node,self.arithexpr(),typename)
        return node

    def boolexp(self):
        node = self.boolterm()
        while self.curr_token.type in (AND, OR):
            typename = self.curr_token.type
            self.curr_token = self.lexer.next_token()
            node = Arithmetic(left=node, right=self.boolterm(), operator=typename)
        return node

    def parsebool(self):
        return self.boolexp()

    def term(self):
        node = self.boolexp()
        if self.curr_token.type == ASSIGN:
            typename = self.curr_token.type
            self.curr_token = self.lexer.next_token()
            node = Assignment(left=node, right=self.boolexp(), operator=typename)
        return node

    def stateexpression(self):
        node = self.term()
        while self.curr_token.type == SEMICOLON:
            typename = self.curr_token.type
            self.curr_token = self.lexer.next_token()
            node = SemiColon(left=node, right=self.term(), operator=typename)
        return node

    def parsestatement(self):
        return self.stateexpression()


def dictionary(var, value):
    return dict( [ tuple( [var, value] ) ] )


def output(node):
    if node.operator == NUMBER or node.operator == ID or node.operator == SKIP:
        return node.value
    elif node.operator == TRUE or node.operator == FALSE:
        return str(node.value).lower()
    elif node.operator == ADDITION or node.operator == SUBTRACTION or node.operator == MULTIPLICATION or node.operator == EQUAL or node.operator == LESSTHAN or node.operator == AND or node.operator == OR:
        return ''.join(['(', str(output(node.left)), definitions(node.operator), str(output(node.right)), ')'])
    elif node.operator == NOT:
        return ''.join([definitions(node.operator), str(output(node.ref))])
    elif node.operator == ASSIGN:
        return ' '.join([str(output(node.left)), definitions(node.operator), str(output(node.right))])
    elif node.operator == SEMICOLON:
        return ' '.join([''.join([str(output(node.left)), definitions(node.operator)]), str(output(node.right))])
    elif node.operator == WHILE:
        return ' '.join(['while', str(output(node.cond)), 'do', '{', str(output(node.while_true)), '}'])
    elif node.operator == IF:
        return ' '.join(['if', str(output(node.cond)), 'then', '{', str(output(node.truecond)), '}', 'else', '{', str(output(node.falsecond)), '}'])
    else:
        raise Exception("Error while parsing")


class StringManipulation:
    def __init__(self, string):
        self.string = string

    def manipulate(self,other):
        return self.string.replace(other.string, '', 1)
    


def evaluate(ast, state, variables, intermediate, output_small_step, initial):
    node = ast
    if node.operator == NUMBER or node.operator == TRUE or node.operator == FALSE:
        return node.value
    elif node.operator == ID:
        if node.value in state:
            return state[node.value]
        else:
            state[node.value]=0
            return state.get(node.value)
    elif node.operator == SKIP:
        unique_varibles = set(variables)
        state_copy= state.copy()
        state_copy= dict((var, state_copy[var]) for var in unique_varibles)
        intermediate.append(state_copy)
        string_node = StringManipulation(str(output(node)))
        output_small_step.append([StringManipulation(StringManipulation(initial).manipulate(string_node)).manipulate(StringManipulation('; '))])
       
    elif node.operator == SEMICOLON:
        evaluate(node.left, state, variables, intermediate, output_small_step, initial)
        unique_varibles = set(variables)
        state_copy= state.copy()
        state_copy= dict((var, state_copy[var]) for var in unique_varibles)
        intermediate.append(state_copy)
        string_node = StringManipulation(str(output(node.left)))
        output_small_step.append([str(StringManipulation(StringManipulation(initial).manipulate(string_node)).manipulate(StringManipulation('; ')))])
        initial = StringManipulation(StringManipulation(initial).manipulate(string_node)).manipulate(StringManipulation('; '))
        evaluate(node.right, state, variables, intermediate, output_small_step, initial)
    elif node.operator == ASSIGN:
        var = node.left.value
        variables.append(var)
        if state.get(var):
            state[var] = evaluate(node.right, state, variables, intermediate, output_small_step, initial)
        else:
            state.update(dictionary(var, evaluate(node.right, state, variables, intermediate, output_small_step, initial)))
        unique_varibles = set(variables)
        state_copy= state.copy()
        state_copy= dict((var, state_copy[var]) for var in unique_varibles)
        intermediate.append(state_copy)
        string_node = StringManipulation(str(output(node)))
        output_small_step.append(['skip; ' + str(StringManipulation(StringManipulation(initial).manipulate(string_node)).manipulate(StringManipulation('; ')))])
        

    elif node.operator == WHILE:
        cond = node.cond
        while_true = node.while_true
        node.while_false
        break_while = 0
        while evaluate(cond, state, variables, intermediate, output_small_step, initial):
            break_while += 1
            if break_while >= 10000:
                break
            unique_varibles = set(variables)
            state_copy= state.copy()
            state_copy= dict((var, state_copy[var]) for var in unique_varibles)
            intermediate.append(state_copy)
            initial = initial.replace(output(node), str(output(node.while_true) + '; ' + output(node)))
            output_small_step.append([initial])
            evaluate(while_true, state, variables, intermediate, output_small_step, initial)
            unique_varibles = set(variables)
            state_copy= state.copy()
            state_copy= dict((var, state_copy[var]) for var in unique_varibles)
            intermediate.append(state_copy)
            string_node = StringManipulation(str(output(node.while_true)))
            output_small_step.append([StringManipulation(StringManipulation(initial).manipulate(string_node)).manipulate(StringManipulation('; '))])
            initial = StringManipulation(StringManipulation(initial).manipulate(string_node)).manipulate(StringManipulation('; '))
        unique_varibles = set(variables)
        state_copy= state.copy()

        state_copy= dict((var, state_copy[var]) for var in unique_varibles)
        intermediate.append(state_copy)
        string_node = StringManipulation(output(node))
        output_small_step.append(['skip; ' + (StringManipulation(StringManipulation(initial).manipulate(string_node)).manipulate(StringManipulation('; ')))])
        
    elif node.operator == IF:
        cond = node.cond
        truecond = node.truecond
        falsecond = node.falsecond
        if evaluate(cond, state, variables, intermediate, output_small_step, initial):
            unique_varibles = set(variables)
            state_copy= state.copy()
            state_copy= dict((var, state_copy[var]) for var in unique_varibles)
            intermediate.append(state_copy)
            string_node = StringManipulation(str(output(node)))
            output_small_step.append([str(output(node.truecond)) + (StringManipulation(initial).manipulate(string_node))])
            initial = str(output(node.truecond)) + (StringManipulation(initial).manipulate(string_node))
            evaluate(truecond, state, variables, intermediate, output_small_step, initial)
        else:
            unique_varibles = set(variables)
            state_copy= state.copy()
            state_copy= dict((var, state_copy[var]) for var in unique_varibles)
            intermediate.append(state_copy)
            string_node = StringManipulation(str(output(node)))
            output_small_step.append([str(output(node.falsecond)) + (StringManipulation(initial).manipulate(string_node))])
            initial = str(output(node.falsecond)) + (StringManipulation(initial).manipulate(string_node))
            evaluate(falsecond, state, variables, intermediate, output_small_step, initial)
    elif node.operator == ADDITION:
        return evaluate(node.left, state, variables, intermediate, output_small_step, initial) + evaluate(node.right, state, variables, intermediate, output_small_step, initial)
    elif node.operator == SUBTRACTION:
        return evaluate(node.left, state, variables, intermediate, output_small_step, initial) - evaluate(node.right, state, variables, intermediate, output_small_step, initial)
    elif node.operator == MULTIPLICATION:
        return evaluate(node.left, state, variables, intermediate, output_small_step, initial) * evaluate(node.right, state, variables, intermediate, output_small_step, initial)
    elif node.operator == NOT:
        return not evaluate(node.ref, state, variables, intermediate, output_small_step, initial)
    elif node.operator == EQUAL:
        return evaluate(node.left, state, variables, intermediate, output_small_step, initial) == evaluate(node.right, state, variables, intermediate, output_small_step, initial)
    elif node.operator == LESSTHAN:
        return evaluate(node.left, state, variables, intermediate, output_small_step, initial) < evaluate(node.right, state, variables, intermediate, output_small_step, initial)
    elif node.operator == AND:
        return evaluate(node.left, state, variables, intermediate, output_small_step, initial) and evaluate(node.right, state, variables, intermediate, output_small_step, initial)
    elif node.operator == OR:
        return evaluate(node.left, state, variables, intermediate, output_small_step, initial) or evaluate(node.right, state, variables, intermediate, output_small_step, initial)
    
    else:
        raise Exception('Something went wrong!')


class Interpreter:
    def __init__(self, parser):
        self.state = parser.state
        self.ast = parser.parsestatement()
        self.variables = list()
        self.intermediate = list()
        self.output_small_step = list()
        self.initial = output(self.ast)

    def visit(self):
        return evaluate(self.ast, self.state, self.variables, self.intermediate, self.output_small_step, self.initial)


def definitions(operand):
    
    return operand_dict.get(operand)

user_input = input().strip()
lexer = Tokenizer(user_input)
parser = Parser(lexer)
interpreter = Interpreter(parser)
interpreter.visit()
steps = interpreter.output_small_step
temp=[]
MAX_LENGTH=100_00
steps_temp=[]
for sublist in steps:
    for item in sublist:
        steps_temp.append(item)

steps = steps_temp

states = interpreter.intermediate
if user_input[0:5] == 'skip;' or user_input[0:6] == 'skip ;':
    steps.popleft()
    states.popleft()
steps[-1] = 'skip'
if len(states) > MAX_LENGTH:
    states = states[:MAX_LENGTH]
    steps = steps[:MAX_LENGTH]
if len(states) == 1 and states[0] == {} and user_input[:4] == 'skip':
    print('')
else:
    for i in range(len(states)):
        output_string = []
        for j in sorted(states[i]):
            output_string.append(' '.join([j, '→', str(states[i][j])]))
        stateoutput = ''.join(['{', ', '.join(output_string), '}'])
        step_final = ' '.join(['⇒', steps[i]])
        print(step_final, stateoutput, sep=', ')

