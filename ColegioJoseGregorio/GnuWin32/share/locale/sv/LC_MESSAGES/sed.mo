??    U      ?  q   l      0  ?   1  ,   ,  5   Y  N   ?  7   ?  \   	  _   s	  `   ?	  u   4
  l   ?
  b     V   z  Y   ?  ~   +  ?   ?  %   :     `     w     ?     ?     ?     ?     ?       $   *     O     a     |     ?     ?  #   ?     ?     ?     ?          !     3     E  H   R     ?     ?     ?  !   ?          )  (   >     g     z  #   ?     ?     ?  $   ?          :  #   T  B   x  2   ?     ?           #     A  *   `  *   ?     ?     ?     ?  #   ?  #     &   <     c     r  ,   ?     ?     ?  -   ?          0     ?     N     d     z     ?     ?     ?  ?  ?  ?   [  3   T  6   ?  U   ?  <     a   R  V   ?  g     ?   s  ?   ?  \   ?  \   ?  Y   N  ?   ?  ?   -  $   ?     ?     ?          /     C     Z     s     ?  (   ?     ?     ?            !     '   >     f     ?  !   ?     ?     ?     ?     ?  J        N      h      ?  ,   ?     ?     ?  5         C   $   a   $   ?      ?   '   ?   *   ?   #   !     ?!  )   ^!  O   ?!  +   ?!     "  (   "  "   @"     c"  2   ?"  5   ?"  #   ?"     #  	   #  $   '#  $   L#  &   q#     ?#  !   ?#  3   ?#     ?#     $  /   *$     Z$     p$     }$     ?$     ?$     ?$     ?$     ?$  (   ?$                .   5   0   6   ,   7         1   2   /   	          D      A       U      4              O               +   '   (   C      T          
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
PO-Revision-Date: 2008-02-24 17:20+0100
Last-Translator: Christian Rose <menthos@menthos.com>
Language-Team: Swedish <tp-sv@listor.tp-sv.se>
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
Plural-Forms: nplurals=2; plural=(n != 1);
 
Om ingen av flaggorna -e, --expression, -f, eller --file ges, blir det
första argumentet som inte är en flagga det sed-skript som tolkas. Alla
återstående argument är namn på indatafiler. Om inga indatafiler är angivna
läses standard in.

       --help     visa denna hjälptext och avsluta
       --version  visa versionsinformation och avsluta
   --follow-symlinks
                 följ symlänkar när behandling sker på plats
   --posix
                 inaktivera alla GNU-utökningar.
   -R, --regexp-perl
                 använd Perl 5:s syntax för reguljära uttryck i skriptet.
   -b, --binary
                 öppna filer i binärläge (CR+LF särbehandlas inte)
   -e skript, --expression=skript
                 lägg till skript till de kommandon som ska utföras
   -f skriptfil, --file=skriptfil
                 lägg till innehållet i skriptfil till de kommandon som ska
                 utföras
   -i[ÄNDELSE], --in-place[=ÄNDELSE]
                 redigera filer på plats (skapar säkerhetskopia om ändelse
                 tillhandahålls)
   -l N, --line-length=N
                 ange önskad radbrytningslängd för "l"-kommandot
   -n, --quiet, --silent
                 förhindrar automatisk utskrift av mönsterutrymme
   -r, --regexp-extended
                 använd utökade reguljära uttryck i skriptet.
   -s, --separate
                 betrakta filer som separata istället för som en
                 kontinuerlig lång dataström.
   -u, --unbuffered
                 läs in minimala mängder data från indatafilerna och töm
                 utdatabufferterna oftare
 %s: -e uttryck #%lu, tecken %lu: %s
 %s: kan inte läsa %s: %s
 %s: fil %s rad %lu: %s
 : vill inte ha några adresser GNU sed version %s
 Ogiltig bakåtreferens Ogiltigt teckenklassnamn Ogiltigt sorteringstecken Ogiltigt innehåll i \{\} Ogiltigt föregående reguljärt uttryck Ogiltigt intervallslut Ogiltigt reguljärt uttryck Minnet slut Ingen träff Inget tidigare reguljärt uttryck För tidigt slut på reguljärt uttryck Reguljärt uttryck för stort Lyckades Eftersläpande omvänt snedstreck Obalanserad ( eller \( Obalanserad ) eller \) Obalanserad [ eller [^ Obalanserad \{ Användning: %s [FLAGGA]... {skript-endast-om-inga-andra} [indatafil]...

 kommandot "e" stöds inte "}" vill inte ha några adresser baserad på GNU sed version %s

 kan inte hitta etiketten för hopp till "%s" kan inte ta bort %s: %s kan inte byta namn på %s: %s kan inte ange modifierare på tomt reguljärt uttryck kan inte ta status på %s: %s kommandot använder endast en adress kommentarer accepterar inga adresser kunde inte fästa vid %s: %s kunde inte redigera %s: är en terminal kunde inte redigera %s: inte en vanlig fil kunde inte följa symlänken %s: %s kunde inte öppna filen %s: %s kunde inte öppna temporära filen %s: %s kunde inte skriva %d objekt till %s: %s kunde inte skriva %d objekt till %s: %s avgränsningstecknet är inte en ensam byte fel i underprocess \ förväntades efter "a", "c" eller "i" nyare version av sed förväntades extra tecken efter kommandot ogiltig referens \%d på "s"-kommandots högersida ogiltig användning av +N eller ~N som första adress felaktig användning av radadress 0 kommando saknas flera "!" flera "g"-flaggor till "s"-kommandot flera "p"-flaggor till "s"-kommandot flera sifferflaggor till "s"-kommandot inga indatafiler inget tidigare reguljärt uttryck sifferflagga till kommandot "s" får inte vara noll flaggan "e" stöds inte läsfel vid %s: %s strängarna för kommandot "y" är olika långa super-sed version %s
 oväntat "," oväntad "}" okänt kommando: "%c" flaggan okänd för "s" obalanserad "{" oavslutat "s"-kommando oavslutat "y"-kommando oavslutat reguljärt uttryck för adress 