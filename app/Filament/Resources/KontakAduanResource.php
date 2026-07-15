<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KontakAduanResource\Pages;
use App\Models\KontakAduan;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class KontakAduanResource extends Resource
{
    protected static ?string $model = KontakAduan::class;

    protected static ?string $navigationIcon  = 'heroicon-o-inbox-stack';
    protected static ?string $navigationLabel = 'Aduan Masuk';
    protected static ?string $navigationGroup = 'Kontak';
    protected static ?int    $navigationSort   = 2;
    protected static ?string $modelLabel      = 'Aduan';
    protected static ?string $pluralModelLabel = 'Aduan Masuk';

    // Navigasi badge: jumlah aduan baru
    public static function getNavigationBadge(): ?string
    {
        $count = KontakAduan::baru()->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    // ── Form (hanya untuk edit status + catatan admin) ────────────────────────

    public static function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Data Pengirim (Tidak Dapat Diubah)')
                ->description('Data ini dikirim oleh publik dan tidak dapat diedit.')
                ->schema([
                    Placeholder::make('nama_pengadu')
                        ->label('Nama Pengadu')
                        ->content(fn (?KontakAduan $record) => $record?->nama_pengadu ?? '—'),

                    Placeholder::make('email')
                        ->label('Email')
                        ->content(fn (?KontakAduan $record) => $record?->email ?? '—'),

                    Placeholder::make('no_telp')
                        ->label('No. Telepon')
                        ->content(fn (?KontakAduan $record) => $record?->no_telp ?? '—'),

                    Placeholder::make('subjek')
                        ->label('Subjek')
                        ->content(fn (?KontakAduan $record) => $record?->subjek ?? '—')
                        ->columnSpanFull(),

                    Placeholder::make('isi_aduan')
                        ->label('Isi Aduan')
                        ->content(fn (?KontakAduan $record) => $record?->isi_aduan ?? '—')
                        ->columnSpanFull(),
                ])
                ->columns(3)
                ->collapsible(),

            Section::make('Audit Keamanan')
                ->schema([
                    Placeholder::make('recaptcha_score')
                        ->label('Skor reCAPTCHA')
                        ->content(fn (?KontakAduan $record) => $record
                            ? number_format($record->recaptcha_score, 2) . ' / 1.00'
                            . ($record->kemungkinanBot() ? ' — Perhatian: Skor rendah (kemungkinan bot)' : ' — Normal')
                            : '—'
                        ),

                    Placeholder::make('ip_address')
                        ->label('IP Pengirim')
                        ->content(fn (?KontakAduan $record) => $record?->ip_address ?? '—'),

                    Placeholder::make('created_at')
                        ->label('Waktu Masuk')
                        ->content(fn (?KontakAduan $record) => $record?->created_at?->format('d M Y, H:i:s') ?? '—'),
                ])
                ->columns(3)
                ->collapsible(),

            Section::make('Penanganan Admin')
                ->schema([
                    Select::make('status')
                        ->label('Status Penanganan')
                        ->required()
                        ->options([
                            KontakAduan::STATUS_BARU     => 'Baru',
                            KontakAduan::STATUS_DIPROSES => 'Sedang Diproses',
                            KontakAduan::STATUS_SELESAI  => 'Selesai',
                        ])
                        ->native(false),

                    Textarea::make('catatan_admin')
                        ->label('Catatan / Respon Admin')
                        ->helperText('Catatan ini bersifat internal dan tidak dikirim ke pengirim.')
                        ->rows(4)
                        ->maxLength(2000)
                        ->columnSpanFull(),
                ]),

        ]);
    }

    // ── Infolist (detail view) ────────────────────────────────────────────────

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            InfoSection::make('Data Pengirim')
                ->schema([
                    TextEntry::make('nama_pengadu')->label('Nama'),
                    TextEntry::make('email')->label('Email'),
                    TextEntry::make('no_telp')->label('Telepon')->placeholder('—'),
                    TextEntry::make('subjek')->label('Subjek')->columnSpanFull(),
                    TextEntry::make('isi_aduan')->label('Isi Aduan')->columnSpanFull(),
                ])
                ->columns(3),

            InfoSection::make('Audit')
                ->schema([
                    TextEntry::make('recaptcha_score')
                        ->label('Skor reCAPTCHA')
                        ->formatStateUsing(fn (float $state) => number_format($state, 2) . ' / 1.00')
                        ->badge()
                        ->color(fn (KontakAduan $record) => $record->kemungkinanBot() ? 'danger' : 'success'),
                    TextEntry::make('ip_address')->label('IP Pengirim'),
                    TextEntry::make('created_at')->label('Waktu Masuk')->dateTime('d M Y, H:i:s'),
                ])
                ->columns(3),

            InfoSection::make('Status Penanganan')
                ->schema([
                    TextEntry::make('status')
                        ->label('Status')
                        ->badge()
                        ->color(fn (KontakAduan $record) => $record->statusColor()),
                    TextEntry::make('catatan_admin')->label('Catatan Admin')->placeholder('Belum ada catatan'),
                ]),
        ]);
    }

    // ── Table ─────────────────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Masuk')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('nama_pengadu')
                    ->label('Pengirim')
                    ->searchable()
                    ->weight('semibold')
                    ->description(fn (KontakAduan $r) => $r->email),

                TextColumn::make('subjek')
                    ->label('Subjek')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (KontakAduan $record) => $record->statusColor())
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        KontakAduan::STATUS_BARU     => 'Baru',
                        KontakAduan::STATUS_DIPROSES => 'Diproses',
                        KontakAduan::STATUS_SELESAI  => 'Selesai',
                        default                      => $state,
                    })
                    ->sortable(),

                TextColumn::make('recaptcha_score')
                    ->label('reCAPTCHA')
                    ->formatStateUsing(fn (float $state) => number_format($state, 2))
                    ->badge()
                    ->color(fn (KontakAduan $record) => $record->kemungkinanBot() ? 'danger' : 'gray')
                    ->tooltip('Skor di bawah 0.5 mengindikasikan kemungkinan bot')
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('ip_address')
                    ->label('IP')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        KontakAduan::STATUS_BARU     => 'Baru',
                        KontakAduan::STATUS_DIPROSES => 'Diproses',
                        KontakAduan::STATUS_SELESAI  => 'Selesai',
                    ])
                    ->native(false),

                TernaryFilter::make('mencurigakan')
                    ->label('Skor reCAPTCHA')
                    ->queries(
                        true:  fn ($q) => $q->where('recaptcha_score', '<', KontakAduan::RECAPTCHA_THRESHOLD),
                        false: fn ($q) => $q->where('recaptcha_score', '>=', KontakAduan::RECAPTCHA_THRESHOLD),
                    )
                    ->trueLabel('Mencurigakan (< 0.5)')
                    ->falseLabel('Normal (>= 0.5)'),
            ])
            ->actions([
                ViewAction::make()->iconButton(),
                Action::make('edit_status')
                    ->label('Ubah Status')
                    ->icon('heroicon-o-pencil-square')
                    ->iconButton()
                    ->form([
                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                KontakAduan::STATUS_BARU     => 'Baru',
                                KontakAduan::STATUS_DIPROSES => 'Sedang Diproses',
                                KontakAduan::STATUS_SELESAI  => 'Selesai',
                            ])
                            ->native(false),
                        Textarea::make('catatan_admin')
                            ->label('Catatan Admin')
                            ->rows(3),
                    ])
                    ->fillForm(fn (KontakAduan $record) => [
                        'status'         => $record->status,
                        'catatan_admin'  => $record->catatan_admin,
                    ])
                    ->action(fn (KontakAduan $record, array $data) => $record->update($data)),
            ])
            ->bulkActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKontakAduan::route('/'),
            'view'  => Pages\ViewKontakAduan::route('/{record}'),
            'edit'  => Pages\EditKontakAduan::route('/{record}/edit'),
        ];
    }
}
