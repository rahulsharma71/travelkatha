<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CityResource\Pages;
use App\Models\City;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class CityResource extends Resource
{
    protected static ?string $model = City::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('City Name')
                    ->required()
                    ->maxLength(255),
    
                Forms\Components\Select::make('state_id')
                    ->label('State')
                    ->relationship('state', 'name') // Assumes City model has a `state` relationship
                    ->required(),
    
                Forms\Components\Select::make('categories')
                    ->label('Categories')
                    ->relationship('categories', 'name')
                    ->multiple() // Allow selecting multiple categories
                    ->preload(),
    
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true) // Set default value to true (active)
                    ->required(),
    
                // Add the image upload field
                Forms\Components\FileUpload::make('image')
                    ->label('City Image')
                    ->directory('cities') // Directory to store the images
                    ->image() // Ensures only images are uploaded
                    ->maxSize(1024), // Max size in KB
            ]);
    }
    
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('City Name')
                    ->sortable()
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('state.name')
                    ->label('State'),
    
                Tables\Columns\TagsColumn::make('categories.name')
                    ->label('Categories'),
    
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active')
                    ->sortable()
                    ->toggleable(),
    
                // Add the image column
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    

    public static function getRelations(): array
    {
        return [
            // Add relation managers if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCities::route('/'),
            'create' => Pages\CreateCity::route('/create'),
            'edit' => Pages\EditCity::route('/{record}/edit'),
        ];
    }
}
