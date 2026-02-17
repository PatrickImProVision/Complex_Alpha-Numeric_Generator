
---
Project Name: Complex Alpha Numeric Generator (CANG)

Project Description: 'The Complex Alpha Numeric Generator (CANG) is a PHP‑based utility designed to produce highly customizable alphanumeric strings for use in authentication systems, token creation, unique identifiers, and data‑randomization workflows. Built for developers who need both flexibility and reliability, CANG supports layered configuration options including character‑set selection, entropy tuning, pattern enforcement, and optional cryptographic strengthening. CANG’s architecture emphasizes modularity and clean integration. It can operate as a standalone function, a reusable class, or a plug‑in component within larger PHP applications. Its deterministic configuration model ensures repeatable behavior when needed, while still offering strong randomness for security‑sensitive contexts. Whether you’re generating session keys, invitation codes, randomized filenames, or structured alphanumeric sequences, CANG provides a robust, developer‑friendly foundation that adapts to a wide range of project requirements.'

Project Type: PHP

Project Schema: Universal

Project Definition:

The Fundamental Language Symbols:
Language_Symbol : Upper -> range('A','Z')
Language_Symbol : Lower -> range('a','z')
Language_Symbol : Numeric -> range('0','9')
Language_Symbol : CharShort -> str_split('_-')
Language_Symbol : CharLong -> str_split('#$%&+-@')

The Basic Language Name:
Language_Name : Alphabet_Upper
Language_Name : Alphabet_Lower
Language_Name : Alphabet_Mix
Language_Name : Numeric
Language_Name : Alphabet_Upper_Num
Language_Name : Alphabet_Lower_Num
Language_Name : Alphabet_Mix_Num
Language_Name : Alphabet_Mix_Num_CharShort
Language_Name : Alphabet_Mix_Num_CharLong
Language_Name : Alphabet_Mix_Num_CharMix

The Basic Language Type:
Language_Type : '[A-Z]'
Language_Type : '[a-z]'
Language_Type : '[A-Z]', '[a-z]'
Language_Type : '[0-9]'
Language_Type : '[A-Z]', '[0-9]'
Language_Type : '[a-z]', '[0-9]'
Language_Type : '[A-Z]', '[a-z]', '[0-9]'
Language_Type : '[A-Z]', '[a-z]', '[0-9]', '[-_]'
Language_Type : '[A-Z]', '[a-z]', '[0-9]', '[#$%+-@]'
Language_Type : '[A-Z]', '[a-z]', '[0-9]', '[-_]', '[#$%+-@]'

The Basic Language Description:
Language_Description : 'Alphabetical -> Simple: Capital Letters'
Language_Description : 'Alphabetical -> Simple: Small Letters'
Language_Description : 'Alphabetical -> Mix: Capital And Small Letters'
Language_Description : 'Numerical -> Simple'
Language_Description : 'Alphabetical And Numerical -> Simple: Capital Letters (Megaupload.com)'
Language_Description : 'Alphabetical And Numerical -> Simple: Small Letters'
Language_Description : 'Alphabetical And Numerical -> Mix: Capital And Small Letters'
Language_Description : 'Alphabetical And Numerical -> Mix: Capital/Small Letters Plus Short Special Chars (YouTube.com)'
Language_Description : 'Alphabetical And Numerical -> Mix: Capital/Small Letters Plus Long Special Chars (Safe Password)'
Language_Description : 'Alphabetical And Numerical -> Mix: Capital/Small Letters Plus Full Special Chars (Safe Password)'

The Basic Language Range:
Language_Range : Language_Symbol[Upper]
Language_Range : Language_Symbol[Lower]
Language_Range : Language_Symbol[Upper], Language_Symbol[Lower]
Language_Range : Language_Symbol[Numeric]
Language_Range : Language_Symbol[Upper], Language_Symbol[Numeric]
Language_Range : Language_Symbol[Lower], Language_Symbol[Numeric]
Language_Range : Language_Symbol[Upper], Language_Symbol[Lower], Language_Symbol[Numeric]
Language_Range : Language_Symbol[Upper], Language_Symbol[Lower], Language_Symbol[Numeric], Language_Symbol[CharShort]
Language_Range : Language_Symbol[Upper], Language_Symbol[Lower], Language_Symbol[Numeric], Language_Symbol[CharLong]
Language_Range : Language_Symbol[Upper], Language_Symbol[Lower], Language_Symbol[Numeric], Language_Symbol[CharShort], Language_Symbol[CharLong]

The Basic Language Id:
Language_Id : 0
Language_Id : 1
Language_Id : 2
Language_Id : 3
Language_Id : 4
Language_Id : 5
Language_Id : 6
Language_Id : 7
Language_Id : 8
Language_Id : 9

---
ToDo List:
Config,
Language,
ProFile,
CANG

---
The Design Of The Files: Config, Language, ProFile, CANG

File Name: CANG_Config
File Description: It Is A Complex Array With Architecture Of Fundamental Configuration That Is Loaded InTo Language Class.

File Name: CANG_Language
File Description: It Is A Complex Language Class That Load The Config Of The Language InTo Sortable Order That Is Loaded InTo ProFile.

Set The Basic Language_Order(Language_Id, Language_Name, Language_Type, Language_Description) -> Language_Range

File Name: CANG_ProFile
File Description:It Is A Complex Language ProFile Class That Load The Language Class InTo SelectAble Order That Is Loaded InTo CANG Core.

The ProFile Must Be Able To Select The Symbol, Type, Range Of The Language And Order Them Accoding To Selection.
The Selection Of Language Is Based On Language_Id To Set Default_Language.
The Selection Of Language_Range Must Be Able To Language Rotate As Developer/User Decide To Sort By Canonical Order Language_Range, Language_Type
Example:

Language_Range() -> If More Than One The Order Does Work

Default_Language -> Language_Id
Default_Order -> Language_Range(Upper, Lower, Numeric, CharShort, CharLong)

Default_Language -> Language_Id
Default_Order -> Language_Range(CharLong, CharShort, Numeric, Lower, Upper)


File Name: CANG_Core
File Description: It Is A Complex CANG Class That Load The ProFile Class InTo The Fields To Select Mode And Lenght Of The Generated Code For Next Developer/User Process.

Able To Change Length:
Minimum Length = 8

Able To Select Generation Mode:
GenerateMode : Beginning, Previous, Current, Next, End, Random, Id

Example Mode Selection:
Mode : Beginning(:NoInPut:) -> Id(:InPutNumber:)
Mode : Previous(:InPutString:) -> Id(:InPutNumber:)
Mode : Current(:InPutString:) -> Id(:InPutNumber:)
Mode : Next(:InPutString:) -> Id(:InPutNumber:)
Mode : End(:NoInPut:) -> Id(:InPutNumber:)
Mode : Random(:NoInPut:) -> Id(:InPutNumber:)
Mode : Id(:InPutNumber:) -> Current(:InPutString:)

Able To Read Position: Id(:InPutNumber:)
Example:
 'Code Generation' / 'Program Friendly' / 'Developer/User Friendly'
AAAAAAAA = 0 -> 1
AAAAAAAB = 1 -> 2
AAAAAAAC = 2 -> 3


Code Array: Old Schema
	public function CodeArray(){
		$code_base = implode($this->code_char_base);
	if($this->default_code_length==strlen($code_base)){
			return array(
					'code_base'=>$code_base,
					'code_base_md5'=>md5($code_base),
					'code_base_sha1'=>sha1($code_base),
					'code_base64_encode'=>base64_encode($code_base),
					'code_max_number'=>$this->CodeType['code_max_number'],
					'code_pos_num'=>$this->code_pos_num,
					'code_time'=>$this->CodeType['code_generated_time'],
					'code_message'=>'is_acurrate',
					'code_name'=>$this->CodeType['code_name'],
					'code_description'=>$this->CodeType['code_description'],
					'code_type'=>$this->default_code_type,
					'code_max_type'=>$this->code_max_type,
					'code_length'=>$this->default_code_length
					);
		}elseif($this->default_code_length<strlen($code_base)){
			return array(
						'code_base'=>$code_base,
						'code_base_md5'=>md5($code_base),
						'code_base_sha1'=>sha1($code_base),
						'code_base64_encode'=>base64_encode($code_base),
						'code_max_number'=>$this->CodeType['code_max_number'],
						'code_pos_num'=>$this->code_pos_num,
						'code_time'=>$this->CodeType['code_generated_time'],
						'code_message'=>'is_upper_or_full',
						'code_name'=>$this->CodeType['code_name'],
						'code_description'=>$this->CodeType['code_description'],
						'code_type'=>$this->default_code_type,
						'code_max_type'=>$this->code_max_type,
						'code_length'=>$this->default_code_length
						);
		}else{
			return array(
						'code_base'=>$code_base,
						'code_base_md5'=>md5($code_base),
						'code_base_sha1'=>sha1($code_base),
						'code_base64_encode'=>base64_encode($code_base),
						'code_max_number'=>$this->CodeType['code_max_number'],
						'code_pos_num'=>$this->code_pos_num,
						'code_time'=>$this->CodeType['code_generated_time'],
						'code_message'=>'is_lower',
						'code_name'=>$this->CodeType['code_name'],
						'code_description'=>$this->CodeType['code_description'],
						'code_type'=>$this->default_code_type,
						'code_max_type'=>$this->code_max_type,
						'code_length'=>$this->default_code_length
						);
		}
	}