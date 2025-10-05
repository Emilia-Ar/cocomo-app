<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


class EstimateRequest extends FormRequest
{
public function authorize(): bool { return true; }


public function rules(): array
{
$levels = 'in:muy_bajo,bajo,nominal,alto,muy_alto,extra_alto';
return [
'kloc' => ['required','numeric','gt:0'],
'mode' => ['required','in:organico,semiacoplado,empotrado'],
'salary' => ['required','numeric','gt:0'],
// 15 drivers
'RELY' => ["required", $levels],
'DATA' => ["required", $levels],
'CPLX' => ["required", $levels],
'TIME' => ["required", $levels],
'STOR' => ["required", $levels],
'VIRT' => ["required", $levels],
'TURN' => ["required", $levels],
'ACAP' => ["required", $levels],
'AEXP' => ["required", $levels],
'PCAP' => ["required", $levels],
'VEXP' => ["required", $levels],
'LTEX' => ["required", $levels],
'MODP' => ["required", $levels],
'TOOL' => ["required", $levels],
'SCED' => ["required", $levels],
];
}


public function messages(): array
{
return [
'kloc.gt' => 'KLOC debe ser mayor a 0.',
'salary.gt' => 'El salario debe ser mayor a 0.',
];
}
}