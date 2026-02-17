<?php
declare(strict_types=1);

if (!class_exists('CANG_ProFile', false)) {
    require_once __DIR__ . '/CANG_ProFile.php';
}
if (!class_exists('CANG_Core', false)) {
    require_once __DIR__ . '/CANG_Core.php';
}

final class CANG_Driver
{
    private int $Length = 8;
    private int $Type = 7;
    private string $Order_Key = 'Order_1';
    /** @var array<string, mixed>|null */
    private ?array $Selected_Profile = null;

    public function SetLength(int $Length): self
    {
        if ($Length < 1) {
            throw new InvalidArgumentException('ERR_LENGTH_REQUIRED');
        }
        $this->Length = $Length;
        return $this;
    }

    public function SetType(int $Type): self
    {
        if ($Type < 0) {
            throw new InvalidArgumentException('ERR_PROFILE_NOT_FOUND');
        }
        $this->Type = $Type;
        $this->Selected_Profile = null;
        return $this;
    }

    public function SetOrder(string $Order_Key): self
    {
        $this->Order_Key = $Order_Key;
        $this->Selected_Profile = null;
        return $this;
    }

    /**
     * Join CANG_ProFile + CANG_Core.
     * - Select profile by Type + Order.
     * - Setup core with Symbol + Index.
     *
     * @return array<string, mixed>
     */
    public function Load(): array
    {
        if ($this->Selected_Profile === null) {
            CANG_ProFile::SetId($this->Type);
            $Preview = CANG_ProFile::BuildOrderProfiles();
            if (!isset($Preview['Orders'][$this->Order_Key])) {
                $this->Order_Key = 'Order_1';
            }
            $this->Selected_Profile = CANG_ProFile::SelectOrder($this->Order_Key);
        }

        /** @var array<int, string> $Symbol_List */
        $Symbol_List = $this->Selected_Profile['Profile']['Symbol'];
        /** @var array<string, int> $Symbol_Index */
        $Symbol_Index = $this->Selected_Profile['Profile']['Index'];
        CANG_Core::Setup($Symbol_List, $Symbol_Index);

        return $this->Selected_Profile;
    }

    /**
     * Validate core internal configuration:
     * ValidateCore(Code, Length) -> Length must equal strlen(Code)
     *
     * @return array<string, mixed>
     */
    public function ValidateCore(string $Code, int $Length): array
    {
        if ($Length !== strlen($Code)) {
            throw new InvalidArgumentException('ERR_LENGTH_MISMATCH');
        }

        $this->Load();
        if (!$this->IsIntSpaceSafe($Length)) {
            if (!$this->IsCodeInSelectedAlphabet($Code)) {
                throw new InvalidArgumentException('ERR_INVALID_ALPHABET');
            }

            return [
                'Ok' => true,
                'Status' => 'VALIDATION_PASSED_OVERFLOW_SAFE',
                'Description' => 'Length and alphabet are valid. Roundtrip check skipped due to integer-space limit.',
            ];
        }

        $Current = CANG_Core::Current($Code, $Length);
        $Check_Code = CANG_Core::ConvertTo_Code((int) $Current['Id'], $Length);
        if ($Check_Code !== $Code) {
            throw new InvalidArgumentException('ERR_INVALID_ALPHABET');
        }

        return [
            'Ok' => true,
            'Status' => 'VALIDATION_PASSED',
            'Description' => 'Length matches code and conversion is compatible.',
        ];
    }

    /** @return array<string, mixed> */
    public function Beginy(?int $Length = null): array
    {
        $Length = $Length ?? $this->Length;
        $this->Load();
        $Result = CANG_Core::Beginy($Length);
        $Validation = $this->ValidateCore((string) $Result['Code'], $Length);
        return $this->BuildUnifiedOutput($Result, $Length, $Validation);
    }

    /** @return array<string, mixed> */
    public function Previous(string $Code, ?int $Length = null): array
    {
        $Length = $Length ?? $this->Length;
        $Validation = $this->ValidateCore($Code, $Length);
        $Result = CANG_Core::Previous($Code, $Length);
        return $this->BuildUnifiedOutput($Result, $Length, $Validation);
    }

    /** @return array<string, mixed> */
    public function Current(string $Code, ?int $Length = null): array
    {
        $Length = $Length ?? $this->Length;
        $Validation = $this->ValidateCore($Code, $Length);
        $Result = CANG_Core::Current($Code, $Length);
        return $this->BuildUnifiedOutput($Result, $Length, $Validation);
    }

    /** @return array<string, mixed> */
    public function Next(string $Code, ?int $Length = null): array
    {
        $Length = $Length ?? $this->Length;
        $Validation = $this->ValidateCore($Code, $Length);
        $Result = CANG_Core::Next($Code, $Length);
        return $this->BuildUnifiedOutput($Result, $Length, $Validation);
    }

    /** @return array<string, mixed> */
    public function End(?int $Length = null): array
    {
        $Length = $Length ?? $this->Length;
        $this->Load();
        $Result = CANG_Core::End($Length);
        $Validation = $this->ValidateCore((string) $Result['Code'], $Length);
        return $this->BuildUnifiedOutput($Result, $Length, $Validation);
    }

    /** @return array<string, mixed> */
    public function Random(?int $Length = null): array
    {
        $Length = $Length ?? $this->Length;
        $this->Load();
        $Result = CANG_Core::Random($Length);
        $Validation = $this->ValidateCore((string) $Result['Code'], $Length);
        return $this->BuildUnifiedOutput($Result, $Length, $Validation);
    }

    /** @return array<string, mixed> */
    public function Id(int $Number, ?int $Length = null): array
    {
        $Length = $Length ?? $this->Length;
        $this->Load();
        $Result = CANG_Core::Id($Number, $Length);
        $Validation = $this->ValidateCore((string) $Result['Code'], $Length);
        return $this->BuildUnifiedOutput($Result, $Length, $Validation);
    }

    /**
     * Join output in Meta + Profile style.
     *
     * @param array<string, int|string> $Result
     * @param array<string, mixed> $Validation
     * @return array<string, mixed>
     */
    private function BuildUnifiedOutput(array $Result, int $Length, array $Validation): array
    {
        $Code = (string) $Result['Code'];
        $Id = (int) $Result['Id'];
        $Support = $this->AttachCodeSupport($Result, $Length);
        $Selected_Meta = (is_array($this->Selected_Profile) && isset($this->Selected_Profile['Meta']) && is_array($this->Selected_Profile['Meta']))
            ? $this->Selected_Profile['Meta']
            : [];

        return [
            'Meta' => [
                'Language_Id' => $this->Type,
                'Selected_Order' => $this->Order_Key,
                'Preview_Count' => (int) ($Selected_Meta['Preview_Count'] ?? 0),
                'Available_Order_Id' => (array) ($Selected_Meta['Available_Order_Id'] ?? []),
                'Length' => $Length,
                'Validation_Ok' => (bool) $Validation['Ok'],
                'Validation_Status' => (string) $Validation['Status'],
                'Validation_Description' => (string) $Validation['Description'],
            ],
            'Profile' => [
                'Code_String' => $Code,
                'Code_Id' => $Id,
                'Code_MD5' => (string) $Support['Code_md5'],
                'Code_SHA1' => (string) $Support['Code_sha1'],
                'Code_Base64_Encode' => (string) $Support['Code_base64_encode'],
                'Code_Time' => (int) $Support['Code_time'],
                'Code_Max_Id' => (int) $Support['Code_max_number'],
                'Code_Type' => (int) $Support['Code_type'],
                'Code_Max_Type' => (int) $Support['Code_max_type'],
                'Code_Name' => (string) $Support['Code_name'],
                'Code_Description' => (string) $Support['Code_description'],
            ],
        ];
    }

    /**
     * Attach extra code support fields:
     * - md5
     * - sha1
     * - base64_encode
     * - timestamp (microseconds, long integer)
     *
     * @param array<string, int|string> $Result
     * @return array<string, int|string>
     */
    private function AttachCodeSupport(array $Result, int $Length): array
    {
        $Code = (string) $Result['Code'];
        $Selected_Meta = (is_array($this->Selected_Profile) && isset($this->Selected_Profile['Meta']) && is_array($this->Selected_Profile['Meta']))
            ? $this->Selected_Profile['Meta']
            : [];
        $Result['Code_md5'] = md5($Code);
        $Result['Code_sha1'] = sha1($Code);
        $Result['Code_base64_encode'] = base64_encode($Code);
        $Result['Code_time'] = (int) round(microtime(true) * 1000000);
        $Result['Code_max_number'] = $this->GetCodeMaxNumber($Length);
        $Result['Code_type'] = $this->Type;
        $Result['Code_max_type'] = 9;
        $Result['Code_name'] = (string) ($Selected_Meta['Language_Name'] ?? '');
        $Result['Code_description'] = (string) ($Selected_Meta['Language_Description'] ?? '');

        return $Result;
    }

    private function GetCodeMaxNumber(int $Length): int
    {
        if (!is_array($this->Selected_Profile) || !isset($this->Selected_Profile['Profile']['Symbol']) || !is_array($this->Selected_Profile['Profile']['Symbol'])) {
            return 0;
        }

        $Base = count($this->Selected_Profile['Profile']['Symbol']);
        if ($Base < 2 || $Length < 1) {
            return 0;
        }

        $Max = 1;
        for ($I = 0; $I < $Length; $I++) {
            if ($Max > intdiv(PHP_INT_MAX, $Base)) {
                return PHP_INT_MAX;
            }
            $Max *= $Base;
        }

        return $Max - 1;
    }

    private function IsIntSpaceSafe(int $Length): bool
    {
        if (!is_array($this->Selected_Profile) || !isset($this->Selected_Profile['Profile']['Symbol']) || !is_array($this->Selected_Profile['Profile']['Symbol'])) {
            return false;
        }

        $Base = count($this->Selected_Profile['Profile']['Symbol']);
        if ($Base < 2 || $Length < 1) {
            return false;
        }

        $Max = 1;
        for ($I = 0; $I < $Length; $I++) {
            if ($Max > intdiv(PHP_INT_MAX, $Base)) {
                return false;
            }
            $Max *= $Base;
        }

        return true;
    }

    private function IsCodeInSelectedAlphabet(string $Code): bool
    {
        if (!is_array($this->Selected_Profile) || !isset($this->Selected_Profile['Profile']['Index']) || !is_array($this->Selected_Profile['Profile']['Index'])) {
            return false;
        }

        /** @var array<string, int> $Index */
        $Index = $this->Selected_Profile['Profile']['Index'];
        $Length = strlen($Code);
        for ($I = 0; $I < $Length; $I++) {
            if (!array_key_exists($Code[$I], $Index)) {
                return false;
            }
        }

        return true;
    }
}

/*
Usage Example: CANG_Driver Methods

Description:
- SetLength(Length): set default code length for generation methods.
- SetType(Type): select language profile id (0..9 in current language config).
- SetOrder(Order_Key): select one generated profile order (Order_1, Order_2, ...).
- Load(): resolve selected profile and configure CANG_Core.
- ValidateCore(Code, Length): validate input code against internal runtime alphabet and length.
- Beginy(Length?): get first sequence code/id for selected config.
- Previous(Code, Length?): get previous sequence code/id.
- Current(Code, Length?): resolve id for a given code.
- Next(Code, Length?): get next sequence code/id.
- End(Length?): get last sequence code/id.
- Random(Length?): get random valid code/id.
- Id(Number, Length?): get code/id from explicit numeric id.
*/

/*
// Runnable example (execute this file directly)
if (realpath((string) ($_SERVER['SCRIPT_FILENAME'] ?? '')) === __FILE__) {
    $Driver = new CANG_Driver();

    // Step 1: Runtime setup.
    $Driver->SetLength(8);       // default Length for methods when Length is omitted
    $Driver->SetType(7);         // profile id: Alphabet_Mix_Num_CharShort
    $Driver->SetOrder('Order_1'); // selected order from generated profile permutations
    $Selected_Profile = $Driver->Load();

    // Step 2: Generate first value, then move through sequence.
    $Beginy = $Driver->Beginy(); // first code/id
    $Next = $Driver->Next((string) $Beginy['Profile']['Code_String']);
    $Current = $Driver->Current((string) $Next['Profile']['Code_String']);
    $Previous = $Driver->Previous((string) $Current['Profile']['Code_String']);
    $End = $Driver->End();
    $Random = $Driver->Random();

    // Step 3: Generate by explicit numeric id.
    $By_Id = $Driver->Id(12345);

    // Step 4: Explicit validation example.
    $Validation = $Driver->ValidateCore((string) $Beginy['Profile']['Code_String'], 8);

    echo json_encode([
        'Selected_Profile' => $Selected_Profile,
        'Beginy' => $Beginy,
        'Next' => $Next,
        'Current' => $Current,
        'Previous' => $Previous,
        'End' => $End,
        'Random' => $Random,
        'By_Id' => $By_Id,
        'Validation' => $Validation,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
}
*/
