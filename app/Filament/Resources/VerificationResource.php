<?php

namespace App\Filament\Resources;

use App\Classes\GeniusMailer;
use App\Filament\Resources\VerificationResource\Pages;
use App\Models\Generalsetting;
use App\Models\Subscription;
use App\Models\UserSubscription;
use App\Models\Verification;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class VerificationResource extends Resource
{
    protected static ?string $model = Verification::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-badge';

    protected static ?string $navigationGroup = 'Vendor Verifications';

    protected static ?string $navigationLabel = 'All Verifications';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Verification Details')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Seller Name')
                            ->disabled()
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'Pending' => 'Pending',
                                'Verified' => 'Verified',
                                'Declined' => 'Declined',
                            ])
                            ->required(),

                        Forms\Components\Toggle::make('admin_warning')
                            ->label('Admin Warning')
                            ->required(),

                        Forms\Components\Textarea::make('warning_reason')
                            ->label('Warning Reason')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('text')
                            ->label('Verification Text')
                            ->columnSpanFull(),

                        Forms\Components\Placeholder::make('attachments_display')
                            ->label('Attachments')
                            ->content(fn (Verification $record): HtmlString => new HtmlString(
                                '<img src="'.asset('assets/images/'.$record->attachments).'" style="max-height: 500px; width: auto;" />'
                            ))
                            ->visible(fn (Verification $record) => ! empty($record->attachments))
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Sellers name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\ImageColumn::make('attachments')
                    ->label('Document')
                    ->disk('public')
                    ->circular()
                    ->getStateUsing(fn (Verification $record): string => asset('assets/images/'.$record->attachments)),

                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Verified' => 'Verified',
                        'Declined' => 'Declined',
                    ])
                    ->selectablePlaceholder(false),

                Tables\Columns\IconColumn::make('admin_warning')
                    ->label('Warning')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('secret_login')
                        ->label('Secret Login')
                        ->icon('heroicon-o-user')
                        ->color('info')
                        ->action(function (Verification $record) {
                            Auth::guard('web')->logout();
                            Auth::guard('web')->login($record->user);

                            return redirect()->route('vendor.dashboard');
                        }),

                    Tables\Actions\Action::make('add_new_plan')
                        ->label('Add New Plan')
                        ->icon('heroicon-o-plus')
                        ->color('success')
                        ->form([
                            Forms\Components\Select::make('subs_id')
                                ->label('Select Plan')
                                ->options(Subscription::all()->pluck('title', 'id'))
                                ->required(),
                        ])
                        ->action(function (Verification $record, array $data) {
                            $user = $record->user;
                            $subs = Subscription::findOrFail($data['subs_id']);
                            $settings = Generalsetting::findOrFail(1);
                            $today = Carbon::now()->format('Y-m-d');

                            $package = $user->subscribes()->where('status', 1)->orderBy('id', 'desc')->first();

                            $user->is_vendor = 2;
                            if (! empty($package)) {
                                if ($package->subscription_id == $data['subs_id']) {
                                    $newday = strtotime($today);
                                    $lastday = strtotime($user->date);
                                    $secs = $lastday - $newday;
                                    $days = $secs / 86400;
                                    $total = $days + $subs->days;
                                    $user->date = date('Y-m-d', strtotime($today.' + '.$total.' days'));
                                } else {
                                    $user->date = date('Y-m-d', strtotime($today.' + '.$subs->days.' days'));
                                }
                            } else {
                                $user->date = date('Y-m-d', strtotime($today.' + '.$subs->days.' days'));
                            }
                            $user->mail_sent = 1;
                            $user->update();

                            $curr = \Illuminate\Support\Facades\DB::table('currencies')->where('is_default', 1)->first();

                            $sub = new UserSubscription;
                            $sub->user_id = $user->id;
                            $sub->subscription_id = $subs->id;
                            $sub->title = $subs->title;
                            $sub->currency_sign = $curr->sign;
                            $sub->currency_code = $curr->name;
                            $sub->currency_value = $curr->value;

                            $sub->price = $subs->price * $curr->value;
                            $sub->days = $subs->days;
                            $sub->allowed_products = $subs->allowed_products;
                            $sub->details = $subs->details;
                            $sub->status = 1;
                            $sub->save();

                            if ($settings->is_smtp == 1) {
                                $mailData = [
                                    'to' => $user->email,
                                    'type' => 'vendor_accept',
                                    'cname' => $user->name,
                                    'oamount' => '',
                                    'aname' => '',
                                    'aemail' => '',
                                    'onumber' => '',
                                ];
                                $mailer = new GeniusMailer();
                                $mailer->sendAutoMail($mailData);
                            }

                            FilamentNotification::make()
                                ->title('Subscription Plan Added Successfully')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\Action::make('ask_verification')
                        ->label('Ask For Verification')
                        ->icon('heroicon-o-question-mark-circle')
                        ->color('warning')
                        ->form([
                            Forms\Components\Textarea::make('details')
                                ->label('Details')
                                ->required(),
                        ])
                        ->action(function (Verification $record, array $data) {
                            $user = $record->user;
                            $settings = Generalsetting::find(1);

                            $user->verifies()->create([
                                'admin_warning' => 1,
                                'warning_reason' => $data['details'],
                            ]);

                            if ($settings->is_smtp == 1) {
                                $mailData = [
                                    'to' => $user->email,
                                    'type' => 'vendor_verification',
                                    'cname' => $user->name,
                                    'oamount' => '',
                                    'aname' => '',
                                    'aemail' => '',
                                    'onumber' => '',
                                ];
                                $mailer = new GeniusMailer();
                                $mailer->sendAutoMail($mailData);
                            }

                            FilamentNotification::make()
                                ->title('Verification Request Sent Successfully')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\Action::make('details')
                        ->label('Details')
                        ->icon('heroicon-o-eye')
                        ->url(fn (Verification $record): string => route('admin-vendor-show', $record->user_id)),

                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
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
            'index' => Pages\ListVerifications::route('/'),
            'create' => Pages\CreateVerification::route('/create'),
            'edit' => Pages\EditVerification::route('/{record}/edit'),
        ];
    }
}
