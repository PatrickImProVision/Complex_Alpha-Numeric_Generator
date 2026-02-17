---
Title: CANG Project Definition (External Rewrite)
Source: Rewrite of Base_CANG_CommandAI.md
Status: Development Ready
---

## 1) Purpose
CANG (Complex Alpha Numeric Generator) is a PHP utility for generating and converting alphanumeric codes.
This document defines behavior contracts; implementation details may vary.
Documentation style rule (shareability): use one arrow style `A -> B` for mappings/flows and sentence case for descriptive lines across all sections.
Project description: The Complex Alpha Numeric Generator (CANG) is a PHP-based utility
designed to produce highly customizable alphanumeric strings for authentication systems,
token creation, unique identifiers, and data-randomization workflows.

Built for developers who need both flexibility and reliability, CANG supports layered
configuration options including character-set selection, entropy tuning, pattern
enforcement, and optional cryptographic strengthening.

CANG's architecture emphasizes modularity and clean integration. It can operate as a
standalone function, a reusable class, or a plug-in component within larger PHP
applications.

Its deterministic configuration model ensures repeatable behavior when needed, while still
offering strong randomness for security-sensitive contexts.

Whether you are generating session keys, invitation codes, randomized filenames, or
structured alphanumeric sequences, CANG provides a robust, developer-friendly foundation
that adapts to a wide range of project requirements.

Primary capabilities:
- Generate and convert by mode (`Beginy`, `Previous`, `Current`, `Next`, `End`, `Random`, `Id`)
- Return both `Code` and `Id` for conversion-aware operations
- Use selectable language profiles (character sets)
- Support deterministic and random workflows

## 2) Config Architecture
Core file design:
- `CANG_Config` -> complex configuration array that defines all language fundamentals.
- `CANG_Language` -> loads config data and prepares sortable language structures.
- `CANG_ProFile` -> imports language definitions from `CANG_Language`, selects language/profile, and applies developer-defined order.
- `CANG_Core` -> uses selected profile/order to run generation and conversion only.

Section-to-Unit mapping:
- `CANG_Config` -> Sections `2`, `3`, `4`, `5`
- `CANG_Language` -> Sections `4`, `5`
- `CANG_ProFile` -> Sections `5`, `6`
- `CANG_Core` -> Sections `6`, `7`, `8`, `10`, `11`, `12`

Core load flow:
- `Config -> Language -> ProFile -> Core`

## 3) Fundamental Config Symbols
`Language_Symbol` definitions:
- `Upper -> range('A','Z')`
- `Lower -> range('a','z')`
- `Numeric -> range('0','9')`
- `CharShort -> str_split('_-')`
- `CharLong -> str_split('#$%&+-@')`
- Core Language_Symbol rule: each `Language_Symbol` name (example: `CharLong`) must be followed by a defined range.
- Core range-constructor rule: both `range(...)` and `str_split(...)` are valid ways to create symbol ranges.

Regex-safe note:
- Applies only to true regex-processing patterns; it does not apply to `Language_Type` references or `Language_Display` values.


## 4) Canonical Language Definitions
Each language profile is one complete record.

Strict-name scopes:
- Core Strict_Name rule (profile record fields only): `Language_Id`, `Language_Name`, `Language_Type`, `Language_Description`, `Language_Range`.
- Core Strict_Name rule (language config maps): `Language_Symbol`, `Language_Display`.
- Core Strict_Name permanent rule: these exact key names are fixed and must be remembered/kept forever for compatibility.

Required profile fields:
- `Language_Id` (int)
- `Language_Name` (string)
- `Language_Type` (ordered `Language_Display` references)
- `Language_Description` (string)
- `Language_Range` (ordered symbol groups)

Language display map:
- `Language_Display['Upper'] = '[A-Z]'`
- `Language_Display['Lower'] = '[a-z]'`
- `Language_Display['Numeric'] = '[0-9]'`
- `Language_Display['CharShort'] = '[-_]'`
- `Language_Display['CharLong'] = '[#$%&+-@]'`
- Core Language_Type value rule: every `Language_Type` item must be a `Language_Display['Field_Name']` reference.
- Core Language_Type shape rule: input may be single reference or list of references (hybrid input allowed).
- Core Language_Type runtime rule: always normalize to list form internally before processing.
- Core Language_Type ordering rule: reordering applies only when normalized list has more than one item; one item is fixed-order (no-op).
- Core Language_Type reference rule: every referenced `Language_Display['Field_Name']` key must exist in `Language_Display`.
- Core Language_Type validation rule: regex validity/escaping is not required for `Language_Type` items because they are references, not regex patterns.
- Core Language_Type format examples: `Language_Type: Language_Display['Upper']` or `Language_Type: [Language_Display['Upper'], Language_Display['Lower']]`.

Language symbol map:
- `Language_Symbol` is defined through `Language_Range` references as `Language_Symbol[Name]`.
- Core Language_Range rule: `Language_Range` holds the range of symbols.
- Core Language_Range ordering rule: ordering is possible only when `Language_Range` contains more than one key symbol name.
- If `Language_Range` contains only one key symbol name, ordering is not applicable (no-op) and the single order is fixed.

Canonical profiles:
1. `Language_Id: 0`
- `Language_Name: Alphabet_Upper`
- `Language_Type: Language_Display['Upper']`
- `Language_Description: Alphabetical -> Simple: Capital Letters`
- `Language_Range: [Language_Symbol[Upper]]`

2. `Language_Id: 1`
- `Language_Name: Alphabet_Lower`
- `Language_Type: Language_Display['Lower']`
- `Language_Description: Alphabetical -> Simple: Small Letters`
- `Language_Range: [Language_Symbol[Lower]]`

3. `Language_Id: 2`
- `Language_Name: Alphabet_Mix`
- `Language_Type: [Language_Display['Upper'], Language_Display['Lower']]`
- `Language_Description: Alphabetical -> Mix: Capital And Small Letters`
- `Language_Range: [Language_Symbol[Upper], Language_Symbol[Lower]]`

4. `Language_Id: 3`
- `Language_Name: Numeric`
- `Language_Type: Language_Display['Numeric']`
- `Language_Description: Numerical -> Simple`
- `Language_Range: [Language_Symbol[Numeric]]`

5. `Language_Id: 4`
- `Language_Name: Alphabet_Upper_Num`
- `Language_Type: [Language_Display['Upper'], Language_Display['Numeric']]`
- `Language_Description: Alphabetical And Numerical -> Simple: Capital Letters (Microsoft/Megaupload.com)`
- `Language_Range: [Language_Symbol[Upper], Language_Symbol[Numeric]]`

6. `Language_Id: 5`
- `Language_Name: Alphabet_Lower_Num`
- `Language_Type: [Language_Display['Lower'], Language_Display['Numeric']]`
- `Language_Description: Alphabetical And Numerical -> Simple: Small Letters`
- `Language_Range: [Language_Symbol[Lower], Language_Symbol[Numeric]]`

7. `Language_Id: 6`
- `Language_Name: Alphabet_Mix_Num`
- `Language_Type: [Language_Display['Upper'], Language_Display['Lower'], Language_Display['Numeric']]`
- `Language_Description: Alphabetical And Numerical -> Mix: Capital And Small Letters`
- `Language_Range: [Language_Symbol[Upper], Language_Symbol[Lower], Language_Symbol[Numeric]]`

8. `Language_Id: 7`
- `Language_Name: Alphabet_Mix_Num_CharShort`
- `Language_Type: [Language_Display['Upper'], Language_Display['Lower'], Language_Display['Numeric'], Language_Display['CharShort']]`
- `Language_Description: Alphabetical And Numerical -> Mix: Capital/Small Letters Plus Short Special Chars (YouTube.com)`
- `Language_Range: [Language_Symbol[Upper], Language_Symbol[Lower], Language_Symbol[Numeric], Language_Symbol[CharShort]]`

9. `Language_Id: 8`
- `Language_Name: Alphabet_Mix_Num_CharLong`
- `Language_Type: [Language_Display['Upper'], Language_Display['Lower'], Language_Display['Numeric'], Language_Display['CharLong']]`
- `Language_Description: Alphabetical And Numerical -> Mix: Capital/Small Letters Plus Long Special Chars (Safe Password)`
- `Language_Range: [Language_Symbol[Upper], Language_Symbol[Lower], Language_Symbol[Numeric], Language_Symbol[CharLong]]`

10. `Language_Id: 9`
- `Language_Name: Alphabet_Mix_Num_CharMix`
- `Language_Type: [Language_Display['Upper'], Language_Display['Lower'], Language_Display['Numeric'], Language_Display['CharShort'], Language_Display['CharLong']]`
- `Language_Description: Alphabetical And Numerical -> Mix: Capital/Small Letters Plus Full Special Chars (Safe Password)`
- `Language_Range: [Language_Symbol[Upper], Language_Symbol[Lower], Language_Symbol[Numeric], Language_Symbol[CharShort], Language_Symbol[CharLong]]`

## 5) Language Order and Defaults
`CANG_ProFile` must support language selection and order override.

Order contract:
- Core Language_Order directional rule: `Language_Order` is AI-directional description for selected-language mapping and reorder behavior.
- `Language_Order(Same Values According To Selected Language_Id) -> ReOrderAble Fields By Developer/User Input`
- Core directional-term rule: `ReOrderAble` is intentional AI directional description and must be kept as defined.

Selection contract:
- `Default_Language -> Language_Id`
- `Default_Order` must be built only from `Language_Range` symbols of the selected `Default_Language`.
- `CANG_ProFile` must include the selected language definition imported from `CANG_Language` and order only those available symbols.
- Global template example (config-level): `[Language_Symbol[Upper], Language_Symbol[Lower], Language_Symbol[Numeric], Language_Symbol[CharShort], Language_Symbol[CharLong]]`.

Custom order example:
- `Default_Language -> Language_Id`
- `Default_Order -> [Language_Symbol[CharLong], Language_Symbol[CharShort], Language_Symbol[Numeric], Language_Symbol[Lower], Language_Symbol[Upper]]` (apply only if these symbols exist in selected language definition).

Compiled alphabet rule:
- `Compiled_Alphabet` = concatenation of active `Language_Range` groups in selected order.
- Duplicate characters must be removed while preserving first occurrence.
- Alphabet size must be `>= 2`.

## 6) Length Rules
- `Length` is mandatory for generated code output.
- If `Length` is missing, throw `ERR_LENGTH_REQUIRED`.
- `Length` must be a positive integer.
- Never accept `0`; throw `ERR_INVALID_LENGTH`.
- Absolute minimum allowed by engine is `1`.
- `Min_Length = 8` is a recommended default policy example, not a hard engine floor.
- If project policy sets a higher minimum (example `8`), values below that policy minimum must throw `ERR_INVALID_CODE_LENGTH`.
- For code-input modes, `strlen(Code)` must match `Length`; otherwise throw `ERR_LENGTH_MISMATCH`.

## 7) Mode Contracts
All modes run against one selected profile and one compiled alphabet.

Mode definition order must remain (documentation/reference order only, not required runtime invocation order):
1. `Beginy`
2. `Previous`
3. `Current`
4. `Next`
5. `End`
6. `Random`
7. `Id`

Canonical mode names (keep as defined):
1. Beginy
2. Previous
3. Current
4. Next
5. End
6. Random
7. Id
- Core naming rule: capitalize the first letter of Variables and Methods in rules/examples.
- Core native-function rule: native PHP functions must keep their original names/casing (example: `strlen`, `random_int`).
- Core native-variable rule: if a native variable/constant is defined in capital letters, keep it exactly as native-defined.
- Method names use `Beginy/Previous/Current/Next/End/Random/Id`, while validation mode tokens use `BEGINY/PREVIOUS/CURRENT/NEXT/END/RANDOM/ID`.
- Variable style uses `Code/Id/Length/Number` and `$Code/$Id/$Length/$Number`.
- Capitalized Variables/Methods in this document are naming conventions for readability.

Unified return shape for these modes:
- `{ Code: string, Id: int }`
- Mini return example: `{Code:"AAAAAAAB", Id:1}`

Return contracts:
- `Beginy(Length)` -> `{Code, Id}`
- `Previous(Code, Length)` -> `{Code, Id}`
- `Current(Code, Length)` -> `{Code, Id}`
- `Next(Code, Length)` -> `{Code, Id}`
- `End(Length)` -> `{Code, Id}`
- `Random(Length)` -> `{Code, Id}`
- `Id(Number, Length)` -> `{Code, Id}`

Quick call block:
- `Beginy(Length)`
- `Previous(Code, Length)`
- `Id(Number, Length)`
- `Random(Length)`

Rules:
- For every mode above, `Length` must be explicitly specified.
- If missing, throw `ERR_LENGTH_REQUIRED`.
- Core validation method rule: `ValidateCore(Length, Code, Id): {Ok: bool, Status: string, Description: string}` is moved out of `CANG_Core` and currently on hold for a separate validation file.
- Core existence method rule: `CANG_Core` must expose `ExistenceCheck(Length, Code, Id): {Exists: bool, Status: string, Reason: string, Description: string}`.
- Core generator helper rules:
- `ConvertTo_Code(Id, Length): Code`
- `ConvertTo_Id(Code, Length): Id`
- `Generate_ByRandomCode(Length): {Code, Id}`
- Internal loop rule for retry/fallback paths: `For (Step = 0; Step < Limit; Step++)`.
- Canonical Id route rule (single source of truth) for public `Id(Number, Length)`:
- `1) Map Number -> Id` and generate `{Code, Id}`.
- `2) Pass output to `Current(Code, Length)` (validation is handled by external validator when enabled).
- `3) Run `ExistenceCheck`.
- Mapping example:
- `// Method inputs: $Number, $Length`
- `// Step 1: Apply canonical Id route step (map Number -> Id and generate {Code, Id}).`
- `// Step 2: Pass generated output to Current(Code, Length).`
- Pseudo-code example:
```php
$Output = Id($Number, $Length);      // {Code, Id}
Current($Output['Code'], $Length);   // external validation (on hold) can run before/after this step
ExistenceCheck($Length, $Output['Code'], $Output['Id']);
```
- Validation hold rule: `ValidateCore` is external and on hold; `CANG_Core` does not execute it.
- External validator target behavior (on hold): check `strlen(Code) == Length` (when `Code` is provided), then validate `Code` and `Id` conversion compatibility.
- Clean flow contract:
- `1) Current(...)` runs core conversion flow.
- `2) External validation (on hold)`: run before/after `Current(...)` as policy requires.
- `3) If invalid`: throw mapped `ERR_*`.
- `4) If valid`: return success info (or nothing).
- `5) API layer`: catch exception and format `{Ok:false,Error,Description}`.
- Developer success example: `{Ok:true,Status:"VALIDATION_PASSED",Description:"Inputs are valid for the selected mode."}`
- Developer input table (public modes):
- `BEGINY` -> required: `Length`
- `PREVIOUS` -> required: `Length`, `Code`
- `CURRENT` -> required: `Length`, `Code`
- `NEXT` -> required: `Length`, `Code`
- `END` -> required: `Length`
- `RANDOM` -> required: `Length`
- `ID` -> required: `Length`, `Number`
- `Reverse` is internal-only compatibility alias helper, not a public validation mode.
- Core reverse-usage rule: `Reverse` must not be used in canonical execution flow diagrams.
- Core reverse-routing rule: if implemented, `Reverse` only routes to `ConvertTo_Id` or `ConvertTo_Code` based on input type; it must not define separate conversion logic.
- `Current` does not perform reverse conversion because it represents the same input `Code` in the current sequence position.
- `Current` must return the exact current `{Code, Id}` pair for sequencing accuracy (`Code`, `Length`, `Id`); length/input validation is handled by external validator (on hold).
- Core `Current` rule: input `Code` is treated as current-sequence reference by default.
- `Current` must not execute validation internally while validation is on hold outside `CANG_Core`.
- `Current` is the reference state used to define `Previous`, `Next`, `Beginy`, and `End` sequence behavior.
- Core conversion rule: `Id` is used for conversion at any `Sequence_OP` (directly or indirectly).
- Core sequencing anchor rule: `Beginy` is the first output point that defines sequence start context.
- Core Beginy rule: `Beginy` must internally produce initial `Code` from `Length`, then use that produced `Code` as the input context for `Current` and validation flow.
- After anchor, sequencing methods use `{Code, Length}` context for progression (`Previous(Code, Length)` / `Next(Code, Length)` compatibility model).
- Core execution reference rule for `Previous/Next` (runtime-aligned):
- `1) (Previous(Code, Length) | Next(Code, Length)) -> Current(Code, Length)`.
- `2) Current(Code, Length) -> ConvertTo_Id -> Sequence_OP -> ExistenceCheck`.
- Validation is external/on hold and does not run inside `Current`.
- Internal chaining rule: `Previous` and `Next` resolve current position first through `Current` (internally using `ConvertTo_Id` for position resolution), then adjust `Id` and generate final output through internal `ConvertTo_Code`.
- Exact algorithm for `Previous/Next`:
- `1) $PrevNumber = Current(Code, Length).Id - 1`, then `$PrevCode = ConvertTo_Code($PrevNumber, Length)`.
- `2) $NextNumber = Current(Code, Length).Id + 1`, then `$NextCode = ConvertTo_Code($NextNumber, Length)`.
- `Previous` returns `{Code:$PrevCode, Id:$PrevNumber}` and `Next` returns `{Code:$NextCode, Id:$NextNumber}` after conversion compatibility is applied.
- Boundary rule: if `Previous` is called on the first code, throw `ERR_SEQUENCE_START`; if `Next` is called on the last code, throw `ERR_SEQUENCE_END`.
- `Id(Number, Length)` output contract is defined by the canonical Id route rule and conversion rules above.
- `Current` mode:
- `Current -> ConvertTo_Id/ConvertTo_Code (core operations only)`
- Shared sequencing block (`Beginy`, `Previous`, `Next`, `End`):
- `Mode -> (Current -> (Sequence_OP -> ExistenceCheck))`
- `Exists` -> deterministic fallback walk (`Backward`/`Forward`)
- `Not Exists` -> return/commit
- `Random` mode:
- `Random_OP -> Current -> ExistenceCheck`
- `Exists` -> regenerate/retry
- `Not Exists` -> return/commit
- `Random` must not use deterministic fallback walk.
- `Id` mode:
- Core Id mode flow: see canonical Id route rule defined above (single source of truth).
- Generate by `Id:Number` mapping, then apply existence decision rules.
- For deterministic fallback walk: step = generate candidate -> `ExistenceCheck`.
- Stop on boundary/limit -> throw conflict error.
- Commit safety rule: after `Exists=false`, insert/commit with unique constraint (or lock) to avoid race conditions.
- Summary: `Random = regenerate`; deterministic modes = advance to next valid candidate, not same-input retry.
- Core uniqueness baseline: `Runtime` tracking is a dedicated first-stage source in `ExistenceCheck` flow.
- External uniqueness source strategy (`DB/Cache/Memory/Callback`) is supported through `ExistenceCheck(...)` source adapters after `Runtime` stage.
- Core retry safety defaults: `Random_Max_Retries = 1000` and `Max_Random_Time_Ms = 200`.
- Deterministic fallback safety defaults: `Deterministic_Max_Steps = 1000` and `Deterministic_Max_Time_Ms = 200`.
- If random uniqueness cannot complete within retry/time limits (or code space is exhausted), throw `ERR_RANDOM_SPACE_EXHAUSTED`.
- If deterministic fallback cannot complete within step/time limits (or boundary is reached with no available candidate), throw `ERR_SEQUENCE_CONFLICT`.
- Strict range rule for `Id`: define `MaxId = (N^Length) - 1`; if `Number > MaxId`, throw `ERR_ID_OUT_OF_RANGE` and do not auto-expand `Length`.
- All modes must support conversion compatibility between one another through `{Code, Id}`.

## 8) Positioning Model (Code <-> Id)
Use base-N conversion:
- `N = Len(Compiled_Alphabet)`
- Leftmost char is most significant digit.
- Digit value = index of char in compiled alphabet.

`ConvertTo_Id(Code, Length) -> Id`:
- Parse `Code` as base-N integer.
- Used by `Current`, `Previous`, and `Next` (and internal `Reverse` helper when needed).

`ConvertTo_Code(Id, Length) -> Code`:
- Convert integer to base-N and left-pad with first alphabet character to required `Length`.
- Used by `Beginy`, `Previous`, `Next`, `End`, `Random`, and `Id` (and internal `Reverse` helper when needed).

Example with profile where first char is `A`:
- `AAAAAAAA` -> `0`
- `AAAAAAAB` -> `1`
- `AAAAAAAC` -> `2`

Important:
- These examples are valid only when compiled alphabet starts with `A` and `Length = 8`.

## 9) Validation (On Hold) and Errors
Runtime error contract:
- Core behavior (CANG_Core): throw exceptions only for core operation errors (typed exception recommended, e.g., CANG_Exception) with stable error key.
- Boundary behavior (API/UI layer): catch core exceptions and return only important error data: `{Ok:false,Error,Description}`.
- `Description` must be short, human-readable, and safe (no internal stack traces or sensitive data).

Validation (external file, on hold):
- `Profile_Id` must exist.
- Input `Code` must contain only characters from compiled alphabet.
- `Length` must be provided for generation/conversion modes.
- `Length` must be integer >= 1.
- For code-input modes, `strlen(Code)` must equal `Length`.
- If project policy minimum is enabled (example `8`), enforce that minimum.
- `Id` must be integer >= 0.
- For `Id(Number, Length)`, `Number` must satisfy `0 <= Number <= (N^Length)-1`; otherwise throw `ERR_ID_OUT_OF_RANGE`.

Suggested error keys:
- `ERR_PROFILE_NOT_FOUND`
- `ERR_INVALID_ALPHABET`
- `ERR_INVALID_CODE_CHAR`
- `ERR_INVALID_CODE_LENGTH`
- `ERR_INVALID_ID`
- `ERR_ID_OUT_OF_RANGE`
- `ERR_MODE_INPUT_REQUIRED`
- `ERR_LENGTH_REQUIRED`
- `ERR_INVALID_LENGTH`
- `ERR_LENGTH_MISMATCH`
- `ERR_SEQUENCE_START`
- `ERR_SEQUENCE_END`
- `ERR_SEQUENCE_CONFLICT`
- `ERR_RANDOM_SPACE_EXHAUSTED`

## 10) Determinism and Security
- Deterministic operations: `Beginy`, `Previous`, `Current`, `Next`, `End`, `Id`.
- Non-deterministic operation: `Random`.
- `Random` must use cryptographically secure source in PHP (for example `random_int`).

## 11) Implementation Notes
- Keep one single source of truth for profiles (CANG_Config).
- CANG_Language should only resolve and normalize profile data.
- CANG_ProFile should only handle ordering/selection logic.
- CANG_Core should only perform generation/conversion operations.
- `ValidateCore(...)` is moved out of `CANG_Core` and is on hold for a separate validation file.
- CANG_Core must expose ExistenceCheck(Length, Code, Id): {Exists: bool, Status: string, Reason: string, Description: string} as active core method for uniqueness checks.



## 12) Core ExistenceCheck Sources
- This section defines source strategies used by active core `ExistenceCheck(...)`.
- Core method:
- `ExistenceCheck(Length, Code, Id): {Exists: bool, Status: string, Reason: string, Description: string}`
- Source priority order: `Runtime -> DB -> Cache -> Memory -> Callback`.
- Environment mode roles:
- `Development -> System Developer`
- `Testing -> BenchMark Validator`
- `Production -> DashBoard Administrator`
- Environment behavior mapping:
- `Development`: enabled sources -> `Runtime -> DB`; `Cache/Memory/Callback` optional; `CHECK_ERROR` in optional sources may continue with warning.
- `Testing`: enabled sources -> `Runtime -> DB -> Memory`; `Cache/Callback` optional; `CHECK_ERROR` fails test run by default.
- `Production`: enabled sources -> `Runtime -> DB -> Cache -> Memory -> Callback`; `DB` required; `CHECK_ERROR` from `Runtime/DB` stops flow immediately.
- Policy rule: deterministic/random collision handling stays the same across environments; only source availability and failure strictness change.
- Retry/error contract: `Random` retries on `Exists=true`; deterministic modes use fallback walk on `Exists=true`; throw on `CHECK_ERROR` unless policy says otherwise.
- Mini flow snippet:
- Sequencing modes (`Beginy`, `Previous`, `Next`, `End`): `Current -> Sequence_OP -> ExistenceCheck -> Return/Fallback`.
- `Current`: see canonical current core-operation flow in Section `7` (validation on hold/external).
- `Random`: `Random_OP -> Current -> ExistenceCheck -> Return/Retry`.
- `Id`: see canonical Id route rule (single source of truth), then `Return/Decision`.

Optional payload examples:
- Success (Exists=true): `{Exists:true,Status:"CHECK_SUCCESS",Reason:"VALID_INPUT",Description:"Code already exists."}`
- Success (Exists=false): `{Exists:false,Status:"CHECK_SUCCESS",Reason:"VALID_INPUT",Description:"Code is available."}`
- Failure: `{Exists:false,Status:"CHECK_ERROR",Reason:"SOURCE_FAILED",Description:"Database lookup failed."}`

Flow map:
- `Status=CHECK_CONFIG + Reason=NOT_CONFIGURED -> FLOW_EXISTENCE_CHECK_INPUT_NOT_READY`
- `Status=CHECK_ERROR + Reason=INVALID_INPUT -> FLOW_EXISTENCE_CHECK_INPUT_FAILED`
- `Status=CHECK_SUCCESS + Reason=VALID_INPUT -> FLOW_EXISTENCE_CHECK_INPUT_PASSED`
- `Status=CHECK_ERROR + Reason=SOURCE_FAILED -> FLOW_EXISTENCE_CHECK_SOURCE_FAILED`
