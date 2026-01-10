Create PHP Code:

Name: CANG_LanguageCharFoundation

Fundamental Config File:

Set The Fundamental Language Characters:
LanguageChar : Upper -> range('A','Z')
LanguageChar : Lower -> range('a','z')
LanguageChar : Numeric -> range('0','9')
LanguageChar : ShortSpecial -> str_split('_-')
LanguageChar : FullSpecial -> str_split('#$%&+-@_')

Create PHP Class:

Name: CANG_LanguageCharClass

Set The Basic Language Name:
LanguageName : Alphabet_Upper
LanguageName : Alphabet_Lower
LanguageName : Alphabet_Mix
LanguageName : Numeric
LanguageName : Alphabet_Upper_Num
LanguageName : Alphabet_Lower_Num
LanguageName : Alphabet_Mix_Num
LanguageName : Alphabet_Mix_Num_SpecialShort
LanguageName : Alphabet_Mix_Num_SpecialFull

Set The Basic Language Type:
LanguageType : '[A-Z]'
LanguageType : '[a-z]'
LanguageType : '[A-Z,a-z]'
LanguageType : '[0-9]'
LanguageType : '[A-Z,0-9]'
LanguageType : '[a-z,0-9]'
LanguageType : '[A-Z,a-z,0-9]'
LanguageType : '[A-Z,a-z,0-9,-_]'
LanguageType : '[A-Z,a-z,0-9,-_]'

Set The Basic Language Description:
LanguageDescription : 'Alphabetical -> Simple: Capital letters'
LanguageDescription : 'Alphabetical -> Simple: Small letters'
LanguageDescription : 'Alphabetical -> Mix: Capital and Small letters'
LanguageDescription : 'Numerical -> Simple'
LanguageDescription : 'Alphabetical And Numerical -> Simple: Capital letters (Megaupload.com)'
LanguageDescription : 'Alphabetical and Numerical -> Simple: Small letters'
LanguageDescription : 'Alphabetical and Numerical -> Mix: Capital and Small letters'
LanguageDescription : 'Alphabetical and Numerical -> Mix: Capital/Small letters plus Short Special chars (YouTube.com)'
LanguageDescription : 'Alphabetical and Numerical -> Mix: Capital/Small letters plus Full Special chars (Safe Password)'

Set The Basic Language Range:
LanguageRange : LanguageChar[Upper]
LanguageRange : LanguageChar[Lower]
LanguageRange : LanguageChar[Upper], LanguageChar[Lower]
LanguageRange : LanguageChar[Numeric]
LanguageRange : LanguageChar[Upper], LanguageChar[Numeric]
LanguageRange : LanguageChar[Lower], LanguageChar[Numeric]
LanguageRange : LanguageChar[Upper], LanguageChar[Lower], LanguageChar[Numeric]
LanguageRange : LanguageChar[Upper], LanguageChar[Lower], LanguageChar[Numeric], LanguageChar[ShortSpecial]
LanguageRange : LanguageChar[Upper], LanguageChar[Lower], LanguageChar[Numeric], LanguageChar[FullSpecial]

Set The Basic Language Id:
LanguageId : 1
LanguageId : 2
LanguageId : 3
LanguageId : 4
LanguageId : 5
LanguageId : 6
LanguageId : 7
LanguageId : 8
LanguageId : 9

Set The Basic Language Order:
LanguageOrder : LanguageId, LanguageName, LanguageType, LanguageDescription, LanguageRange

Set The Basic Language Definition:
LanguageDefinition -> LanguageOrder

Name: Complex Alpha Numeric Generator (CANG)

Create PHP CANG Class:

Able To Select Language:
LanguageDefinition

Able To Change Length:
Minimum Length = 8

Able To Select Generation Mode:
GenerateMode : Beginning, Current, Next, Previous, End, Random, Id

Example Mode Selection:
Mode : Beginning(:NoInPut:)
Mode : Current(:InPutString:)
Mode : Next(:InPutString:)
Mode : Previous(:InPutString:)
Mode : End(:NoInPut:)
Mode : Random(:NoInPut:)
Mode : Id(:InPutNumber:)

Able To Read Position:
Example:
AAAAAAAA = 0
AAAAAAAB = 1
AAAAAAAC = 2

ReadMe:
Write Nice Referencial ReadMe MarkDown With Logic And Development For GitHub About The CANG That Includes Language Char Followed By Language Definition And Language Selector To Gain Settings For CANG And Generate OutPut String That Is Compatible According To
Language Name:
1 => 'Alphabet_Upper',
2 => 'Alphabet_Lower',
3 => 'Alphabet_Mix',
4 => 'Numeric',
5 => 'Alphabet_Upper_Num',
6 => 'Alphabet_Lower_Num',
7 => 'Alphabet_Mix_Num',
8 => 'Alphabet_Mix_Num_SpecialShort',
9 => 'Alphabet_Mix_Num_SpecialFull'

Language Type:
1 => '[A-Z]',
2 => '[a-z]',
3 => '[A-Z,a-z]',
4 => '[0-9]',
5 => '[A-Z,0-9]',
6 => '[a-z,0-9]',
7 => '[A-Z,a-z,0-9]',
8 => '[A-Z,a-z,0-9,-_]',
9 => '[A-Z,a-z,0-9,-_]'

Language Description:
1 => 'Alphabetical -> Simple: Capital letters',
2 => 'Alphabetical -> Simple: Small letters',
3 => 'Alphabetical -> Mix: Capital and Small letters',
4 => 'Numerical -> Simple',
5 => 'Alphabetical And Numerical -> Simple: Capital letters (Megaupload.com)',
6 => 'Alphabetical and Numerical -> Simple: Small letters',
7 => 'Alphabetical and Numerical -> Mix: Capital and Small letters',
8 => 'Alphabetical and Numerical -> Mix: Capital/Small letters plus Short Special chars (YouTube.com)',
9 => 'Alphabetical and Numerical -> Mix: Capital/Small letters plus Full Special chars (Safe Password)'
