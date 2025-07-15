<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Estado;
use App\Models\Cidade;

class EstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desativa temporariamente a verificação de chaves estrangeiras
        Schema::disableForeignKeyConstraints();

        // Limpa as tabelas na ordem correta (filha, depois mãe)
        Cidade::truncate();
        Estado::truncate();

        // Reativa a verificação
        Schema::enableForeignKeyConstraints();

        // Agora, insere os dados dos estados
        Estado::insert([
            ['nome' => 'Acre', 'uf' => 'AC'],
            ['nome' => 'Alagoas', 'uf' => 'AL'],
            ['nome' => 'Amapá', 'uf' => 'AP'],
            ['nome' => 'Amazonas', 'uf' => 'AM'],
            ['nome' => 'Bahia', 'uf' => 'BA'],
            ['nome' => 'Ceará', 'uf' => 'CE'],
            ['nome' => 'Distrito Federal', 'uf' => 'DF'],
            ['nome' => 'Espírito Santo', 'uf' => 'ES'],
            ['nome' => 'Goiás', 'uf' => 'GO'],
            ['nome' => 'Maranhão', 'uf' => 'MA'],
            ['nome' => 'Mato Grosso', 'uf' => 'MT'],
            ['nome' => 'Mato Grosso do Sul', 'uf' => 'MS'],
            ['nome' => 'Minas Gerais', 'uf' => 'MG'],
            ['nome' => 'Pará', 'uf' => 'PA'],
            ['nome' => 'Paraíba', 'uf' => 'PB'],
            ['nome' => 'Paraná', 'uf' => 'PR'],
            ['nome' => 'Pernambuco', 'uf' => 'PE'],
            ['nome' => 'Piauí', 'uf' => 'PI'],
            ['nome' => 'Rio de Janeiro', 'uf' => 'RJ'],
            ['nome' => 'Rio Grande do Norte', 'uf' => 'RN'],
            ['nome' => 'Rio Grande do Sul', 'uf' => 'RS'],
            ['nome' => 'Rondônia', 'uf' => 'RO'],
            ['nome' => 'Roraima', 'uf' => 'RR'],
            ['nome' => 'Santa Catarina', 'uf' => 'SC'],
            ['nome' => 'São Paulo', 'uf' => 'SP'],
            ['nome' => 'Sergipe', 'uf' => 'SE'],
            ['nome' => 'Tocantins', 'uf' => 'TO']
        ]);
    }
}