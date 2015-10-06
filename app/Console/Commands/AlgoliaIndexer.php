<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Contracts\Search;

class AlgoliaIndexer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mrboston:index {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Search $search) {
        // fetch all recipes
        $recipes = collect(
            \DB::table('recipes')->where('is_master', '=', 1)->get()
        )->map(function ($recipe) {
            $ingredients = collect(
                \DB::table('recipe_ingredients')
                ->select('ingredients.*')
                ->join('ingredients', 'recipe_ingredients.ingredient_id' , '=', 'ingredients.id')
                ->where('recipe_ingredients.recipe_id', '=', $recipe->id)->get()
            )->map(function ($ingredient) {
                return $ingredient->name;
            })->toArray();

            return [
                'objectID' => $recipe->id,
                'name' => $recipe->name,
                'slug' => $recipe->slug,
                'ingredients' => implode(',', $ingredients)
            ];  
        });

        $batch = array();
        
        foreach ($recipes as $recipe) {
            array_push($batch, $recipe);

            if (count($batch) == 1000) {
              $search->index('recipes')->saveObjects($batch);
              $batch = array();
            }
        }

        // save objects
        $search->index('recipes')->saveObjects($batch);
        // completion message
        $this->info('All Done');
    }
}
