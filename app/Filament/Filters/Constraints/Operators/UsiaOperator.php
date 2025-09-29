<?php

namespace App\Filament\Filters\Constraints\Operators;

use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\QueryBuilder\Constraints\Operators\Operator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Log;

class UsiaOperator extends Operator
{
    protected string $operator = '>';
    
    public function operator(string $operator): static
    {
        $this->operator = $operator;
        return $this;
    }
    
    public function getFormSchema(): array
    {
        return [
            TextInput::make('value')
                ->numeric()
                ->required()
                ->suffix('tahun'),
        ];
    }
    
    public function apply(Builder $query, string $qualifiedColumn): Builder
    {
        // Log::debug($qualifiedColumn);
        $usia = $this->getSettings()['value'] ?? null;
        
        if (!$usia) {
            return $query;
        }
        
        // $tanggalBatas = now()->subYears($usia);
        $tanggalBatas = now()->subYears((int) $usia)->toDateString();
        
        if (! str_contains($qualifiedColumn, '.')) {
            return $this->applyOnColumn($query, $qualifiedColumn, $tanggalBatas);
        }

        $parts = explode('.', $qualifiedColumn);
        $column = array_pop($parts); // nama kolom terakhir
        $first = $parts[0] ?? null;
        $model = $query->getModel();
        $table = $model->getTable();

        // Kasus 'table.column' (mis. 'anggotas.tanggal_lahir') -> treat as column
        if ($first === $table) {
            return $this->applyOnColumn($query, $qualifiedColumn, $tanggalBatas);
        }

        // Cek apakah first part adalah method relasi pada model
        if ($first && method_exists($model, $first)) {
            try {
                $relation = $model->{$first}();
            } catch (\Throwable $e) {
                $relation = null;
            }

            if ($relation instanceof Relation) {
                $relationPath = implode('.', $parts); // bisa nested like rel.subrel
                return $query->whereHas($relationPath, function ($q) use ($column, $tanggalBatas) {
                    if ($this->operator === '>') {
                        $q->where($column, '<', $tanggalBatas);
                    } else {
                        $q->where($column, '>', $tanggalBatas);
                    }
                });
            }
        }

        // Fallback: treat as qualified column (alias.table.column) => direct where
        return $this->applyOnColumn($query, $qualifiedColumn, $tanggalBatas);
    }

    protected function applyOnColumn(Builder $query, string $qualifiedColumn, string $tanggalBatas): Builder
    {
        if ($this->operator === '>') {
            return $query->where($qualifiedColumn, '<', $tanggalBatas);
        }

        return $query->where($qualifiedColumn, '>', $tanggalBatas);
    }
}
