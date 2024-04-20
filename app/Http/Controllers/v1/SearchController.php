<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\MangaCollection;
use App\Http\Resources\v1\TeamCollection;
use App\Http\Resources\v1\UserCollection;
use App\Models\v1\Manga;
use App\Models\v1\Team;
use App\Models\v1\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected $relationships = [
        'mangas',
        'teams',
        'users',
    ];

    public function __invoke(Request $request)
    {
        $query = $request->query('query');
        $page = $request->query('page') ?: 1;
        $limit = $request->query('limit') ?: 25;
        $isOrdered = $request->boolean('isOrdered');
        $includes = $this->includeRelationship($request);

        if (!$query) {
            return $this->success([]);
        }

        $collections = collect();
        if (in_array('mangas', $includes)) {
            /**
             * @var LengthAwarePaginator $mangas
             */
            $mangas = Manga::where(function (Builder $builder) use ($query) {
                $builder->where([
                    ['title', 'LIKE', '%' . $query . '%', 'or'],
                    ['title_en', 'LIKE', '%' . $query . '%', 'or'],
                    ['author', 'LIKE', '%' . $query . '%', 'or'],
                ])->where('is_approved', 1);
            })
                ->paginate($limit, ['*'], 'page', $page);

            $collection = new MangaCollection($mangas);
            if ($isOrdered) {
                $collections['mangas'] = $collection->collection;
            } else {
                $collections->push(...$collection->collection);
            }
        }

        if (in_array('users', $includes)) {
            /**
             * @var LengthAwarePaginator $users
             */
            $users = User::where('username', 'LIKE', '%' . $query . '%')
                ->paginate($limit, ['*'], 'page', $page);

            $collection = new UserCollection($users);
            if ($isOrdered) {
                $collections['users'] = $collection->collection;
            } else {
                $collections->push(...$collection->collection);
            }
        }

        if (in_array('teams', $includes)) {
            /**
             * @var LengthAwarePaginator $teams
             */
            $teams = Team::where('name', 'LIKE', '%' . $query . '%')
                ->paginate($limit, ['*'], 'page', $page);

            $collection = new TeamCollection($teams);
            if ($isOrdered) {
                $collections['teams'] = $collection->collection;
            } else {
                $collections->push(...$collection->collection);
            }
        }


        $results['data'] = $isOrdered
            ? $collections
            : $collections->shuffle()->all();

        if ($includes) {
            $pages = ${$includes[0]}->lastPage();

            foreach ($includes as $include) {
                if (${$include}->lastPage() > $pages) {
                    $pages = ${$include}->lastPage();
                }
            }

            $results['pagination'] = [
                'page' => ${$includes[0]}->currentPage(),
                'pages' => $pages,
            ];
        }

        return $this->success($results, 200, true);
    }
}
