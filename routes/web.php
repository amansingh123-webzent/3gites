<?php

use App\Http\Controllers\Admin\AdminBroadcastController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminDonationController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminPollController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminTributeController;
use App\Http\Controllers\Auth\AdminUserCreationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\GuestbookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RsvpController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TributeController;
use Illuminate\Support\Facades\Route;

// ── Public routes (no auth required) ────────────────────────────────────────
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Member directory — viewable by guests
Route::get('/members', [MemberController::class, 'index'])->name('members.index');
Route::get('/members/{user}', [MemberController::class, 'show'])->name('members.show');

// Tribute / In Loving Memory pages — viewable by guests
Route::get('/in-loving-memory', [TributeController::class, 'index'])->name('tributes.index');
Route::get('/in-loving-memory/{tribute}', [TributeController::class, 'show'])->name('tributes.show');

// ── Gallery — public ─────────────────────────────────────────────────
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/member/{user}', [GalleryController::class, 'memberGallery'])
    ->name('gallery.member');

// ── Events — public ──────────────────────────────────────────────────
Route::get('/events/calendar-data', function (Illuminate\Http\Request $request) {
    $year  = (int) $request->query('year',  now()->year);
    $month = (int) $request->query('month', now()->month);

    // Clamp to sane values
    $year  = max(2020, min(2100, $year));
    $month = max(1, min(12, $month));

    $events = \App\Models\Event::where('is_published', true)
        ->whereYear('event_date', $year)
        ->whereMonth('event_date', $month)
        ->get()
        ->groupBy(fn ($e) => $e->event_date->format('Y-m-d'))
        ->map(fn ($group) => $group->map(fn ($e) => [
            'id'    => $e->id,
            'title' => $e->title,
            'url'   => route('events.show', $e),
        ])->values());

    return response()->json($events);
})->name('events.calendar-data');

Route::get('/events',         [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// ── Donations — public page, payment requires auth ───────────────────────────
Route::get('/donate', [DonationController::class, 'index'])->name('donate.index');

// ── Store — public listing, cart/checkout requires auth ───────────────────────
Route::get('/store', [StoreController::class, 'index'])->name('store.index');

// ── Static pages ───────────────────────────────────────────────────────────────
Route::get('/about',      [StaticPageController::class, 'about'])->name('about');
Route::get('/leadership', [StaticPageController::class, 'leadership'])->name('leadership');
Route::get('/contact',    [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact',   [ContactController::class, 'send'])
    ->middleware('throttle:contact')
    ->name('contact.send');
Route::get('/comments',   [GuestbookController::class, 'index'])->name('guestbook.index');
Route::post('/comments',  [GuestbookController::class, 'store'])
    ->middleware(['auth', 'account.active', 'password.fresh'])
    ->name('guestbook.store');
Route::delete('/comments/{comment}', [GuestbookController::class, 'destroy'])
    ->middleware(['auth', 'account.active'])
    ->name('guestbook.destroy');

// ── Stripe Webhook (no CSRF, no auth) ────────────────────────────────────────
Route::post('/stripe/webhook', [OrderController::class, 'webhook'])->name('stripe.webhook');

// ── Authenticated member routes ──────────────────────────────────────────────
Route::middleware(['auth', 'account.active', 'password.fresh'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Donations — authenticated actions ────────────────────────────────────────
    Route::post('/donate/intent',   [DonationController::class, 'createIntent'])->name('donate.intent');
    Route::post('/donate/confirm',  [DonationController::class, 'confirm'])->name('donate.confirm');
    Route::get('/donate/thank-you', [DonationController::class, 'thankYou'])->name('donate.thankyou');

    // ── Cart ─────────────────────────────────────────────────────────────────────
    Route::get('/cart',                    [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add',               [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{productId}',      [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{productId}',     [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart',                 [CartController::class, 'clear'])->name('cart.clear');

    // ── Checkout ─────────────────────────────────────────────────────────────────
    Route::post('/checkout',               [OrderController::class, 'checkout'])->name('checkout');
    Route::get('/checkout/success',        [OrderController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancelled',      [OrderController::class, 'cancelled'])->name('checkout.cancelled');

    // Profile editing — protected by ProfilePolicy in controller
    Route::get('/members/{user}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/members/{user}', [ProfileController::class, 'update'])->name('profile.update');

    // Photo uploads — separate endpoints for clarity
    Route::post('/members/{user}/photo/teen', [ProfileController::class, 'uploadTeenPhoto'])
        ->name('profile.photo.teen');
    Route::post('/members/{user}/photo/recent', [ProfileController::class, 'uploadRecentPhoto'])
        ->name('profile.photo.recent');
    Route::delete('/members/{user}/photo/{type}', [ProfileController::class, 'deletePhoto'])
        ->name('profile.photo.delete');

    // Password change (from Module 2)
    Route::get('/change-password', [ProfileController::class, 'showChangePassword'])
        ->name('password.change');
    Route::post('/change-password', [ProfileController::class, 'updatePassword'])
        ->name('password.change.update');

    // ── Message Board (auth required) ────────────────────────────────────────────
    // Posts
    Route::get('/board',              [PostController::class, 'index'])->name('posts.index');
    Route::get('/board/create',       [PostController::class, 'create'])->name('posts.create');
    Route::post('/board',             [PostController::class, 'store'])->name('posts.store');
    Route::get('/board/{post}',       [PostController::class, 'show'])->name('posts.show');
    Route::delete('/board/{post}',    [PostController::class, 'destroy'])->name('posts.destroy');

    // Admin-only post actions
    Route::patch('/board/{post}/pin', [PostController::class, 'togglePin'])
        ->name('posts.pin')
        ->middleware('role:admin');

    // Comments
    Route::post('/board/{post}/comments',            [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/board/{post}/comments/{comment}',[CommentController::class, 'destroy'])->name('comments.destroy');

    // ── Gallery — authenticated actions ─────────────────────────────────────────
    // Member: upload to own gallery
    Route::post('/gallery/upload', [GalleryController::class, 'upload'])
        ->name('gallery.upload');

    // Delete a photo (owner or admin)
    Route::delete('/gallery/photos/{photo}', [GalleryController::class, 'destroy'])
        ->name('gallery.destroy');

    // ── RSVP — authenticated members only ────────────────────────────────────────
    Route::post('/events/{event}/rsvp',   [RsvpController::class, 'store'])->name('rsvp.store');
    Route::delete('/events/{event}/rsvp', [RsvpController::class, 'destroy'])->name('rsvp.destroy');

    // ── Polls — authenticated members ────────────────────────────────────────────
    Route::get('/polls',           [PollController::class, 'index'])->name('polls.index');
    Route::get('/polls/{poll}',    [PollController::class, 'show'])->name('polls.show');
    Route::post('/polls/{poll}/vote', [PollController::class, 'vote'])->name('polls.vote');
});

// ── Admin: upload to class gallery ──────────────────────────────────────────
Route::middleware(['auth', 'account.active', 'role:admin'])
    ->post('/gallery/admin/upload', [GalleryController::class, 'adminUpload'])
    ->name('gallery.admin.upload');

// ── Admin routes ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'account.active', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/members/create', [AdminUserCreationController::class, 'create'])
        ->name('members.create');
    Route::post('/members/create', [AdminUserCreationController::class, 'store'])
        ->name('members.store');
    Route::patch('/members/{user}/toggle-lock', [AdminUserCreationController::class, 'toggleLock'])
        ->name('members.toggle-lock');

    // Admin tribute management
    Route::get('/tributes/{tribute}/edit', [AdminTributeController::class, 'edit'])
        ->name('tributes.edit');
    Route::patch('/tributes/{tribute}', [AdminTributeController::class, 'update'])
        ->name('tributes.update');
    Route::post('/tributes/{tribute}/photo', [AdminTributeController::class, 'uploadPhoto'])
        ->name('tributes.photo');

    // ── Admin: event management ──────────────────────────────────────────────────
    Route::get('/events',                    [AdminEventController::class, 'index'])->name('events.index');
    Route::get('/events/create',             [AdminEventController::class, 'create'])->name('events.create');
    Route::post('/events',                   [AdminEventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit',       [AdminEventController::class, 'edit'])->name('events.edit');
    Route::patch('/events/{event}',          [AdminEventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}',         [AdminEventController::class, 'destroy'])->name('events.destroy');
    Route::patch('/events/{event}/publish',  [AdminEventController::class, 'togglePublish'])->name('events.publish');

    // RSVP list per event (admin only)
    Route::get('/events/{event}/rsvps',      [AdminEventController::class, 'rsvps'])->name('events.rsvps');

    // ── Admin: poll management ───────────────────────────────────────────────────
    Route::get('/polls',                      [AdminPollController::class, 'index'])->name('polls.index');
    Route::get('/polls/create',               [AdminPollController::class, 'create'])->name('polls.create');
    Route::post('/polls',                     [AdminPollController::class, 'store'])->name('polls.store');
    Route::get('/polls/{poll}/edit',          [AdminPollController::class, 'edit'])->name('polls.edit');
    Route::patch('/polls/{poll}',             [AdminPollController::class, 'update'])->name('polls.update');
    Route::delete('/polls/{poll}',            [AdminPollController::class, 'destroy'])->name('polls.destroy');
    Route::patch('/polls/{poll}/publish',     [AdminPollController::class, 'publish'])->name('polls.publish');
    Route::patch('/polls/{poll}/close',       [AdminPollController::class, 'close'])->name('polls.close');

    // ── Admin: donations, products, orders ───────────────────────────────────────
    // Donations (read-only)
    Route::get('/donations', [AdminDonationController::class, 'index'])->name('donations.index');

    // Products
    Route::get('/products',              [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create',       [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products',             [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit',  [AdminProductController::class, 'edit'])->name('products.edit');
    Route::patch('/products/{product}',     [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}',    [AdminProductController::class, 'destroy'])->name('products.destroy');
    Route::patch('/products/{product}/toggle', [AdminProductController::class, 'toggle'])->name('products.toggle');

    // Orders
    Route::get('/orders',                [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}',        [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/refund', [AdminOrderController::class, 'refund'])->name('orders.refund');

    // ── Admin: broadcast email ─────────────────────────────────────────────────────
    Route::get('/broadcast',  [AdminBroadcastController::class, 'index'])->name('broadcast.index');
    Route::post('/broadcast', [AdminBroadcastController::class, 'send'])->name('broadcast.send');

    // ── Admin: email setup guide ───────────────────────────────────────────────────
    Route::get('/email-guide', fn() => view('admin.email-guide'))->name('email-guide');
});

require __DIR__ . '/auth.php';
