<?php

namespace App\Providers;

use App\Http\ViewComposers\UserFieldsComposer;
use App\Profession;
use App\User;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::component('components._card','cardygl');
        /*View::composer('common._fields', function($view){
            $professions = Profession::orderBy('title','ASC')->get();
            $view->with(compact('professions'));

        });*/
        //View::composer('common._fields', UserFieldsComposer::class); //Using View Composer to passing data

        Blade::directive('renderygl', function($expression){


            $parts = explode(',',$expression,2);

            $component = $parts[0];
            $args = $parts[1]?$parts[1]:'[]';

            return "<?php echo app('App\Http\ViewComponents\\\\'.{$component},{$args})->toHtml() ?>";

        });

        /***** Route Model Binding  -> Explico un poco: En el controller UserController hay un metodo destroy que recibe
         * un $id como prametro y esto es porque si se pasa el Modelo User laravel no va encontrar, ya que como se va esta
         * trabajando con SoftDeletes laravel solo va encontar User que no no este sofdelete(deletede_at == null), por eso
         * es que es importante personalizar la logica de resolucion y esto es lo que hace el codigo de abajo comentado
         * basicamente dice que la si una ruta contiene un modelo User y esta ruta es igual users.destroy entonces buscame el modelo
         * en los usarios que se han borrado  (softdeletes) para las otras rutas simplemente buscame el usuario normal, $value seria el id
        Route::bind('user', function ($value) {
            if (Route::currentRouteName() === 'users.destroy') {
                return User::withTrashed()->find($value);
            }
            return User::find($value);
        });
        */


    }
}
