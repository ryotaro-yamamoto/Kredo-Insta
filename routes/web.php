<?php

use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\NewInterestController;
use App\Http\Controllers\Admin\AdvertiseController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MickeyController;
use App\Models\Story;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Web\WebDriver;

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/suggestions', [HomeController::class, 'suggestions'])->name('suggestions.index');
    Route::get('/people', [HomeController::class, 'search'])->name('search');

    // Stories
    Route::post('/stories', [StoryController::class, 'store'])->name('stories.store');
    Route::post('/stories/{story}/view', [StoryController::class, 'view']);

    //Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index'); // 一覧
    Route::get('/messages/{userId}', [MessageController::class, 'chat'])->name('messages.chat'); // 個別チャット
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/mickey', [MickeyController::class, 'index'])->name('mickey'); //Riko

    //Admin
    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function () {
        //Users
        Route::get('/users', [UsersController::class, 'index'])->name('users');
        Route::delete('/users/{id}/deactivate', [UsersController::class, 'deactivate'])->name('users.deactivate');
        Route::patch('/users/{id}/activate', [UsersController::class, 'activate'])->name('users.activate');
        Route::get('/users/search', [HomeController::class, 'searchUsers'])->name('search.users');

        //Advertises
        Route::get('/advertises', [AdvertiseController::class, 'index'])->name('advertises');
        Route::get('/advertises/create', [AdvertiseController::class, 'create'])->name('advertises.create');
        Route::post('/advertises', [AdvertiseController::class, 'store'])->name('advertises.store');
        Route::get('/advertises/{id}/edit', [AdvertiseController::class, 'edit'])->name('advertises.edit'); // ✨追加
        Route::patch('/advertises/{id}/update', [AdvertiseController::class, 'update'])->name('advertises.update'); // ✨追加
        Route::delete('/advertises/{id}/destroy', [AdvertiseController::class, 'destroy'])->name('advertises.destroy');

        //Interests
        Route::get('/interests', [NewInterestController::class, 'index'])->name('interests');
        Route::get('/interests/create', [NewInterestController::class, 'create'])->name('interests.create');
        Route::post('/interests', [NewInterestController::class, 'store'])->name('interests.store');
        Route::delete('/interests/{id}/destroy', [NewInterestController::class, 'destroy'])->name('interests.destroy');

        //Posts
        Route::get('/posts', [PostsController::class, 'index'])->name('posts');
        Route::delete('/posts/{id}/invisible', [PostsController::class, 'invisible'])->name('posts.invisible');
        Route::patch('/posts/{id}/visible', [PostsController::class, 'visible'])->name('posts.visible');
        Route::get('/posts/search', [HomeController::class, 'searchPosts'])->name('search.posts');

        //Categories
        Route::get('/categories', [CategoriesController::class, 'index'])->name('categories');
        Route::post('/categories/store', [CategoriesController::class, 'store'])->name('categories.store');
        Route::patch('/categories/{id}/update', [CategoriesController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{id}/destroy', [CategoriesController::class, 'destroy'])->name('categories.destroy');
        Route::get('/categories/{id}/posts', [CategoriesController::class, 'posts'])->name('categories.posts');
    });

    Route::match(['get', 'post'], '/botman', function (Request $request) {
        DriverManager::loadDriver(WebDriver::class);

        $botman = BotManFactory::create([]);

        // 「質問」で選択肢を提示
        $botman->hears('質問', function ($bot) {
            $message = "どの質問を知りたいですか？<br>" .
                    "1. ミッキーについて<br>" .
                    "2. ミッキーさんがやったでしょ？<br>" .
                    "3. ❤️<br>" .
                    "4. You are smart.<br>" .
                    "5. Memory.<br><br>" .
                    "番号で答えてください！";
        
            $bot->reply($message);
        });

        // 各選択肢に対する応答
        $botman->hears('1', function ($bot) {
            $bot->reply('俺はと胸やねん');
        });

        $botman->hears('2', function ($bot) {
            $bot->reply('ほんま俺ちゃうで！信じてや！');
        });

        $botman->hears('3', function ($bot) {
            $bot->reply('たろや〜ん');
        });
        $botman->hears('4', function ($bot) {
            $bot->reply('very thank you');
        });
        $botman->hears('5', function ($bot) {
            $bot->reply('<a href="https://youtu.be/dNAMsBpl7eU" target="_blank" rel="noopener noreferrer">▶️ TAP to open YouTube</a>');
        });
        

        // その他のメッセージ対応
        $botman->fallback(function ($bot) {
            $bot->reply('「質問」と送っていただければ、選択肢が出てきますよ！');
        });
        $botman->listen();
    });

    //Posts
    Route::get('/post', [PostController::class, 'index'])->name('post.index');
    Route::get('/post/create', [PostController::class, 'create'])->name('post.create');
    Route::post('/post/store', [PostController::class, 'store'])->name('post.store');
    Route::get('/post/{id}/show', [PostController::class, 'show'])->name('post.show');
    Route::get('/post/{id}/edit', [PostController::class, 'edit'])->name('post.edit');
    Route::patch('/post/{id}/update', [PostController::class, 'update'])->name('post.update');
    Route::delete('/post/{id}/destroy', [PostController::class, 'destroy'])->name('post.destroy');

    //Comments
    Route::post('/comment/{post_id}/store', [CommentController::class, 'store'])->name('comment.store');
    Route::delete('/comment/{post_id}/destroy', [CommentController::class, 'destroy'])->name('comment.destroy');

    //Profile
    Route::get('/profile/{id}/show', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');//if-elseしてるからidはいらないらしい（header.blade.php L15）
    Route::patch('/profile/{id}/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/{id}/following', [ProfileController::class, 'following'])->name('profile.following');
    Route::get('/profile/{id}/followers', [ProfileController::class, 'followers'])->name('profile.followers');


    //Likes
    Route::post('/like/{post_id}/store', [LikeController::class, 'store'])->name('like.store');
    Route::delete('/like/{post_id}/destroy', [LikeController::class, 'destroy'])->name('like.destroy');

    //Follows
    Route::post('/follow/{user_id}/store', [FollowController::class, 'store'])->name('follow.store');
    Route::delete('/follow/{user_id}/destroy', [FollowController::class, 'destroy'])->name('follow.destroy');

    Route::get('/secret-delete-story', function(){
        $story = Story::find();
    
        if($story){
            $story->delete();
            return "Deleted Successfully";
        }
        return "Story not found";
    });

    Route::get('/secret-show-story', function(){
        $story = Story::get()->all();
    
        return $story;
    });
});
