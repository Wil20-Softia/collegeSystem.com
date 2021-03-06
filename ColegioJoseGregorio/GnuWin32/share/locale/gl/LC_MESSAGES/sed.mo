??    U      ?  q   l      0  ?   1  ,   ,  5   Y  N   ?  7   ?  \   	  _   s	  `   ?	  u   4
  l   ?
  b     V   z  Y   ?  ~   +  ?   ?  %   :     `     w     ?     ?     ?     ?     ?       $   *     O     a     |     ?     ?  #   ?     ?     ?     ?          !     3     E  H   R     ?     ?     ?  !   ?          )  (   >     g     z  #   ?     ?     ?  $   ?          :  #   T  B   x  2   ?     ?           #     A  *   `  *   ?     ?     ?     ?  #   ?  #     &   <     c     r  ,   ?     ?     ?  -   ?          0     ?     N     d     z     ?     ?     ?  ?  ?  '  j  '   ?  7   ?  ^   ?  =   Q  a   ?  m   ?  a   _  ?   ?  ?   R  s   ?  g   L  d   ?  ?     ?   ?  (   D     m     ?     ?     ?  "   ?  &   ?  !        A  %   \     ?     ?     ?     ?  '   ?  "   ?      !     B     H     f     z     ?     ?  J   ?      ?           8   1   X      ?      ?   F   ?      	!      !!  $   B!  (   g!  '   ?!  1   ?!  .   ?!  $   "  -   >"  [   l"  7   ?"      #  '   #  )   ;#  '   e#  8   ?#  1   ?#  %   ?#     $     5$  ,   D$  ,   q$  2   ?$     ?$  '   ?$  =   %     T%     t%  <   ?%     ?%     ?%     ?%     ?%     &     6&     F&     c&  *   ?&                .   5   0   6   ,   7         1   2   /   	          D      A       U      4              O               +   '   (   C      T          
       >   =      "   8          E           M   N          *      :   J   R            -   G                        P   K   Q   @           %   3   9   ?          $   I          ;   B   !          L          H           #           &   F      S   <             )          
If no -e, --expression, -f, or --file option is given, then the first
non-option argument is taken as the sed script to interpret.  All
remaining arguments are names of input files; if no input files are
specified, then the standard input is read.

       --help     display this help and exit
       --version  output version information and exit
   --follow-symlinks
                 follow symlinks when processing in place
   --posix
                 disable all GNU extensions.
   -R, --regexp-perl
                 use Perl 5's regular expressions syntax in the script.
   -b, --binary
                 open files in binary mode (CR+LFs are not processed specially)
   -e script, --expression=script
                 add the script to the commands to be executed
   -f script-file, --file=script-file
                 add the contents of script-file to the commands to be executed
   -i[SUFFIX], --in-place[=SUFFIX]
                 edit files in place (makes backup if extension supplied)
   -l N, --line-length=N
                 specify the desired line-wrap length for the `l' command
   -n, --quiet, --silent
                 suppress automatic printing of pattern space
   -r, --regexp-extended
                 use extended regular expressions in the script.
   -s, --separate
                 consider files as separate rather than as a single continuous
                 long stream.
   -u, --unbuffered
                 load minimal amounts of data from the input files and flush
                 the output buffers more often
 %s: -e expression #%lu, char %lu: %s
 %s: can't read %s: %s
 %s: file %s line %lu: %s
 : doesn't want any addresses GNU sed version %s
 Invalid back reference Invalid character class name Invalid collation character Invalid content of \{\} Invalid preceding regular expression Invalid range end Invalid regular expression Memory exhausted No match No previous regular expression Premature end of regular expression Regular expression too big Success Trailing backslash Unmatched ( or \( Unmatched ) or \) Unmatched [ or [^ Unmatched \{ Usage: %s [OPTION]... {script-only-if-no-other-script} [input-file]...

 `e' command not supported `}' doesn't want any addresses based on GNU sed version %s

 can't find label for jump to `%s' cannot remove %s: %s cannot rename %s: %s cannot specify modifiers on empty regexp cannot stat %s: %s command only uses one address comments don't accept any addresses couldn't attach to %s: %s couldn't edit %s: is a terminal couldn't edit %s: not a regular file couldn't follow symlink %s: %s couldn't open file %s: %s couldn't open temporary file %s: %s couldn't write %d item to %s: %s couldn't write %d items to %s: %s delimiter character is not a single-byte character error in subprocess expected \ after `a', `c' or `i' expected newer version of sed extra characters after command invalid reference \%d on `s' command's RHS invalid usage of +N or ~N as first address invalid usage of line address 0 missing command multiple `!'s multiple `g' options to `s' command multiple `p' options to `s' command multiple number options to `s' command no input files no previous regular expression number option to `s' command may not be zero option `e' not supported read error on %s: %s strings for `y' command are different lengths super-sed version %s
 unexpected `,' unexpected `}' unknown command: `%c' unknown option to `s' unmatched `{' unterminated `s' command unterminated `y' command unterminated address regex Project-Id-Version: sed 4.2.0
Report-Msgid-Bugs-To: bug-gnu-utils@gnu.org
POT-Creation-Date: 2009-06-27 15:08+0200
PO-Revision-Date: 2008-09-18 08:35+0200
Last-Translator: Francisco Javier Tsao Sant?n <tsao@enelparaiso.org>
Language-Team: Galician <proxecto@trasno.net>
MIME-Version: 1.0
Content-Type: text/plain; charset=iso-8859-1
Content-Transfer-Encoding: 8bit
Plural-Forms: nplurals=2; plural=n!=1;
 
Se non se indican as opci?ns -e, --expression, -f ou --file, ent?n o primeiro
argumento que non ? unha opci?n t?mase como o script sed para interpretar. T?dolos
argumentos restantes son nomes de ficheiros de entrada; se non se especifican
ficheiros de entrada, ent?n se le a entrada standard.

      --help     amosa esta axuda e sae
      --version  amosa-la informaci?n da versi?n e sa?r
   --follow-symlinks
                 segue ligaz?ns simb?licas cando se procesan no seu sitio
   --posix
                 desactiva t?dalas extensi?ns GNU.
   -R, --regexp-perl
                 usa-la sintaxe de expresi?ns regulares de Perl 5 no script.
   -b, --binary
                 ficheiros abertos en modo binario (non se procesan de xeito especial CR+LFs)
 -e script, --expression=script
               engade script ?s instrucci?ns que ser?n executadas
   -f ficheiro-de-script, --file=ficheiro-de-script
                 engade o contido do ficheiro do script ?s instrucci?ns que ser?n executadas
   -i[SUFIXO], --in-place[=SUFIXO]
                 edita ficheiros no seu sitio (facendo copia de seguridade se se indica un sufixo)
   -l N, --line-length=N
                 especifica a lonxitude de axuste da li?a desexado para a instrucci?n `l' 
   -n, --quiet, --silent
                      suprime a visualizaci?n autom?tica do espacio de patr?ns
   -r, --regexp-extended
                 usa a sintaxe extendida de expresi?ns regulares no script.
   -s, --separate
                 trata os ficheiros coma separados no canto de coma unha
                 longa tira de caracteres continua.
   -u, --unbuffered 
                 carga cantidades m?nimas de datos dos ficheiros de entrada
                 e baleira os buffers de sa?da m?is decote
 %s: -e expresi?n #%lu, car?cter %lu: %s
 %s: non se puido ler %s: %s
 %s: ficheiro %s li?a %lu: %s
 : non acepta un enderezo GNU sed versi?n %s
 Referencia cara a atr?s non v?lida Nome de clase de caracteres non v?lido Car?cter de ordeamento non v?lido Contido de \{\} non v?lido Expresi?n regular anterior non v?lida Fin de rango non v?lida Expresi?n regular non v?lida Memoria esgotada Non se atopou Non hai unha expresi?n regular anterior Fin prematura da expresi?n regular Expresi?n regular grande de m?is ?xito Barra invertida ? fin de li?a ( ou \( sen parella ) ou \) sen parella [ ou [^ sen parella \{ sen parella Uso: %s [OPCI?N]... {script-s?-sen-outro-script} [ficheiro-de-entrada]...
 o comando `e' non est? soportado `}' non acepta un enderezo baseado en GNU sed versi?n %s

 non se puido atopa-la etiqueta para saltar a `%s' non se puido borrar %s: %s non se puido renomear %s: %s non se poden especificar modificadores nunha expresi?n regular baleira non se puido ler %s: %s a instrucci?n s? usa un enderezo os comentarios non aceptan enderezos non se puido adxuntar elemento en %s: %s non se puido editar %s: ? unha terminal non se puido editar %s: non ? un ficheiro regular non se puido segui-la ligaz?n simb?lica %s: %s non se puido abri-lo ficheiro %s: %s non se puido abri-lo ficheiro temporal %s: %s non se puido escribir %d elemento en %s: %s non se puideron escribir %d elementos en %s: %s o car?cter delimitador non ? un car?cter de byte simple erro no subproceso esper?base \ despois de `a', `c' ou `i' se esperaba unha versi?n de sed m?is nova caracteres extra despois da instrucci?n referencia \%d non v?lida no lado dereito do comando `s' non se pode usar +N ou ~N como primeira direcci?n uso non v?lido da direcci?n de li?a 0 falta unha instrucci?n m?ltiples `!'s m?ltiples opci?ns `g' para a instrucci?n `s' m?ltiples opci?ns `p' para a instrucci?n `s' m?ltiples opci?ns num?ricas para a instrucci?n `s' non hai ficheiros de entrada non hai unha expresi?n regular anterior unha opci?n num?rica para a instrucci?n `s' non pode ser cero a opci?n `e' non est? soportada erro de lectura en %s: %s as cadeas para a instrucci?n `y' te?en lonxitudes diferentes super-sed versi?n %s
 `,' inesperada `}' inesperado instrucci?n desco?ecida:`%c' opci?n de `s' desco?ecida `{' sen parella instrucci?n `s' non rematada instrucci?n `y' non rematada expresi?n regular de enderezo non rematada 