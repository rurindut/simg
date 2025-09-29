<?php

namespace App\Filament\Filters\Constraints;

use App\Filament\Filters\Constraints\Operators\UsiaOperator;
use Filament\Tables\Filters\QueryBuilder\Constraints\Constraint;

class UsiaConstraint extends Constraint
{
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->label('Usia');
        $this->icon('heroicon-m-calculator');
        
        $this->operators([
            UsiaOperator::make('lebih_dari')
                ->label('Lebih dari')
                ->summary(fn ($operator) => 'Usia lebih dari ' . ($operator->getSettings()['value'] ?? '') . ' tahun')
                ->operator('>'),
                
            UsiaOperator::make('kurang_dari')
                ->label('Kurang dari')
                ->summary(fn ($operator) => 'Usia kurang dari ' . ($operator->getSettings()['value'] ?? '') . ' tahun')
                ->operator('<'),
        ]);
    }
    
    /**
     * Override untuk menghilangkan inverse operators
     */
    public function getOperatorSelectOptions(): array
    {
        $options = [];

        foreach ($this->getOperators() as $operatorName => $operator) {
            $operator->constraint($this);
            
            // Hanya tambahkan operator normal, skip inverse
            $options[$operatorName] = $operator->inverse(false)->getLabel();
            
            $operator->constraint(null);
            $operator->inverse(null);
        }

        return $options;
    }
}