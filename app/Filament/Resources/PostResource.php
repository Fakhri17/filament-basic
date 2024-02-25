<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Group;
use App\Models\Category;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\CheckboxColumn;


class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Content')
                    ->description('Create a new post')
                    ->collapsible()
                    ->schema([
                        Checkbox::make('is_published')
                            ->columnSpanFull()
                            ->label('Is Published'),
                        TextInput::make('title')
                            ->rules('min:3|max:255')
                            ->label('Title')
                            ->required(),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->unique('posts', 'slug')
                            ->required(),
                        Select::make('category_id')
                            ->options(
                                Category::all()->pluck('name', 'id')
                            )
                            ->label('Category'),
                        ColorPicker::make('color')
                            ->label('Color'),
                        MarkdownEditor::make('content')
                            ->label('Content')
                            ->columnSpanFull(),
                        TagsInput::make('tags')
                            ->columnSpanFull()
                            ->label('Tags')
                    ])->columnSpan(2)->columns(2),

                Group::make()->schema([
                    Section::make('Thumbnail')
                        ->description('Upload a thumbnail for the post')
                        ->collapsible()
                        ->schema([
                            FileUpload::make('thumbnail')
                                ->label('Thumbnail')
                                ->image()
                                ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/jpg'])
                                ->maxSize(3 * 1024)
                                ->required(),
                        ])->columnSpan(1),
                ]),

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),
                ColorColumn::make('color')
                    ->label('Color')
                    ->sortable(),
                ImageColumn::make('thumbnail')
                    ->label('Thumbnail'),
                TextColumn::make('tags')
                    ->label('Tags')
                    ->searchable()
                    ->sortable(),
                CheckboxColumn::make('is_published')
                    ->label('Is Published')
                    ->sortable(),

            ])
            ->filters([
                //
            ])
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
