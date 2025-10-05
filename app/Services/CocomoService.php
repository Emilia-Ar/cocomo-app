<?php


namespace App\Services;


class CocomoService
{
/**
* Tabla de multiplicadores por factor y nivel (modelo intermedio COCOMO'81)
* Niveles válidos: muy_bajo, bajo, nominal, alto, muy_alto, extra_alto
*/
public static function driverMultipliers(): array
{
return [
'RELY' => ['muy_bajo'=>0.75,'bajo'=>0.88,'nominal'=>1.00,'alto'=>1.15,'muy_alto'=>1.40],
'DATA' => ['bajo'=>0.94,'nominal'=>1.00,'alto'=>1.08,'muy_alto'=>1.16],
'CPLX' => ['muy_bajo'=>0.70,'bajo'=>0.85,'nominal'=>1.00,'alto'=>1.15,'muy_alto'=>1.30,'extra_alto'=>1.65],
'TIME' => ['nominal'=>1.00,'alto'=>1.11,'muy_alto'=>1.30,'extra_alto'=>1.66],
'STOR' => ['nominal'=>1.00,'alto'=>1.06,'muy_alto'=>1.21,'extra_alto'=>1.56],
'VIRT' => ['bajo'=>0.94,'nominal'=>1.00,'alto'=>1.10,'muy_alto'=>1.15],
'TURN' => ['bajo'=>0.87,'nominal'=>1.00,'alto'=>1.07,'muy_alto'=>1.15],
'ACAP' => ['muy_bajo'=>1.46,'bajo'=>1.19,'nominal'=>1.00,'alto'=>0.86,'muy_alto'=>0.71],
'AEXP' => ['muy_bajo'=>1.29,'bajo'=>1.13,'nominal'=>1.00,'alto'=>0.91,'muy_alto'=>0.82],
'PCAP' => ['muy_bajo'=>1.42,'bajo'=>1.17,'nominal'=>1.00,'alto'=>0.86,'muy_alto'=>0.70],
'VEXP' => ['muy_bajo'=>1.19,'bajo'=>1.10,'nominal'=>1.00,'alto'=>0.90,'muy_alto'=>0.85],
'LTEX' => ['muy_bajo'=>1.14,'bajo'=>1.07,'nominal'=>1.00,'alto'=>0.95,'muy_alto'=>0.84],
'MODP' => ['muy_bajo'=>1.24,'bajo'=>1.10,'nominal'=>1.00,'alto'=>0.91,'muy_alto'=>0.82],
'TOOL' => ['muy_bajo'=>1.24,'bajo'=>1.10,'nominal'=>1.00,'alto'=>0.91,'muy_alto'=>0.83],
'SCED' => ['muy_bajo'=>1.23,'bajo'=>1.08,'nominal'=>1.00,'alto'=>1.04,'muy_alto'=>1.10],
];
}


/** Calcula el EAF (producto de multiplicadores) */
public static function calcEAF(array $levels): float
{
$table = self::driverMultipliers();
$eaf = 1.0;
foreach ($table as $key => $opts) {
$lvl = $levels[$key] ?? 'nominal';
if (!array_key_exists($lvl, $opts)) {
throw new \InvalidArgumentException("Nivel inválido para $key: $lvl");
}
$eaf *= $opts[$lvl];
}
return round($eaf, 6);
}


/**
* Coeficientes (a,b,c,d) por modo para COCOMO Intermedio
*/
public static function modeCoefficients(string $mode): array
{
return match ($mode) {
'organico' => ['a'=>3.2,'b'=>1.05,'c'=>2.5,'d'=>0.38],
'semiacoplado' => ['a'=>3.0,'b'=>1.12,'c'=>2.5,'d'=>0.35],
'empotrado' => ['a'=>2.8,'b'=>1.20,'c'=>2.5,'d'=>0.32],
default => throw new \InvalidArgumentException('Modo inválido'),
};
}


/** Devuelve [eaf, pm, tdev, p, monthly_cost, total_cost] */
public static function estimate(float $kloc, string $mode, float $salary, array $levels): array
{
if ($kloc <= 0) throw new \InvalidArgumentException('KLOC debe ser > 0');
if ($salary <= 0) throw new \InvalidArgumentException('Salario debe ser > 0');


$coeff = self::modeCoefficients($mode);
$eaf = self::calcEAF($levels);


$pm = $coeff['a'] * $eaf * pow($kloc, $coeff['b']); // Persona-Meses
$tdev = $coeff['c'] * pow($pm, $coeff['d']); // Meses
$p = $pm / $tdev; // Personas promedio
$monthly = $p * $salary; // $/mes
$total = $pm * $salary; // $ total


return [
round($eaf, 6), round($pm, 6), round($tdev, 6), round($p, 6),
round($monthly, 2), round($total, 2)
];
}
}