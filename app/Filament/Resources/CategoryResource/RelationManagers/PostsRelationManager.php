<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Category;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Group;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function form(Form $form): Form
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
                        // Select::make('category_id')
                        //     // ->relationship('category', 'name')
                        //     ->options(Category::all()->pluck('name', 'id'))
                        //     ->searchable()
                        //     ->label('Category')
                        //     ->required(),
                        ColorPicker::make('color')
                            ->label('Color'),
                        RichEditor::make('content')
                            ->label('Content')
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'blockquote',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'h1',
                                'h2',
                                'h3',
                                'h4',
                                'h5',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ]),
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

            ])->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('slug'),
                CheckboxColumn::make('is_published')
                    ->label('Is Published')
                    ->sortable(),


            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\ViewAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
