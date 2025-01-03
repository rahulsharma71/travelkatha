<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogCommentsResource\Pages;
use App\Filament\Resources\BlogCommentsResource\RelationManagers;
use App\Models\BlogComment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BlogCommentsResource extends Resource
{
    protected static ?string $model = BlogComment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Add form fields to create or edit blog comments
                Forms\Components\Textarea::make('comment')
                    ->label('Comment')
                    ->required()
                    ->maxLength(500),
                
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')  // Assuming you have a `user` relationship in the BlogComment model
                    ->required(),
                
                Forms\Components\Select::make('blog_post_id')
                    ->label('Blog Post')
                    ->relationship('blogPost', 'title')  // Assuming you have a `blogPost` relationship in the BlogComment model
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Display blog comment details
                Tables\Columns\TextColumn::make('comment')
                    ->label('Comment')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('blogPost.title')
                    ->label('Blog Post')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->sortable(),
            ])
            ->filters([
                // Add filters for the table if necessary
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),  // Optional delete action
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('delete')
                    ->label('Delete Selected')
                    ->action(function ($records) {
                        // Handle bulk delete logic here
                        foreach ($records as $record) {
                            $record->delete();
                        }
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Define any relationships if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogComments::route('/'),
            'create' => Pages\CreateBlogComments::route('/create'),
            'edit' => Pages\EditBlogComments::route('/{record}/edit'),
        ];
    }
}
