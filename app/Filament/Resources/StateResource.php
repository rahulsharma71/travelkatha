<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StateResource\Pages;
use App\Models\State;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class StateResource extends Resource
{
    protected static ?string $model = State::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('State Name'),
                Forms\Components\Select::make('country_id')
                    ->label('Country')
                    ->required()
                    ->relationship('country', 'name'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')->label('State Name')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('country_id')->label('Country ID')->sortable()->searchable(),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ])
        ->headerActions([
            Tables\Actions\Action::make('import')
                ->label('Import CSV')
                ->action(function (array $data) {
                    // Access the file from the request directly
                    $file = request()->file('file');

                    // Check if the file is valid
                    if (!$file || !$file->isValid()) {
                        throw new \Exception('The uploaded file is not valid.');
                    }

                    // Read the CSV file into an array
                    $rows = array_map('str_getcsv', file($file->getRealPath()));

                    // Validate the CSV header (it should match the expected format)
                    $header = $rows[0];
                    if ($header !== ['id', 'name', 'country_id']) {
                        throw new \Exception('Invalid CSV header. Expected: id, name, country_id');
                    }

                    // Process the CSV data and insert it into the database
                    foreach (array_slice($rows, 1) as $row) {
                        State::create([
                            'name' => $row[1],        // Assuming the CSV has 'name' in the second column
                            'country_id' => $row[2],  // Assuming the CSV has 'country_id' in the third column
                        ]);
                    }

                    // Success message
                    session()->flash('success', 'States imported successfully.');
                })
                ->form([
                    Forms\Components\FileUpload::make('file')
                        ->label('Upload CSV File')
                        ->required()
                        ->acceptedFileTypes(['text/csv']),
                ]),
        ]);
}


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStates::route('/'),
            'create' => Pages\CreateState::route('/create'),
            'edit' => Pages\EditState::route('/{record}/edit'),
        ];
    }
}
