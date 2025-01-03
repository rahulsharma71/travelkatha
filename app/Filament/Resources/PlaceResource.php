<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlaceResource\Pages;
use App\Models\Place;
use App\Models\City;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class PlaceResource extends Resource
{
    protected static ?string $model = Place::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

  
    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Place Name')
                    ->required()
                    ->maxLength(255),
    
                Forms\Components\Select::make('city_id')
                    ->label('City')
                    ->relationship('city', 'name') // Assumes City model has a `name` column
                    ->required()
                    ->preload(),
    
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->required()
                    ->maxLength(1000),
    
                // Add the image upload field
                Forms\Components\FileUpload::make('image')
                    ->label('Place Image')
                    ->directory('places') // Directory to store the images
                    ->image() // Ensures only images are uploaded
                    ->maxSize(1024), // Max size in KB
            ]);
    }
    
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Place Name')
                    ->sortable()
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('description')
                    ->label('Description'),
    
                Tables\Columns\TagsColumn::make('city.name')
                    ->label('City'),
    
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
            'index' => Pages\ListPlaces::route('/'),
            'create' => Pages\CreatePlace::route('/create'),
            'edit' => Pages\EditPlace::route('/{record}/edit'),
        ];
    }
}
