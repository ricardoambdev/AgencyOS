<?php

use App\Core\Controllers\CommentController;
use App\Domains\Agenda\Controllers\EventoController;
use App\Domains\Crm\Controllers\LeadController;
use App\Domains\Cliente\Controllers\ClienteController;
use App\Domains\Cliente\Controllers\PortalController;
use App\Domains\Comercial\Controllers\ContratoController;
use App\Domains\Config\Controllers\AutomationController;
use App\Domains\Config\Controllers\CustomFieldController;
use App\Domains\Config\Controllers\RoleController;
use App\Domains\Config\Controllers\SettingController;
use App\Domains\Config\Controllers\WebhookController;
use App\Domains\Config\Controllers\WorkflowController;
use App\Domains\Projeto\Controllers\ProjectTemplateController;
use App\Domains\Financeiro\Controllers\InvoiceController;
use App\Domains\Producao\Controllers\EntregavelController;
use App\Domains\Projeto\Controllers\ProjetoController;
use App\Domains\Equipamento\Controllers\EquipamentoController;
use App\Domains\Horas\Controllers\LancamentoHoraController;
use App\Domains\Wiki\Controllers\ArtigoController;
use App\Domains\Projeto\Controllers\TaskController;
use App\Domains\Relatorios\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');

    Route::get('/forgot-password', [AuthController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email')->middleware('throttle:10,1');
    Route::get('/reset-password/{token}', [AuthController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
    Route::get('/email/verify', [AuthController::class, 'showVerificationNotice'])
        ->middleware('auth')->name('verification.notice');
    Route::post('/email/verification-notification', [AuthController::class, 'resendVerification'])
        ->middleware('auth')->name('verification.send');
});

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['signed'])->name('verification.verify');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/customize', [DashboardController::class, 'customize'])->name('dashboard.customize');
    Route::post('dashboard/customize', [DashboardController::class, 'updateCustomization'])->name('dashboard.customize.update');

    Route::resource('leads', LeadController::class);
    Route::resource('clientes', ClienteController::class);
    Route::resource('projetos', ProjetoController::class);
    Route::post('projetos/{projeto}/tasks', [ProjetoController::class, 'storeTask'])->name('projetos.tasks.store');
    Route::patch('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::resource('agenda', EventoController::class);
    Route::resource('financeiro', InvoiceController::class);
    Route::resource('producao', EntregavelController::class)->parameters(['producao' => 'entregavel']);
    Route::resource('wiki', ArtigoController::class)->parameters(['wiki' => 'artigo']);
    Route::resource('equipamentos', EquipamentoController::class)->parameters(['equipamentos' => 'equipamento']);
    Route::get('horas/tasks', [LancamentoHoraController::class, 'tasksForProject'])->name('horas.tasks');
    Route::resource('horas', LancamentoHoraController::class)->parameters(['horas' => 'lancamento']);
    Route::resource('comercial', ContratoController::class)->parameters(['comercial' => 'contrato']);
    Route::get('comercial/{contrato}/attachments/{attachment}/download', [ContratoController::class, 'downloadAttachment'])
        ->name('comercial.attachments.download');
    Route::resource('templates', ProjectTemplateController::class)->parameters(['templates' => 'template']);
    Route::get('templates/{template}/apply', [ProjectTemplateController::class, 'apply'])->name('templates.apply');
    Route::post('templates/{template}/apply', [ProjectTemplateController::class, 'storeApply'])->name('templates.apply.store');
    Route::post('producao/{entregavel}/aprovar', [EntregavelController::class, 'aprovar'])->name('producao.aprovar');
    Route::post('producao/{entregavel}/versao', [EntregavelController::class, 'novaVersao'])->name('producao.nova-versao');
    Route::get('producao/attachments/{attachment}/download', [EntregavelController::class, 'downloadAttachment'])->name('producao.attachments.download');

    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');

Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
Route::post('/comments/{comment}/react', [CommentController::class, 'toggleReaction'])->name('comments.react');

    Route::get('/favoritos', [FavoriteController::class, 'index'])->name('favoritos.index');
    Route::post('/favoritos/toggle', [FavoriteController::class, 'toggle'])->name('favoritos.toggle');

    Route::get('/anexos/{attachment}/download', [AttachmentController::class, 'download'])->name('attachments.download');
    Route::post('/anexos', [AttachmentController::class, 'store'])->name('attachments.store');
    Route::delete('/anexos/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');

    Route::prefix('config')->name('config.')->middleware('can:cap:config.manage')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::get('settings/edit', [SettingController::class, 'editSettings'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'updateSettings'])->name('settings.update');
        Route::resource('roles', RoleController::class);
        Route::resource('workflows', WorkflowController::class);
        Route::resource('automations', AutomationController::class);
        Route::resource('webhooks', WebhookController::class);
        Route::resource('custom-fields', CustomFieldController::class)->parameters(['custom-fields' => 'customField']);
    });

    Route::prefix('relatorios')->name('relatorios.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('{report}/export', [ReportController::class, 'export'])->name('export');
    });
});

Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('/{token}', [PortalController::class, 'show'])->name('dashboard');
    Route::get('/{token}/projeto/{projeto}', [PortalController::class, 'project'])->name('project');
    Route::post('/{token}/comment', [PortalController::class, 'comment'])->name('comment');
    Route::post('/{token}/approve', [PortalController::class, 'approve'])->name('approve');
    Route::get('/{token}/download/{attachment}', [PortalController::class, 'download'])->name('download');
});
