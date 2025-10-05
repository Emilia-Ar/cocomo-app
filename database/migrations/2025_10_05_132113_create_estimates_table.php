<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void
{
Schema::create('estimates', function (Blueprint $table) {
$table->id();
$table->decimal('kloc', 10, 3); // miles de LOC
$table->enum('mode', ['organico', 'semiacoplado', 'empotrado']);
$table->decimal('salary', 12, 2); // salario mensual promedio por persona


// JSON con niveles elegidos por factor (RELY, DATA, ...)
$table->json('drivers');


// Derivados
$table->decimal('eaf', 10, 6); // Effort Adjustment Factor
$table->decimal('pm', 12, 6); // Persona-Meses
$table->decimal('tdev', 10, 6); // Duración en meses
$table->decimal('p', 10, 6); // Personas promedio = PM / TDEV
$table->decimal('monthly_cost', 14, 2); // = P * salary
$table->decimal('total_cost', 16, 2); // = PM * salary


$table->timestamps();
$table->index(['mode']);
});
}


public function down(): void
{
Schema::dropIfExists('estimates');
}
};
