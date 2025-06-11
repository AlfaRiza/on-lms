<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PricingResource\Pages;
use App\Filament\Resources\PricingResource\RelationManagers;
use App\Models\Pricing;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PricingResource extends Resource
{
    protected static ?string $model = Pricing::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Fieldset::make('Details')
                    ->schema([
                        /**
                         * Field 'name':
                         * Nama dari paket harga. Wajib diisi, maksimal 255 karakter, dan otomatis fokus saat form dibuka.
                         */

                        TextInput::make('name')
                            ->label('Nama')
                            ->placeholder('Masukkan nama')
                            ->maxLength(255)
                            ->required()
                            ->autofocus()
                            ->columnSpanFull(),

                        /**
                         * Field 'price':
                         * Harga paket dalam IDR. Wajib diisi, hanya menerima angka desimal, minimal 0.
                         */
                        TextInput::make('price')
                            ->label('Harga')
                            ->required()
                            ->numeric()
                            ->prefix('IDR')
                            ->rules(['min:0'])
                            ->inputMode('decimal'),

                        /**
                         * Field 'duration':
                         * Durasi paket dalam bulan. Wajib diisi, hanya menerima angka, minimal 1 bulan. Terdapat bantuan teks untuk penjelasan input.
                         */
                        TextInput::make('duration')
                            ->label('Durasi (Bulan)')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->inputMode('numeric')
                            ->suffix('bulan')
                            ->helperText('Masukkan durasi dalam bulan, minimal 1 bulan.')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('duration')
                    ->label('Durasi (Bulan)')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => "{$state} bulan"),

                TextColumn::make('price')
                    ->label('Harga')
                    ->sortable()
                    ->searchable()
                    ->money('IDR', true),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->timezone('Asia/Jakarta')->format('d F Y H:i')),

                TextColumn::make('updated_at')
                    ->label('Terakhir Update')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->timezone('Asia/Jakarta')->format('d F Y H:i')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPricings::route('/'),
            'create' => Pages\CreatePricing::route('/create'),
            'edit' => Pages\EditPricing::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
